<?php

namespace App\Http\Controllers\Admin;

use App\Mail\CustomerRegisteredMail;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Carbon\Carbon;
use App\Mail\OrderPlacedMail;
use Illuminate\Support\Facades\Mail;
use App\Services\ClosestSupplierService;


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

        // Get orders with pagination
        $entries = Order::query()
            ->when(request('status'), function($query, $status) {
                return $query->where('status', $status);
            })
            ->when(request('transfer_status'), function($query, $transferStatus) {
                return $query->where('transfer_status', $transferStatus);
            })
            ->when(request('recurring'), function($query, $recurring) {
                return $query->where('recurring', $recurring);
            })
            ->when(request('customer_id'), function($query, $customerId) {
                return $query->where('customer_id', $customerId);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        // Make sure entries are available to the view
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
        // Validation
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
            'postal_code' => 'string|max:20',
            'province' => 'string|max:50',
            'country' => 'string|max:100',
            'delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:valid,cancelled,skip',
            'pickup_delivery' => 'required|string|in:pickup,delivery'
        ]);


        try {
            // Handle customer lookup or creation
            $customer = $this->handleCustomerData($request);
            // Calculate cost (adjust pricing logic as needed)
            $iceCost = ($validated['amount_of_ice'] ?? 0) * 5;      // Example: $5 per unit of ice
            $boxCost = ($validated['amount_of_boxes'] ?? 0) * 2.5;  // Example: $2.50 per box
            $totalCost = $iceCost + $boxCost;


            // Merge calculated and derived fields into validated data
            $validated['customer_id'] = $customer->id;
            $validated['total_cost'] = $totalCost;


            $validated['origin'] = 'manual'; // ✅ set origin to manual


            // Create the order with customer_id
            $order = Order::create($validated);

            Mail::to($order->email)->send(new OrderPlacedMail($order));


            return response()->json(['success' => true, 'order' => $order]);
        } catch (\Exception $e) {
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

        // Step 1: Validate the order data
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
            'subtotal' => 'nullable|numeric|min:0',
            'delivery_cost' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0',
            'accept' => 'required|accepted',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.amount_of_items' => 'required|numeric|min:1', // Changed from min:0 to min:1
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'items.*.total_price' => 'nullable|numeric|min:0',
        ]);

        try {
            // Step 2: Handle customer
            $customer = $this->handleCustomerData($request);

            // Step 3: Create the order
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
                'delivery_date' => $validated['delivery_date'],
                'notes' => $validated['notes'],
                'status' => $validated['status'] ?? 'valid',
                'pickup_delivery' => $validated['pickup_delivery'] ?? 'delivery',
                'sub_total' => $validated['subtotal'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'delivery_cost' => $validated['delivery_cost'] ?? 5.00,
                'total_cost' => $validated['total_cost'] ?? 0,
                'origin' => 'online',
            ]);

            // Step 4: Create OrderItems - Ensure all required fields are present
            foreach ($validated['items'] as $item) {
                $amount = $item['amount_of_items'];
                $unitPrice = $item['unit_price'] ?? 0;
                $totalPrice = $item['total_price'] ?? ($amount * $unitPrice);

                $product = Product::find($item['product_id']);

                if ($product && stripos($product->product_name, 'dry ice') !== false) {
                    if ($item['amount_of_items'] < 10) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Dry Ice require a minimum order of 10 lbs.'
                        ]);
                    }
                }

                // Explicitly create with all required fields
                $orderItem = $order->items()->create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'amount_of_items' => $amount, // Ensure this is explicitly set
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);

                // Log for debugging
                \Log::info('Order item created: ', [
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'amount_of_items' => $amount,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'created_item' => $orderItem->toArray()
                ]);
            }

            // Step 5: Send confirmation email
            Mail::to($order->email)->send(new OrderPlacedMail($order));

            return response()->json([
                'success' => true,
                'message' => 'Online Order submitted successfully',
                'order' => $order->load('items'),
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Online order creation failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ', $request->all());
            \Log::error('Validated data: ', $validated ?? 'Validation failed');

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 422);
        }
    }
    /* Handle customer data for review form */

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

//    protected function addCustomFilters()
//    {
//        $today = request()->query('today');
//        $future = request()->query('future');
//        $past = request()->query('past');
//
//        if ($today) {
//            CRUD::addClause('whereDate', 'delivery_date', '=', Carbon::today()->toDateString());
//        } elseif ($future) {
//            CRUD::addClause('whereDate', 'delivery_date', '>', Carbon::today()->toDateString());
//        } elseif ($past) {
//            CRUD::addClause('whereDate', 'delivery_date', '<', Carbon::today()->toDateString());
//        }
//    }

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


    // Override update method to update order and customer
    public function update($id)
    {
        $request = $this->crud->validateRequest();
        $data = $request->all();

        $this->updateOrderData($data, $id);

        return $this->crud->performUpdateAction($id);
    }

    /**
     * Show order details (AJAX endpoint)
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderDetails($id)
    {
        try {
            $order = Order::findOrFail($id);
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Order not found'], 404);
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
            $validatedData = $request->validate([
                'customer_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'amount_of_ice' => 'nullable|numeric|min:0',
                'amount_of_boxes' => 'nullable|numeric|min:0',
                'recurring' => 'nullable|string|in:recurring,non-recurring',
                'location_name' => 'nullable|string|max:255',
                'address' => 'string|max:255',
                'unit' =>  'nullable|string|max:50',
                'city' => 'string|max:100',
                'postal_code' => 'string|max:20',
                'province' => 'nullable|string|max:50',
                'country' => 'nullable|string|max:100',
                'delivery_date' => 'nullable|date',
                'notes' => 'nullable|string',
                'status' => 'required|string|in:valid,cancelled,skip',
                'pickup_delivery' => 'required|string|in:pickup,delivery'
            ]);

            // Handle customer creation/update
            if (isset($validatedData['email']) && isset($validatedData['customer_name'])) {
                $customer = Customer::updateOrCreate(
                    ['email' => $validatedData['email']],
                    [
                        'name' => $validatedData['customer_name'],
                        'phone' => $validatedData['phone'] ?? null,
                        'address' => $validatedData['address'] ?? null,
                        'city' => $validatedData['city'] ?? null,
                        'postal_code' => $validatedData['postal_code'] ?? null,
                        'province' => $validatedData['province'] ?? null,
                        'country' => $validatedData['country'] ?? null
                    ]
                );
                $validatedData['customer_id'] = $customer->id;
            }

            // Calculate total cost
            $iceCost = ($validatedData['amount_of_ice'] ?? 0) * 1.95;
            $boxCost = ($validatedData['amount_of_boxes'] ?? 0) * 30.00;
            $deliveryFee = ($validatedData['pickup_delivery'] === 'delivery') ? 20.00 : 0.00;
            $subtotal = $iceCost + $boxCost + $deliveryFee;
            $tax = $subtotal * 0.15;
            $validatedData['total_cost'] = $subtotal + $tax;

            // Fix field name mapping
            if (isset($validatedData['postal'])) {
                $validatedData['postal_code'] = $validatedData['postal'];
                unset($validatedData['postal']);
            }

            // Update the order
            $order->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'order' => $order->fresh()
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
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


}
