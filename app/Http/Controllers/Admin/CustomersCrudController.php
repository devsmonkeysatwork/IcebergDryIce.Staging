<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Requests\CustomerRequest;
use Illuminate\Http\Request;

/**
 * Class CustomersCrudController
 * @package App\Http\Controllers\Admin
 */
class CustomersCrudController extends CrudController
{
  use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

  public function setup()
  {
    CRUD::setModel(\App\Models\Customer::class);
    CRUD::setRoute(config('backpack.base.route_prefix') . '/customers');
    CRUD::setEntityNameStrings('customer', 'customers');
    CRUD::setCreateView('vendor.backpack.crud.customer-create');

//    CRUD::setListView('vendor.backpack.crud.customer-list');

  }

  protected function setupListOperation()
  {
    CRUD::column('name');
    CRUD::column('email');
    CRUD::column('phone');
    CRUD::column('address');
    CRUD::column('city');
    CRUD::column('postal_code');
    CRUD::column('province');

    $this->crud->removeAllButtonsFromStack('line');
    $this->crud->addButtonFromView('line', 'view_button', 'view-button', 'beginning');
  }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'province' => 'required|string',
        ];
    }
  protected function setupCreateOperation()
  {
    CRUD::setValidation(CustomerRequest::class);

    CRUD::field('name');
    CRUD::field('email');
    CRUD::field('phone');
    CRUD::field('address');
    CRUD::field('city');
    CRUD::field('postal_code');
    CRUD::field('province')->type('enum')->options(['BC' => 'BC', 'AB' => 'AB']);
  }

    public function view($id)
    {
        $this->crud->hasAccessOrFail('update'); // or 'view'

        $entry = \App\Models\Customer::findOrFail($id);

        return view('vendor.backpack.crud.customer-edit', [
            'entry' => $entry,
            'crud' => $this->crud,
        ]);
    }

    public function updateFromView($id)
    {
        $this->crud->hasAccessOrFail('update');
        $data = request()->all();

        $entry = \App\Models\Customer::findOrFail($id);
        $entry->update($data);

        \Alert::success('Customer updated successfully.')->flash();
        return redirect()->route('customer.view', $id);
    }

    public function deleteFromView($id)
    {
        $this->crud->hasAccessOrFail('delete');

        \App\Models\Customer::findOrFail($id)->delete();

        \Alert::success('Customer Deleted.')->flash();

        return redirect($this->crud->route);
    }


    public function searchCustomers(Request $request)
    {
        $query = $request->get('q', '');
        $page = $request->get('page', 1);
        $perPage = 20;

        $customersQuery = Customer::select([
            'name',
            'email',
            'phone',
            'address',
            'city',
            'postal_code',
            'province'
        ]);

        if (!empty($query)) {
            $customersQuery->where(function($q) use ($query) {
                $q->where('email', 'LIKE', "%{$query}%");
            });
        }

        $customers = $customersQuery
            ->orderBy('email')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'customers' => $customers->items(),
            'has_more' => $customers->hasMorePages(),
            'total' => $customers->total()
        ]);
    }

  protected function setupUpdateOperation()
  {
    $this->setupCreateOperation();
  }
}
