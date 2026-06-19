<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerPricing;
use App\Models\Invoice;
use App\Models\InvoiceFlatCharges;
use App\Models\InvoiceLineItems;
use App\Models\InvoiceOrders;
use App\Models\Order;
use App\Models\RecurringOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceGeneratorController extends Controller
{
    // Session key used for in-memory draft storage.
    // Swap the body of getDraft()/saveDraft()/clearDraft() to hit a DraftInvoice
    // DB table later without touching any other method in this class.
    private const DRAFT_SESSION_KEY = 'invoice_generator_draft';

    public function index()
    {
        $customers = Customer::orderBy('name')->get();

        return view('vendor.backpack.crud.Invoice.customer-invoice', [
            'customers' => $customers,
        ]);
    }

    /**
     * Step 1: Admin selects customer + date range.
     * Pulls eligible orders/recurring orders, applies customer pricing,
     * builds a draft structure, and stores it (currently in session).
     */
    public function buildDraft(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        $customerId = $request->customer_id;
        $startDate  = $request->start_date;
        $endDate    = $request->end_date;

        $pricing = CustomerPricing::where('customer_id', $customerId)->first();
        $usingDefaultPricing = false;

        if (!$pricing) {
            $usingDefaultPricing = true;
        }

        // ---- One-time orders ----
        $orders = Order::where('customer_id', $customerId)
            ->where('origin', 'manual')
            ->whereIn('status', ['valid', 'completed'])
            ->where(function ($q) {
                $q->whereNull('payment_status')->orWhere('payment_status', 'unpaid');
            })
            ->whereNull('invoice_id')
            ->whereBetween('delivery_date', [$startDate, $endDate])
            ->with(['items.product'])
            ->get();

        // ---- Recurring orders ----
        $recurringOrders = RecurringOrder::whereNull('recurring_payment_status')
            ->whereNull('invoice_id')
            ->whereBetween('scheduled_delivery_date', [$startDate, $endDate])
            ->whereHas('order', function ($q) use ($customerId) {
                $q->where('customer_id', $customerId)->where('origin', 'manual');
            })
            ->with(['order.items.product'])
            ->get();

        $lineItems = [];
        $orderRefs = [];

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $lineItems[] = $this->buildLineItem($item, $pricing, $order->id);
            }
            $orderRefs[] = [
                'invoiceable_type' => Order::class,
                'invoiceable_id'   => $order->id,
                'order_type'       => 'one_time',
                'label'            => 'Order #'.$order->id,
                'delivery_date'    => $order->delivery_date,
            ];
        }

        foreach ($recurringOrders as $recurring) {
            $parentOrder = $recurring->order;
            foreach ($parentOrder->items as $item) {
                $lineItems[] = $this->buildLineItem($item, $pricing, $parentOrder->id);
            }
            $orderRefs[] = [
                'invoiceable_type' => RecurringOrder::class,
                'invoiceable_id'   => $recurring->id,
                'order_type'       => 'recurring',
                'label'            => 'Recurring #' . $recurring->id . ' (Order #' . str_pad($parentOrder->invoice_id ?? $parentOrder->id, 4, '0', STR_PAD_LEFT) . ')',
                'delivery_date'    => $recurring->scheduled_delivery_date,
            ];
        }

        if (empty($orderRefs)) {
            return response()->json([
                'success' => false,
                'message' => 'No eligible orders found for this customer in the selected range.',
            ], 422);
        }

        // Once-per-invoice flat charges from customer_pricing
        $flatCharges = [];
        if ($pricing) {
            foreach (['hazmat_fee', 'delivery_fee', 'other_charges'] as $key) {
                if (!empty($pricing->$key) && (float) $pricing->$key != 0) {
                    $flatCharges[] = [
                        'charge_key' => $key,
                        'label'      => null,
                        'amount'     => (float) $pricing->$key,
                    ];
                }
            }
        }

        $draft = [
            'customer_id'   => $customerId,
            'start_date'    => $startDate,
            'end_date'      => $endDate,
            'order_refs'    => $orderRefs,
            'line_items'    => $lineItems,
            'flat_charges'  => $flatCharges,
        ];

        $draft['totals'] = $this->calculateTotals($draft);

        $this->saveDraft($draft);

        return response()->json([
            'success'              => true,
            'draft'                => $draft,
            'using_default_pricing' => $usingDefaultPricing,
        ]);
    }

    /**
     * Step 2: Admin edits the draft — remove an order/line item,
     * add a flat charge or discount, adjust amounts.
     */
    public function updateDraft(Request $request)
    {
        $draft = $this->getDraft();

        if (!$draft) {
            return response()->json([
                'success' => false,
                'message' => 'No active draft found. Please rebuild the draft.',
            ], 404);
        }

        $action = $request->input('action');

        switch ($action) {
            case 'remove_order_ref':
                $request->validate([
                    'invoiceable_type' => 'required|string',
                    'invoiceable_id'   => 'required',
                ]);

                $draft['order_refs'] = array_values(array_filter($draft['order_refs'], function ($ref) use ($request) {
                    return !($ref['invoiceable_type'] === $request->invoiceable_type
                        && $ref['invoiceable_id'] == $request->invoiceable_id);
                }));

                // Drop line items tied to the parent order if it no longer has any ref pointing to it
                $remainingOrderIds = $this->collectParentOrderIds($draft['order_refs']);
                $draft['line_items'] = array_values(array_filter($draft['line_items'], function ($li) use ($remainingOrderIds) {
                    return in_array($li['order_id'], $remainingOrderIds);
                }));
                break;

            case 'add_flat_charge':
                $request->validate([
                    'charge_key' => 'required|string',
                    'amount'     => 'required|numeric',
                    'label'      => 'nullable|string',
                ]);

                $draft['flat_charges'][] = [
                    'charge_key' => $request->charge_key, // e.g. 'custom' or 'discount'
                    'label'      => $request->label,
                    'amount'     => (float) $request->amount, // negative for discount
                ];
                break;

            case 'remove_flat_charge':
                $request->validate(['index' => 'required|integer']);
                unset($draft['flat_charges'][$request->index]);
                $draft['flat_charges'] = array_values($draft['flat_charges']);
                break;

            case 'update_line_item':
                $request->validate([
                    'index'      => 'required|integer',
                    'unit_price' => 'nullable|numeric',
                    'quantity'   => 'nullable|integer',
                ]);

                if (isset($draft['line_items'][$request->index])) {
                    if ($request->filled('unit_price')) {
                        $draft['line_items'][$request->index]['unit_price'] = (float) $request->unit_price;
                    }
                    if ($request->filled('quantity')) {
                        $draft['line_items'][$request->index]['quantity'] = (int) $request->quantity;
                    }
                    $li = $draft['line_items'][$request->index];
                    $draft['line_items'][$request->index]['total_price'] = $li['unit_price'] * $li['quantity'];
                }
                break;

            default:
                return response()->json(['success' => false, 'message' => 'Unknown action.'], 422);
        }

        $draft['totals'] = $this->calculateTotals($draft);

        $this->saveDraft($draft);

        return response()->json(['success' => true, 'draft' => $draft]);
    }

    /**
     * Step 3: Finalize — persist invoice, pivot rows, line items, flat charges.
     * Marks all included orders/recurring_orders as invoiced.
     */
    public function finalize(Request $request)
    {
        $draft = $this->getDraft();

        if (!$draft || empty($draft['order_refs'])) {
            return response()->json([
                'success' => false,
                'message' => 'No active draft to finalize.',
            ], 404);
        }

        $invoice = DB::transaction(function () use ($draft) {
            $invoice = Invoice::create([
                'invoice_number'     => $this->generateInvoiceNumber(),
                'total_amount'       => $draft['totals']['total'],
                'payment_status'     => 'pending',
                'parent_invoice_id'  => null,
                'recurring_sequence' => 1,
                'invoice_date'       => now()->format('Y-m-d'),
                'customer_id'       => $draft['customer_id'],
            ]);

            foreach ($draft['order_refs'] as $ref) {
                InvoiceOrders::create([
                    'invoice_id'       => $invoice->id,
                    'invoiceable_type' => $ref['invoiceable_type'],
                    'invoiceable_id'   => $ref['invoiceable_id'],
                    'order_type'       => $ref['order_type'],
                ]);

                // Mark as invoiced
                if ($ref['invoiceable_type'] === Order::class) {
                    Order::where('id', $ref['invoiceable_id'])->update(['invoice_id' => $invoice->id]);
                } else {
                    RecurringOrder::where('id', $ref['invoiceable_id'])->update(['invoice_id' => $invoice->id]);
                }
            }

            foreach ($draft['line_items'] as $li) {
                InvoiceLineItems::create([
                    'invoice_id'  => $invoice->id,
                    'order_id'    => $li['order_id'],
                    'product_id'  => $li['product_id'],
                    'description' => $li['description'],
                    'quantity'    => $li['quantity'],
                    'unit_price'  => $li['unit_price'],
                    'total_price' => $li['total_price'],
                ]);
            }

            foreach ($draft['flat_charges'] as $fc) {
                InvoiceFlatCharges::create([
                    'invoice_id' => $invoice->id,
                    'charge_key' => $fc['charge_key'],
                    'label'      => $fc['label'],
                    'amount'     => $fc['amount'],
                ]);
            }

            return $invoice;
        });

        $this->clearDraft();

        return response()->json([
            'success'    => true,
            'invoice_id' => $invoice->id,
            'pdf_url'    => route('admin.invoice-generator.pdf', $invoice->id),
        ]);
    }

    /**
     * Generate the consolidated invoice PDF.
     */
    public function downloadPdf($invoiceId)
    {
        $invoice = Invoice::with(['lineItems.product', 'flatCharges'])->findOrFail($invoiceId);

        $invoiceOrders = InvoiceOrders::where('invoice_id', $invoice->id)->get();

        // Pull customer + reference info from the first linked order
        $customer = $invoice->customer;

        $subTotal = $invoice->lineItems->sum('total_price');
        $flatChargesTotal = $invoice->flatCharges->sum('amount');

        $pdf = Pdf::loadView('emails.invoice-pdf-consolidated', [
            'invoice'        => $invoice,
            'invoiceOrders'  => $invoiceOrders,
            'customer'       => $customer,
            'lineItems'      => $invoice->lineItems,
            'flatCharges'    => $invoice->flatCharges,
            'subTotal'       => $subTotal,
            'flatChargesTotal' => $flatChargesTotal,
            'totalAmount'    => $invoice->total_amount,
        ]);

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    // ===================== Helpers =====================

    private function buildLineItem($item, $pricing, $orderId): array
    {
        if ($pricing) {
            if ($item->product_id == 1 && $pricing->ice_price !== null) {
                $unitPrice = $pricing->ice_price;
            } elseif ($item->product_id == 2 && $pricing->box_price !== null) {
                $unitPrice = $pricing->box_price;
            } else {
                $unitPrice = $item->unit_price;
            }
        } else {
            // No customer pricing — fall back to the product's default price
            $unitPrice = $item->product?->price ?? $item->unit_price;
        }

        return [
            'order_id'    => $orderId,
            'product_id'  => $item->product_id,
            'description' => $item->product->product_name ?? 'Product',
            'quantity'    => $item->amount_of_items,
            'unit_price'  => (float) $unitPrice,
            'total_price' => (float) $unitPrice * $item->amount_of_items,
        ];
    }

    private function calculateTotals(array $draft): array
    {
        $subTotal = array_sum(array_column($draft['line_items'], 'total_price'));
        $flatTotal = array_sum(array_column($draft['flat_charges'], 'amount'));

        return [
            'sub_total'   => round($subTotal, 2),
            'flat_total'  => round($flatTotal, 2),
            'total'       => round($subTotal + $flatTotal, 2),
        ];
    }

    private function collectParentOrderIds(array $orderRefs): array
    {
        $ids = [];
        foreach ($orderRefs as $ref) {
            if ($ref['invoiceable_type'] === Order::class) {
                $ids[] = $ref['invoiceable_id'];
            } else {
                $recurring = RecurringOrder::find($ref['invoiceable_id']);
                if ($recurring) {
                    $ids[] = $recurring->order_id;
                }
            }
        }
        return array_unique($ids);
    }

    private function generateInvoiceNumber(): string
    {
        $last = Invoice::orderByDesc('id')->value('invoice_number');
        if (!$last) {
            return '3000';
        }
        // Strip any prefix/formatting and extract the numeric part
        $numeric = (int) preg_replace('/[^0-9]/', '', $last);

        return (string) ($numeric + 1);
    }

    // ----- Draft storage: session-backed for now -----
    // To switch to a DB-backed draft later, replace the body of these three
    // methods to read/write a DraftInvoice model instead of session(), and
    // every calling method above stays unchanged.

    private function getDraft(): ?array
    {
        return session(self::DRAFT_SESSION_KEY);
    }

    private function saveDraft(array $draft): void
    {
        session([self::DRAFT_SESSION_KEY => $draft]);
    }

    private function clearDraft(): void
    {
        session()->forget(self::DRAFT_SESSION_KEY);
    }


    public function viewInvoice($invoiceId)
    {
        if (!request()->ajax()) {
            return redirect()->back()->with('error', 'Direct access not allowed.');
        }


        $invoice = Invoice::with(['lineItems.product', 'flatCharges'])->findOrFail($invoiceId);

        $invoiceOrders = InvoiceOrders::where('invoice_id', $invoice->id)->get();

        // Pull customer + reference info from the first linked order
        $customer = $invoice->customer;

        $subTotal = $invoice->lineItems->sum('total_price');
        $flatChargesTotal = $invoice->flatCharges->sum('amount');

        if (request()->ajax()) {
            return view('emails.invoice-pdf-consolidated', [
                'invoice'        => $invoice,
                'invoiceOrders'  => $invoiceOrders,
                'customer'       => $customer,
                'lineItems'      => $invoice->lineItems,
                'flatCharges'    => $invoice->flatCharges,
                'subTotal'       => $subTotal,
                'flatChargesTotal' => $flatChargesTotal,
                'totalAmount'    => $invoice->total_amount,
            ])->render();
        }

        abort(403);
    }

}
