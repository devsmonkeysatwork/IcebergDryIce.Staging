<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Requests\CustomerRequest;

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
    CRUD::setListView('vendor.backpack.crud.customer-list');

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

  protected function setupUpdateOperation()
  {
    $this->setupCreateOperation();
  }
}
