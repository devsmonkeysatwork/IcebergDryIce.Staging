<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Invoice;
use Carbon\Carbon;

class MigrateCcOrders extends Command
{
    protected $signature = 'migrate:cc-orders';
    protected $description = 'Migrate credit card orders from old database';

    public function handle()
    {
        $this->info('Starting CC orders migration...');

        try {
            DB::beginTransaction();

            $this->migrateCcOrders();

            DB::commit();
            $this->info('CC orders migration completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Migration failed: ' . $e->getMessage());
            $this->error('Line: ' . $e->getLine());
            $this->error('File: ' . $e->getFile());
            return 1;
        }

        return 0;
    }

    private function migrateCcOrders()
    {
        $currentTimestamp = time();
        $ccOrders = DB::table(env('OLD_DB_DATABASE', 'iceberg_db') . '.cc_orders')
            ->orderBy('order_date', 'asc')
            ->where('order_date', '>', $currentTimestamp)
            ->get();

        $this->info('Found ' . count($ccOrders) . ' CC orders to migrate');

        $bar = $this->output->createProgressBar(count($ccOrders));
        $bar->start();

        foreach ($ccOrders as $ccOrder) {
            $this->createOrderFromCc($ccOrder);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Migrated ' . count($ccOrders) . ' CC orders');
    }

    private function createOrderFromCc($ccOrder)
    {
        // Find or create customer by email
        $customer = Customer::where('email', $ccOrder->email)->first();

        if (!$customer) {
            // Create customer from cc_order info
            $customer = Customer::create([
                'name' => $ccOrder->name,
                'email' => $ccOrder->email,
                'password' => bcrypt('password'),
                'phone' => $ccOrder->phone ?? '',
                'address' => $ccOrder->address ?? '',
                'city' => $ccOrder->city ?? '',
                'postal_code' => $ccOrder->postal ?? '',
                'province' => $ccOrder->province && $ccOrder->province != '' ?$ccOrder->province : 'BC',
            ]);
        }

        // Map payment status based on old status
        $paymentStatus = $ccOrder->status >= 2 ? 'paid' : 'pending';

        // Get delivery date from year/month/day
        $deliveryDate = Carbon::create($ccOrder->year, $ccOrder->month, $ccOrder->day);

        // Calculate amounts
        $iceAmount = (float)$ccOrder->ice_amount;
        $boxAmount = (float)$ccOrder->box_amount;

        // Use actual charges from cc_order (in cents, divide by 100)
        $iceCharge = (float)$ccOrder->ice_charge / 100;
        $boxCharge = (float)$ccOrder->box_charge / 100;
        $delivCharge = (float)$ccOrder->deliv_charge;

        // Calculate totals
        $iceTotal = $iceAmount * $iceCharge;
        $boxTotal = $boxAmount * $boxCharge;
        $subTotal = $iceTotal + $boxTotal;

        // Tax calculation (from Cc_order class methods)
        $iceTax = $iceTotal * 0.05;  // 5% GST on ice
        $boxTax = $boxTotal * 0.12;  // 12% tax on boxes
        $delivTax = $delivCharge * 0.05; // 5% GST on delivery
        $totalTax = $iceTax + $boxTax + $delivTax;

        $totalCost = $subTotal + $delivCharge + $totalTax;

        // Create order
        $order = Order::create([
            'customer_id' => $customer->id,
            'customer_name' => $ccOrder->name,
            'email' => $ccOrder->email,
            'phone' => $ccOrder->phone ?? '',
            'amount_of_ice' => $iceAmount,
            'amount_of_boxes' => $boxAmount,
            'origin' => 'online',
            'recurring' => Order::NON_RECURRING,
            'location_name' => $ccOrder->company ?? '',
            'address' => $ccOrder->address ?? '',
            'unit' => '', // Not in cc_orders table
            'city' => $ccOrder->city ?? '',
            'postal_code' => $ccOrder->postal ?? '',
            'province' => $ccOrder->province && $ccOrder->province != '' ?$ccOrder->province : 'BC',
            'country' => $ccOrder->country ?? 'Canada',
            'pickup_delivery' => $ccOrder->pickup == 1 ? 'pickup' : 'delivery',
            'status' => 'completed',
            'hazmat' => 0,
            'delivery_date' => $deliveryDate,
            'notes' => $this->buildCcOrderNotes($ccOrder),
            'sub_total' => $subTotal,
            'delivery_cost' => $delivCharge,
            'tax' => $totalTax,
            'total_cost' => $totalCost,
            'payment_status' => 'paid',
            'supplier_id' => null,
            'invoice_id' => null,
            'push' => 1,
        ]);

        // Create order items for ice
        if ($iceAmount > 0) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => 1, // Ice product
                'amount_of_items' => $iceAmount,
                'unit_price' => $iceCharge,
                'total_price' => $iceTotal,
            ]);
        }

        // Create order items for boxes
        if ($boxAmount > 0) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => 2, // Box product
                'amount_of_items' => $boxAmount,
                'unit_price' => $boxCharge,
                'total_price' => $boxTotal,
            ]);
        }

//        $invoice = Invoice::create([
//            'invoice_type' => 'one_time',
//            'invoiceable_type' => Order::class,
//            'invoiceable_id' => $order->id,
//            'invoice_number' => '0000',
//            'total_amount' => $totalCost,
//            'payment_status' => 'paid',
//            'transaction_json' => $this->buildCcOrderNotes($order),
//            'invoice_date' => $deliveryDate,
//        ]);
//
//        $order->update(['invoice_id' => $invoice->id]);
    }


    /**
     * Build notes from cc_order details and transaction info
     */
    private function buildCcOrderNotes($ccOrder)
    {
        $notes = $ccOrder->notes ?? '';

        // Add transaction details to notes
        if (!empty($ccOrder->cp_x_trans_id)) {
            $notes .= "\n\n[PAYMENT INFO]";
            $notes .= "\nTransaction ID: " . $ccOrder->cp_x_trans_id;

            if (!empty($ccOrder->cp_retrieval_ref_no)) {
                $notes .= "\nReference: " . $ccOrder->cp_retrieval_ref_no;
            }

            if (!empty($ccOrder->cp_transactioncardtype)) {
                $notes .= "\nCard Type: " . $ccOrder->cp_transactioncardtype;
            }

            if (!empty($ccOrder->cp_x_response_reason_text)) {
                $notes .= "\nResponse: " . $ccOrder->cp_x_response_reason_text;
            }
        }

        // Add terms if accepted
        if ($ccOrder->terms == 1) {
            $notes .= "\n[Terms & Conditions Accepted]";
        }

        return trim($notes);
    }

    /**
     * Get readable card type
     */
    private function getCardType($cardType)
    {
        if (empty($cardType)) {
            return 'credit_card';
        }

        $cardType = strtolower($cardType);

        $mapping = [
            'visa' => 'visa',
            'mastercard' => 'mastercard',
            'amex' => 'amex',
            'discover' => 'discover',
        ];

        return $mapping[$cardType] ?? 'credit_card';
    }
}
