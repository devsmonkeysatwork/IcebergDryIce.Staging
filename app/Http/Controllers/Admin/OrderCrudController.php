<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Customer;
use Carbon\Carbon;

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
    //    public function index()
//    {
//        $this->setupListOperation();
//
//    }




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


    protected function setupUpdateOperation()
  {
    $this->setupCreateOperation();
  }

  protected function addCustomFilters()
  {
    // $all = request()->query('all');
    $today = request()->query('today');
    $future = request()->query('future');
    $past = request()->query('past');

    if ($today) {
      CRUD::addClause('whereDate', 'delivery_date', '=', Carbon::today()->toDateString());
    } elseif ($future) {
      CRUD::addClause('whereDate', 'delivery_date', '>', Carbon::today()->toDateString());
    } elseif ($past) {
      CRUD::addClause('whereDate', 'delivery_date', '<', Carbon::today()->toDateString());
    }
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

  protected function updateOrder($data, $id)
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

    $this->saveOrder($data);

    return $this->crud->performSaveAction();
  }

  // Override update method to update order and customer
  public function update($id)
  {
    $request = $this->crud->validateRequest();
    $data = $request->all();

    $this->updateOrder($data, $id);

    return $this->crud->performUpdateAction($id);
  }

    public function ajaxCustomers(Request $request)
    {
        $search = $request->input('q');

        $results = Customer::query()
            ->where('id', 'like', "%{$search}%")
            ->orWhere('name', 'like', "%{$search}%") // Adjust to match your DB column
            ->limit(20)
            ->get(['id', 'name']);

        // Return format Select2 expects: [{id: 1, text: 'Customer Name'}]
        $formatted = $results->map(function ($customer) {
            return ['id' => $customer->id, 'text' => $customer->name . ' (ID: ' . $customer->id . ')'];
        });

        return response()->json($formatted);
    }

}
