<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Mail\CustomerRegisteredMail;
use App\Mail\DryIceOrderEmail;
use App\Models\Invoice;
use App\Models\OrderItem;
use App\Models\RecurringOrder;
use App\Models\StockMovement;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Carbon\Carbon;
use App\Mail\OrderPlacedMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\ClosestSupplierService;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Validator;


class OrderCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(Order::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/orders');
        CRUD::setEntityNameStrings('order', 'orders');
        CRUD::setListView('vendor.backpack.crud.order-list');
    }

    protected function setupListOperation()
    {
        CRUD::column('id')->label('Order #');
        CRUD::column('customer_name')->label('Customer Name');
        CRUD::addColumn([
            'name' => 'delivery_date',
            'type' => 'datetime',
            'label' => 'Delivery Date',
            'format' => 'YYYY-MM-DD HH:mm'
        ]);
        CRUD::column('status')->label('Status');
        CRUD::column('total_cost')->label('Total');
        CRUD::column('origin')->label('Origin');
        CRUD::column('recurring')->label('Recurring');

        // Sort newest first
        CRUD::orderBy('id', 'desc');
    }

    public function index()
    {
        $this->setupListOperation();
        $crud = $this->crud;

        // Get pagination parameters
        $perPage = request('per_page') ?? 10;
        $page = request('page', 1);

        // Get regular orders
        $ordersQuery = Order::query()
            ->select('id', 'invoice_id', 'customer_name', 'delivery_date', 'status', 'total_cost', 'origin', 'recurring', 'payment_status')
            ->when(request('status'), function($query, $status) {
                return $query->where('status', $status);
            })
            ->when(request('transfer_status'), function($query, $transferStatus) {
                return $query->where('push', $transferStatus);
            })
            ->when(request('recurring'), function($query, $recurring) {
                return $query->where('recurring', $recurring);
            })
            ->when(request('customer_id'), function($query, $customerId) {
                return $query->where('customer_id', $customerId);
            })
            ->when(request('payment_status'), function ($query, $paymentStatus) {
                if ($paymentStatus === 'paid') {
                    return $query->where('payment_status', 'paid');
                }
                if ($paymentStatus === 'pending') {
                    return $query->whereNull('payment_status');
                }
                return $query;
            });

        // Get recurring orders
        $recurringOrdersQuery = RecurringOrder::query()
            ->with('order')
            ->when(request('status'), function($query, $status) {
                return $query->where('status', $status);
            })
            ->when(request('customer_id'), function($query, $customerId) {
                return $query->whereHas('order', function($q) use ($customerId) {
                    $q->where('customer_id', $customerId);
                });
            })
            ->when(request('payment_status'), function ($query, $paymentStatus) {
                if ($paymentStatus === 'paid') {
                    return $query->where('recurring_payment_status', 'paid'); // Fixed column name
                }
                if ($paymentStatus === 'pending') {
                    return $query->whereNull('recurring_payment_status'); // Fixed column name
                }
                return $query;
            });

        // Get all orders and recurring orders
        $orders = $ordersQuery->get();
        $recurringOrders = $recurringOrdersQuery->get();

        // Merge and sort by invoice_id
        $allOrders = collect()
            ->merge($orders)
            ->merge($recurringOrders)
            ->sortByDesc('invoice_id');

        // Manual pagination
        $total = $allOrders->count();
        $entries = new \Illuminate\Pagination\LengthAwarePaginator(
            $allOrders->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('vendor.backpack.crud.order-list', compact('entries', 'crud'));
    }

    public function setupCreateOperation()
    {
        CRUD::setValidation(OrderRequest::class);

        // Customer Details
        CRUD::addField([
            'name'  => 'customer_name',
            'label' => 'Customer Name',
            'type'  => 'text',
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);
        CRUD::addField([
            'name'  => 'email',
            'type'  => 'email',
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);

        CRUD::addField([
            'name'  => 'phone',
            'type'  => 'text',
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);
        CRUD::addField([
            'name'  => 'amount_of_ice',
            'type'  => 'number',
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);

        CRUD::addField([
            'name'  => 'amount_of_boxes',
            'type'  => 'number',
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);
        CRUD::addField([
            'name'  => 'origin',
            'type'  => 'select_from_array',
            'options' => ['online' => 'Online', 'manual' => 'Manual'],
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);

        CRUD::addField([
            'name'  => 'recurring',
            'type'  => 'select_from_array',
            'options' => ['recurring' => 'Recurring', 'non-recurring' => 'Non-recurring'],
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);
        CRUD::addField([
            'name'  => 'location_name',
            'type'  => 'text',
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);

        // Address Fields
        CRUD::addField([
            'name'  => 'address',
            'type'  => 'text',
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);
        CRUD::addField([
            'name'  => 'unit',
            'label' => 'Unit',
            'type'  => 'text',
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);

        CRUD::addField([
            'name'  => 'city',
            'type'  => 'text',
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);
        CRUD::addField([
            'name'  => 'postal_code',
            'type'  => 'text',
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);

        CRUD::addField([
            'name'  => 'province',
            'type'  => 'select_from_array',
            'options' => ['BC' => 'BC', 'AB' => 'AB'],
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);
        CRUD::addField([
            'name'  => 'country',
            'label' => 'Country',
            'type'  => 'text',
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);

        CRUD::addField([
            'name'  => 'pickup_delivery',
            'type'  => 'select_from_array',
            'options' => ['pickup' => 'Pickup', 'delivery' => 'Delivery'],
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);
        CRUD::addField([
            'name'  => 'status',
            'type'  => 'select_from_array',
            'options' => ['valid' => 'Valid', 'skip' => 'Skip', 'cancelled' => 'Cancelled'],
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);

        CRUD::addField([
            'name'  => 'delivery_date',
            'type'  => 'datetime',
            'datetime_picker_options' => [
                'format' => 'YYYY-MM-DD HH:mm'
            ],
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);
        CRUD::addField([
            'name'  => 'total_cost',
            'type'  => 'number',
            'wrapperAttributes' => ['class' => 'form-group col-md-6'],
        ]);

        CRUD::addField([
            'name'  => 'notes',
            'type'  => 'textarea',
            'wrapperAttributes' => ['class' => 'form-group col-md-12'],
        ]);


    }



    // In your OrderCrudController or similar

    public function ajaxCreate(Request $request)
    {
        $rules = [
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'amount_of_ice' => 'nullable|numeric|min:0',
            'amount_of_boxes' => 'nullable|numeric|min:0',
            'recurring' => 'string|in:recurring,non-recurring',
            'origin' => 'nullable|string|max:100',
            'location_name' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'province' => 'required|string|max:50',
            'country' => 'required|string|max:100',
            'delivery_date' => 'nullable',
            'delivery_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:valid,cancelled,skip',
            'pickup_delivery' => 'required|string|in:pickup,delivery',
            'supplier_id' => 'nullable|numeric|min:0',
            'hazmat' => 'nullable|numeric|min:0',
        ];



        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $hasProducts = false;
        foreach ($request->products as $amount) {
            if ($amount > 0) {
                $hasProducts = true;
                break;
            }
        }

        if (!$hasProducts) {
            return response()->json([
                'status' => 'error',
                'message' => 'At least one product with amount greater than 0 is required'
            ], 422);
        }
        try {
            DB::beginTransaction();
            // Handle customer lookup or creation
            $existing_customer = Customer::where('email', $request->email)->first();

            // $request->pickup_delivery === 'delivery' || 'pickup' &&

            if (!$existing_customer) {
                $customer = $this->handleCustomerData($request);
            } else {
                $customer = $existing_customer;
            }

            // Calculate costs server-side
            $subtotal = 0;
            foreach ($request->products as $productId => $amount) {
                if ($amount > 0) {
                    $product = Product::find($productId);
                    if ($product) {
                        $subtotal += $amount * $product->price;
                    }
                }
            }

            $delivery_cost = $validated['delivery_cost'];
            $hazmat_cost = $validated['hazmat'];
            $tax = $subtotal * 0.15;

            // Add calculated fields
            $validated['sub_total'] = $subtotal;
            $validated['tax'] = $tax;
            $validated['total_cost'] = $subtotal + $tax + floatval($delivery_cost) + floatval($hazmat_cost);
            $validated['customer_id'] = $customer->id;
            $validated['origin'] = 'manual';


            // Create the order
            $order = Order::create($validated);
            foreach ($request->products as $productId => $amount) {
                if ($amount > 0) {
                    $product = Product::find($productId);
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'amount_of_items' => $amount,
                        'unit_price' => $product->price,
                        'total_price' => $amount * $product->price,
                    ]);
                }
            }

            $invoiceService = new InvoiceService();
            $originalInvoice = $invoiceService->createInvoiceForOrder($order);

            $order->load('invoice');

            DB::commit();


            Mail::to($order->email)->send(new OrderPlacedMail($order));

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Handle AJAX submission from review form
     */

    public function ajaxCreateFromReview(Request $request)
    {
        // Filter out items with null or zero amounts before validation
        $items = $request->input('items', []);
        $filteredItems = array_filter($items, function($item) {
            $amount = $item['amount_of_items'] ?? null;
            return !is_null($amount) && $amount !== '' && $amount > 0;
        });

        // Re-index the array to avoid gaps
        $filteredItems = array_values($filteredItems);

        // Update the request with filtered items
        $request->merge(['items' => $filteredItems]);

        // Validate the request
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'amount_of_ice' => 'nullable|numeric|min:0',
            'amount_of_boxes' => 'nullable|numeric|min:0',
            'recurring' => 'string|in:recurring,non-recurring',
            'location_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'province' => 'required|string|max:50',
            'country' => 'string|max:100',
            'delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'string|in:valid,cancelled,skip',
            'pickup_delivery' => 'string|in:pickup,delivery',
            'supplier_id' => 'nullable|numeric|min:0',
            'sub_total' => 'nullable|numeric|min:0',
            'delivery_cost' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0',
            'accept' => 'required|accepted',
            'items' => 'required|array|min:1',
            'hazmat' => 'nullable|numeric|min:0',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.amount_of_items' => 'required|numeric|min:1',
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'items.*.total_price' => 'nullable|numeric|min:0',
        ]);



        DB::beginTransaction();

        try {
            // Step 1: Handle customer
//            $customer = $this->handleCustomerData($request);
            $customer = null;


            if ($request->input('pickup_delivery') === 'delivery' || 'pickup') {
                // Check if customer already exists first

                $existingCustomer = Customer::where('email', $request->input('email'))
                    ->first();

                if (!$existingCustomer) {
                    // Step 1: Handle customer - only create new customer
                    $customer = $this->handleCustomerData($request);
                } else {
                    // Use existing customer without updating address
                    $customer = $existingCustomer;
                }
            }
            // Step 2: Create the order
            $order = Order::create([
                'customer_id' => $customer->id,
                'customer_name' => $validated['customer_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'amount_of_ice' => $validated['amount_of_ice'] ?? 0,
                'amount_of_boxes' => $validated['amount_of_boxes'] ?? 0,
                'recurring' => $validated['recurring'] ?? 'non-recurring',
                'location_name' => $validated['location_name'],
                'address' => $validated['address'],
                'unit' => $validated['unit'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
                'province' => $validated['province'],
                'country' => $validated['country'] ?? 'Canada',
                'delivery_date' => Carbon::parse($validated['delivery_date'])->setTimeFrom(Carbon::now())->format('Y-m-d H:i:s'),
                'notes' => $validated['notes'],
                'status' => $validated['status'] ?? 'valid',
                'pickup_delivery' => $validated['pickup_delivery'],
                'supplier_id' => $validated['supplier_id'],
                'sub_total' => $validated['sub_total'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'delivery_cost' => $validated['delivery_cost'],
                'total_cost' => $validated['total_cost'] ?? 0,
                'origin' => 'online',
                'hazmat' => $validated['hazmat'],
            ]);
            // Step 3: Process items
            foreach ($validated['items'] as $item) {
                $amount = $item['amount_of_items'];
                $unitPrice = $item['unit_price'] ?? 0;
                $totalPrice = $item['total_price'] ?? ($amount * $unitPrice);

                $product = Product::find($item['product_id']);

                if ($product && $product->id == 1) {
                    if ($amount < 10) {
                        DB::rollBack(); // rollback before returning
                        return response()->json([
                            'success' => false,
                            'message' => 'Dry Ice Pellets requires a minimum order of 10 lbs.'
                        ]);
                    }
                }

                // Create order item
                $order->items()->create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'amount_of_items' => $amount,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);


            }

            $invoiceService = new InvoiceService();
            $originalInvoice = $invoiceService->createInvoiceForOrder($order);

            $order->load('invoice');

            DB::commit();

            Mail::to($order->email)->send(new OrderPlacedMail($order));


//            $order->payment_status = 0;
//            $order->save();



            return response()->json([
                'success' => true,
                'message' => 'Online Order submitted successfully',
                'order' => $order->load('items'),
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();


            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 422);
        }
    }


    public function paymentRedirect($orderId)
    {
        try {
            $order = Order::with('invoice')->findOrFail($orderId);

            $paymentParams = $this->generatePaymentParams($order);

            return view('website.order.payment-redirect', compact('paymentParams'));

        } catch (\Exception $e) {
            \Log::error('Payment redirect failed: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Order not found or payment setup failed.');
        }
    }

    protected function generatePaymentParams($order)
    {
        $x_login = env('EXACT_LOGIN_ID');
        $transaction_key = env('EXACT_TRANSACTION_KEY');
        $x_amount = number_format($order->total_cost, 2, '.', '');
        $x_invoice_num = $order->invoice->invoice_number;
        $x_description = 'Order #' . $order->id;
        $x_email = $order->email;
        $x_first_name = $order->customer_name;
        $x_return_url = route('payments.callback');
        $x_fp_sequence = rand(1000, 100000) + 123456;


        $x_fp_timestamp = time();

        $hmac_data = $x_login . "^" . $x_fp_sequence . "^" . $x_fp_timestamp . "^" . $x_amount . "^" . 'CAD';
        $x_fp_hash = hash_hmac('MD5', $hmac_data, $transaction_key);

        return [
            'x_login' => $x_login,
            'x_amount' => $x_amount,
            'x_fp_timestamp' => $x_fp_timestamp,
            'x_fp_sequence' => $x_fp_sequence,
            'x_invoice_num' => $x_invoice_num,
            'x_description' => $x_description,
            'x_first_name' => $x_first_name,
            'x_email' => $x_email,
//            'x_receipt_link_text' => 'Payment Page',
            'x_receipt_link_url' => $x_return_url,
            'x_receipt_link_method' => 'AUTO-GET',
            'x_currency_code' => 'CAD',
            'x_fp_hash' => $x_fp_hash,
            'x_show_form' => 'PAYMENT_FORM',
            'x_test_request' => 'False',
            'payment_url' => 'https://rpm.demo.e-xact.com/payment'
        ];
    }
    /* Handle customer data for review form */

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }



    protected function handleCustomerData($data)
    {
        $customer = Customer::updateOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['customer_name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city' => $data['city'],
                'postal_code' => $data['postal_code'],
                'province' => $data['province'],
                'country' => $data['country']
            ]
        );
        return $customer;
    }

    protected function saveOrder($data)
    {
        $customer = $this->handleCustomerData($data);
        $data['customer_id'] = $customer->id;

        return Order::create($data);
    }

    protected function updateOrderData($data, $id)
    {
        $customer = $this->handleCustomerData($data);
        $data['customer_id'] = $customer->id;

        $order = Order::findOrFail($id);
        $order->update($data);
        return $order;
    }

    // Override store method to save order and customer
    public function store()
    {
        $request = $this->crud->validateRequest();
        $data = $request->all();

        $order = $this->saveOrder($data);

        // Send confirmation email
        try {
            Mail::to($order->email)->send(new OrderPlacedMail($order));
        } catch (\Exception $e) {
            \Log::error('Failed to send order email: ' . $e->getMessage());
        }

        return $this->crud->performSaveAction();
    }


    /**
     * Show order details (AJAX endpoint)
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderDetails(Request $request)
    {
        try {
            $invoiceId = $request->get('invoice_id');

            $invoice = Invoice::find($invoiceId);

            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found'
                ]);
            }

            $customerInfo = $this->getCustomerInfo($invoice);

            if (!$customerInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer information not found'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'amount' => number_format($invoice->total_amount, 2),
                    'customer_name' => $customerInfo->customer_name,
                    'customer_email' => $customerInfo->email,
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('Error fetching invoice payment details: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Server error occurred'
            ]);
        }
    }

    private function getCustomerInfo($invoice)
    {
        $customerInfo = null;

        switch($invoice->invoiceable_type) {
            case 'App\\Models\\Order':
            case 'Model\\Order':
                $order = \App\Models\Order::where('invoice_id', $invoice->id)->first();
                if ($order) {
                    $customerInfo = $order;
                }
                break;

            case 'App\\Models\\RecurringOrder':
            case 'Model\\RecurringOrder':
                $recurringOrder = \App\Models\RecurringOrder::find($invoice->invoiceable_id);
                if ($recurringOrder) {
                    $order = \App\Models\Order::find($recurringOrder->order_id);
                    if ($order) {
                        $customerInfo = $order;
                    }
                }
                break;

            default:
                // If no specific type, try to find order by invoice_id as fallback
                $order = \App\Models\Order::where('invoice_id', $invoice->id)->first();
                if ($order) {
                    $customerInfo = $order;
                }
                break;
        }

        return $customerInfo;
    }




    public function initiatePayment(Request $request)
    {
        try {
            $invoiceId = $request->get('invoice_id');

            $invoice = Invoice::find($invoiceId);

            if (!$invoice) {
                return redirect()->back()->with('error', 'Invoice not found');
            }

            $customerInfo = $this->getCustomerInfo($invoice);

            if (!$customerInfo) {
                return redirect()->back()->with('error', 'Customer information not found');
            }

            $paymentParams = $this->buildExactPaymentParams($invoice, $customerInfo);

            // Create HTML form and auto-submit directly to Exact
            $form = '<html><body>';
            $form .= '<form id="exactForm" method="POST" action="' . $paymentParams['payment_url'] . '">';

            foreach ($paymentParams as $key => $value) {
                if ($key !== 'payment_url') {
                    $form .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
                }
            }

            $form .= '</form>';
            $form .= '<script>document.getElementById("exactForm").submit();</script>';
            $form .= '</body></html>';

            return response($form);

        } catch (Exception $e) {
            \Log::error('Error processing payment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Server error occurred while processing payment');
        }
    }

    protected function buildExactPaymentParams($invoice, $customerInfo)
    {
        $x_login = env('EXACT_LOGIN_ID');
        $transaction_key = env('EXACT_TRANSACTION_KEY');
        $x_amount = number_format($invoice->total_amount, 2, '.', '');
        $x_invoice_num = $invoice->invoice_number;
        $x_description = 'Invoice #' . $invoice->invoice_number;
        $x_email = $customerInfo->email;
        $x_first_name = $customerInfo->customer_name;
        $x_return_url = route('payments.callback'); // You'll need to create this route

//        $x_relay_response = 'TRUE';
//        $x_receipt_link_method = 'REDIRECT';
//        $x_receipt_link_url = url(route('payments.callback'));

        $x_fp_sequence = rand(1000, 100000) + 123456;
        $x_fp_timestamp = time();

        $hmac_data = $x_login . "^" . $x_fp_sequence . "^" . $x_fp_timestamp . "^" . $x_amount . "^" . 'CAD';
        $x_fp_hash = hash_hmac('MD5', $hmac_data, $transaction_key);

        return [
            'x_login' => $x_login,
            'x_amount' => $x_amount,
            'x_fp_timestamp' => $x_fp_timestamp,
            'x_fp_sequence' => $x_fp_sequence,
            'x_invoice_num' => $x_invoice_num,
            'x_description' => $x_description,
            'x_first_name' => $x_first_name,
            'x_email' => $x_email,
//            'x_receipt_link_text' => 'Payment Page',
            'x_receipt_link_url' => $x_return_url,
            'x_receipt_link_method' => 'AUTO-GET',
            'x_currency_code' => 'CAD',
            'x_fp_hash' => $x_fp_hash,
            'x_show_form' => 'PAYMENT_FORM',
            'x_test_request' => 'False',
//            'x_relay_response' => $x_relay_response,
//            'x_receipt_link_method' => $x_receipt_link_method,
//            'x_receipt_link_url' => $x_receipt_link_url,
            'payment_url' => 'https://rpm.demo.e-xact.com/payment'
        ];
    }


    public function handlePaymentCallback(Request $request) {
        $responseCode = $request->get('x_response_code');
        $invoiceNumber = $request->get('x_invoice_num');

        $invoice = Invoice::where('invoice_number', $invoiceNumber)
            ->with('invoiceable')
            ->where('payment_status', Invoice::PENDING)
            ->first();

        if (!$invoice) {
            return view('website.payment-result', [
                'success' => false,
                'message' => 'Invoice not found or already processed.',
                'invoice_number' => $invoiceNumber ?? null,
            ]);
        }

        if ($responseCode == '1') {
            // success
            $invoice->update([
                'payment_status' => Invoice::PAID,
                'transaction_json' => json_encode($request->all()),
                'paid_at' => now()
            ]);

            $invoiceable = $invoice->invoiceable;

            if ($invoiceable instanceof Order) {
                $invoiceable->update(['payment_status' => 'paid']);
                Mail::to($invoiceable->email)->send(new OrderPlacedMail($invoiceable));
            } elseif ($invoiceable instanceof RecurringOrder) {
                $invoiceable->update(['recurring_payment_status' => 1]);
                $invoiceable->load('order');
                if ($invoiceable->order) {
                    Mail::to($invoiceable->order->email)->send(new OrderPlacedMail($invoiceable, 'recurring'));
                }
            }

            return view('website.order.payment-result', [
                'success' => true,
                'message' => 'Payment completed successfully!',
                'invoice_number' => $invoice->invoice_number,
                'amount' => $request->get('x_amount') ?? null,
                'transaction_id' => $request->get('x_trans_id') ?? null,
            ]);
        } else {
            // failed
            $invoice->update([
                'payment_status' => Invoice::FAILED,
                'transaction_json' => json_encode($request->all()),
                'paid_at' => now()
            ]);

            return view('website.order.payment-result', [
                'success' => false,
                'message' => 'Payment failed. Please try again.',
                'invoice_number' => $invoice->invoice_number,
            ]);
        }
    }


    /**
     * Update order via AJAX
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrderAjax(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            // Validate the request data
            $rules = [
                'customer_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'products' => 'required|array',
                'products.*' => 'numeric|min:0',
                'recurring' => 'string|in:recurring,non-recurring',
                'origin' => 'nullable|string|max:100',
                'location_name' => 'nullable|string|max:255',
                'address' => 'required|string|max:255',
                'unit' => 'nullable|string|max:50',
                'city' => 'required|string|max:100',
                'postal_code' => 'required|string|max:20',
                'province' => 'required|string|max:50',
                'country' => 'required|string|max:100',
                'delivery_date' => 'nullable',
                'delivery_cost' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
                'status' => 'required|string|in:valid,cancelled,skip',
                'pickup_delivery' => 'required|string|in:pickup,delivery',
                'supplier_id' => 'nullable|numeric|min:0',
                'hazmat' => 'nullable|numeric|min:0',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validatedData = $validator->validated();

            // Validate at least one product has amount > 0
            $hasProducts = false;
            foreach ($request->products as $amount) {
                if ($amount > 0) {
                    $hasProducts = true;
                    break;
                }
            }

            if (!$hasProducts) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'At least one product with amount greater than 0 is required'
                ], 422);
            }

            DB::beginTransaction();

            // Calculate costs server-side using actual product prices from DB
            $subtotal = 0;
            foreach ($request->products as $productId => $amount) {
                if ($amount > 0) {
                    $product = Product::find($productId);
                    if ($product) {
                        $subtotal += $amount * $product->price;
                    }
                }
            }

            $delivery_cost = $validatedData['delivery_cost'];
            $hazmat_cost = $validatedData['hazmat'];
            $tax = $subtotal * 0.15;

            // Add calculated fields
            $validatedData['sub_total'] = $subtotal;
            $validatedData['tax'] = $tax;
            $validatedData['total_cost'] = $subtotal + $tax + floatval($delivery_cost) + floatval($hazmat_cost);

            // Keep original origin value
            $validatedData['origin'] = $order->origin;

            // Remove products from validated array as it's not an Order field
            unset($validatedData['products']);

            // Update order items
            foreach ($request->products as $productId => $amount) {
                $orderItem = OrderItem::where('product_id', $productId)->where('order_id', $order->id)->first();

                if ($amount > 0) {
                    $product = Product::find($productId);
                    if ($product) {
                        if ($orderItem) {
                            // Update existing order item
                            $orderItem->update([
                                'amount_of_items' => $amount,
                                'unit_price' => $product->price,
                                'total_price' => $amount * $product->price,
                            ]);
                        } else {
                            // Create new order item
                            OrderItem::create([
                                'order_id' => $order->id,
                                'product_id' => $productId,
                                'amount_of_items' => $amount,
                                'unit_price' => $product->price,
                                'total_price' => $amount * $product->price,
                            ]);
                        }
                    }
                } else {
                    // Remove order item if amount is 0
                    if ($orderItem) {
                        $orderItem->delete();
                    }
                }
            }

            // Update the order
            $order->update($validatedData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'order' => $order->fresh()
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating order: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Delete order via AJAX
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteOrderAjax($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting order: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getCustomerByEmail(Request $request)
    {
        $email = $request->get('email');

        if (!$email) {
            return response()->json(['error' => 'Email is required'], 400);
        }

        $customer = Customer::select([
            'name',
            'email',
            'phone',
            'address',
            'city',
            'postal_code',
            'province'
        ])->where('email', $email)->first();

        if ($customer) {
            return response()->json([
                'success' => true,
                'customer' => $customer
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Customer not found'
        ], 404);
    }

    /**
     * AJAX endpoint for customer search
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxCustomers(Request $request)
    {
        $search = $request->input('q');

        $results = Customer::query()
            ->where('id', 'like', "%{$search}%")
            ->orWhere('name', 'like', "%{$search}%")
            ->limit(20)
            ->get(['id', 'name']);

        // Return format Select2 expects: [{id: 1, text: 'Customer Name'}]
        $formatted = $results->map(function ($customer) {
            return ['id' => $customer->id, 'text' => $customer->name . ' (ID: ' . $customer->id . ')'];
        });

        return response()->json($formatted);
    }


    public function editModal($id, Request $request)
    {
        $recurring = intval($request->query('recurring'));
        if($recurring){
            $recurring_id = intval($request->query('recurring_id'));
            $order = Order::where('recurring', Order::RECURRING)
                ->where('id', $id)
                ->whereHas('recurringOrders', function ($query) {
                    $query->where('status', 'open')
                        ->where('scheduled_delivery_date', '>', now());
                })
                ->with(['recurringOrders' => function ($query) use ($recurring_id) {
                    $query->where('id',$recurring_id);
                }])
                ->with(['items','items.product'])
                ->get()
                ->first();
            $status = null;
            if ($order->recurringOrders?->first()->novex_order_id) {
                $cacheKey = 'order_status_' . $order->recurringOrders?->first()->novex_order_id;
                $status = Cache::remember($cacheKey, now()->addHours(3), function () use ($order) {
                    return $this->getOrderStatus($order->recurringOrders?->first()->novex_order_id);
                });
            }
            $data = [
                'order' => $order,
                'recurring' => $recurring,
                'status' => $status,
            ];
            return view('website.customer.partials.order_details', $data);
        }

        $order = Order::whereId($id)->with(['items','items.product'])->first();
        // Get only products that are in this order
        $orderProducts = $order->items;
        $data = [
            'mode' => 'edit',
            'order' => $order,
            'modalTitle' => 'Edit Order',
            'saveButtonText' => 'Update Order',
            'showOrderId' => true,
            'showDeleteButton' => true,
            'showPushButton' => true,
            'products' => $orderProducts,
            'defaultValues' => [
                'order_id' => $order->id,
                'invoice_id' => $order->invoice_id,
                'customer_email' => $order->email,
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->phone,
                'ice_amount' => $order->amount_of_ice,
                'box_amount' => $order->amount_of_boxes ?? 0,
                'recurring' => $order->recurring,
                'location_name' => $order->location_name,
                'address' => $order->address,
                'unit' => $order->unit,
                'city' => $order->city,
                'postal_code' => $order->postal_code,
                'province' => $order->province,
                'country' => $order->country ?? 'Canada',
                'delivery_date' => $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') : '',
                'delivery_time' => $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('H:i') : '',
                'notes' => $order->notes,
                'status' => $order->status ?? 'valid',
                'pickup_delivery' => $order->pickup_delivery,
                'sub_total' => $order->sub_total ?? 0,
                'delivery_cost' => $order->delivery_cost ?? 0,
                'tax' => $order->tax ?? 0,
                'total_cost' => $order->total_cost ?? 0,
                'origin' => $order->origin,
                'novex_pushed' => $order->push ?? 0,
                'hazmat' => $order->hazmat ?? 0
            ]
        ];
        return view('vendor.backpack.crud.inc.order_modal', $data);
    }

    public function modalCreate()
    {
        $products = Product::all();

        $defaultValues = [
            'invoice_id' => '',
            'customer_email' => '',
            'customer_name' => '',
            'customer_phone' => '',
            'ice_amount' => 0,
            'box_amount' => 0,
            'recurring' => '',
            'location_name' => '',
            'origin' => 'manual',
            'address' => '',
            'unit' => '',
            'city' => '',
            'postal_code' => '',
            'province' => '',
            'country' => 'Canada',
            'pickup_delivery' => '',
            'status' => 'valid',
            'delivery_date' => '',
            'delivery_time' => '',
            'notes' => '',
            'delivery_cost' => 0,
            'sub_total' => 0,
            'tax' => 0,
            'total_cost' => 0,
            'hazmat' => 12,
            'novex_pushed' => false
        ];

        return view('vendor.backpack.crud.inc.order_modal', [
            'mode' => 'create',
            'modalTitle' => 'Create New Order',
            'saveButtonText' => 'Create Order',
            'showOrderId' => false,
            'showDeleteButton' => false,
            'showPushButton' => false,
            'defaultValues' => $defaultValues,
            'products' => $products,
            'order' => null
        ]);
    }

    function getOrderStatus($orderNumber)
    {
        try {
            $response = Http::withOptions([
                'verify' => config('services.http_verify'),
            ])->withHeaders([
                'Authorization' => 'Basic ' . config('services.novex.auth_key'),
                'Content-Type' => 'application/json'
            ])->get(config('services.novex.push_url') . "/{$orderNumber}");

            if ($response->successful()) {
                $data = $response->json();
                return $data['status'];
            }

            return null;

        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return null;
        }
    }


    public function getOrdersByLocationAndDate(Request $request)
    {
        try {
            $request->validate([
                'location' => 'required|string',
                'delivery_date' => 'required|date'
            ]);

            $location = $request->input('location');
            $deliveryDate = $request->input('delivery_date');

            // Fetch orders from database where delivery_location and delivery_date match
            $orders = Order::whereRaw('DATE(delivery_date) = ?', [$deliveryDate])
//                ->where('delivery_location', $location)
                ->orderBy('created_at', 'asc')
                ->get([
                    'id',
                    'customer_name',
                    'amount_of_ice',
                    'amount_of_boxes',
                    'email',
                    'phone',
//                    'delivery_location',
                    'delivery_date',
                    'created_at'
                ]);

            return response()->json($orders, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch orders',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function sendDryIceOrderEmail(Request $request)
    {
        try {
            $request->validate([
                'location' => 'required|string',
                'to' => 'required|email',
                'subject' => 'required|string',
                'body' => 'required|string',
                'delivery_date' => 'required|date'
            ]);

            $location = $request->input('location');
            $recipientEmail = $request->input('to');
            $subject = $request->input('subject');
            $body = $request->input('body');
            $deliveryDate = $request->input('delivery_date');

            $orders = Order::whereRaw('DATE(delivery_date) = ?', [$deliveryDate])
//                ->where('delivery_location', $location)
                ->orderBy('created_at', 'asc')
                ->get();


            Mail::to($recipientEmail)->send(new DryIceOrderEmail([
                'location' => $location,
                'delivery_date' => $deliveryDate,
                'subject' => $subject,
                'body' => $body,
                'orders' => $orders,
                'recipient_email' => $recipientEmail
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully',
                'data' => [
                    'location' => $location,
                    'recipient' => $recipientEmail,
                    'delivery_date' => $deliveryDate,
                    'orders_count' => $orders->count()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to send email',
                'message' => $e->getMessage()
            ], 500);
        }
    }




}
