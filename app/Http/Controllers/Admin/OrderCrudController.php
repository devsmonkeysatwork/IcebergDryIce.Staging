<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
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

//    $orders = Order::select('id', 'customer_name')->get();

//    CRUD::addButtonFromView('top', 'custom_filter', 'buttons.custom_filter');
//
//      // Apply filter logic
//      if (request()->filled('status')) {
//          CRUD::addClause('where', 'status', request('status'));
//      }
//      if (request()->filled('type')) {
//          CRUD::addClause('where', 'recurring', request('type'));
//      }
//
//      if (request()->filled('order_id')) {
//          CRUD::addClause('where', 'id', request('order_id'));
//      }

//      if (request()->filled('transfer_status')) {
//          CRUD::addClause('where', 'transfer_status', request('transfer_status'));
//      }
//      if (request()->filled('customer_id')) {
//          CRUD::addClause('where', 'customer_id', request('customer_id'));
//      }



      // Dropdown filter for Order Type
//     CRUD::addFilter([
//         'name'  => 'type',
//         'type'  => 'dropdown',
//         'label' => 'Type',
//                            ], [
//                                'standard' => 'Standard',
//                                'express' => 'Express',
//                            ], function ($value) {
//                                CRUD::addClause('where', 'type', $value);
//                            });
//
//    // Dropdown filter for Order Status
//    CRUD::addFilter([
//        'name'  => 'status',
//        'type'  => 'dropdown',
//        'label' => 'Status',
//    ], [
//        'pending'   => 'Pending',
//        'shipped'   => 'Shipped',
//        'delivered' => 'Delivered',
//    ], function ($value) {
//        CRUD::addClause('where', 'status', $value);
//    });
//
//    // Dropdown filter for Transfer Status
//    CRUD::addFilter([
//        'name'  => 'transfer_status',
//        'type'  => 'dropdown',
//        'label' => 'Transfer Status',
//    ], [
//        'transferred' => 'Transferred',
//        'not_transferred' => 'Not Transferred',
//    ], function ($value) {
//        CRUD::addClause('where', 'transfer_status', $value);
//    });

//    $this->addCustomFilters();
//
//    // Add buttons to filter orders
//    CRUD::addButtonFromView('top', 'all_orders', 'orders_all');
//    CRUD::addButtonFromView('top', 'todays_orders', 'orders_today');
//    CRUD::addButtonFromView('top', 'future_orders', 'orders_future');
//    CRUD::addButtonFromView('top', 'past_orders', 'orders_past');
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
}
