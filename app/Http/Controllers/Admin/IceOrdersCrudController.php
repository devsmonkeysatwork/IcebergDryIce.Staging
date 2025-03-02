<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\IceOrderRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Events\IceOrderPlaced;

/**
 * Class IceOrdersCrudController
 * @package App\Http\Controllers\Admin
 */
class IceOrdersCrudController extends CrudController
{
  use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

  public function setup()
  {
    CRUD::setModel(\App\Models\IceOrder::class);
    CRUD::setRoute(config('backpack.base.route_prefix') . '/ice-orders');
    CRUD::setEntityNameStrings('ice order', 'ice orders');
    CRUD::setCreateView('vendor.backpack.crud.ice-order-create');
  }

  protected function setupListOperation()
  {
    CRUD::column('date');
    CRUD::column('supplier_name');
    CRUD::column('ice_cost');
    CRUD::column('ice_invoice');
    CRUD::column('border_cost');
    CRUD::column('border_invoice');
    CRUD::column('shipper_name');
    CRUD::column('shipper_cost');
    CRUD::column('probill');
    CRUD::column('other_description');
    CRUD::column('other_cost');
    CRUD::column('weight');
    CRUD::column('totes');
  }

  protected function setupCreateOperation(){

      CRUD::setValidation(IceOrderRequest::class);

      // Date (Dropdown)
      CRUD::addField([
          'name'  => 'date',
          'type'  => 'date',
          'label' => 'Date',
          'wrapperAttributes' => ['class' => 'form-group col-md-2'],
      ]);

      // Supplier Details
      CRUD::addField([
          'name'  => 'supplier_name',
          'type'  => 'text',
          'label' => 'Supplier',
          'attributes' => ['placeholder' => 'Dry Ice Supplier Name'],
          'wrapperAttributes' => ['class' => 'form-group col-md-4'],
      ]);
      CRUD::addField([
          'name'  => 'ice_cost',
          'type'  => 'number',
          'label' => 'Ice Cost',
          'attributes' => ["step" => "0.01", 'placeholder' => 'Cost $'],
          'wrapperAttributes' => ['class' => 'form-group col-md-2'],
      ]);
      CRUD::addField([
          'name'  => 'ice_invoice',
          'type'  => 'text',
          'label' => 'Ice Invoice #',
          'attributes' => ['placeholder' => 'Invoice #'],
          'wrapperAttributes' => ['class' => 'form-group col-md-2'],
      ]);

      // Carrier Details
      CRUD::addField([
          'name'  => 'shipper_name',
          'type'  => 'text',
          'label' => 'Carrier',
          'attributes' => ['placeholder' => 'Carrier Name'],
          'wrapperAttributes' => ['class' => 'form-group col-md-4'],
      ]);
      CRUD::addField([
          'name'  => 'shipper_cost',
          'type'  => 'number',
          'label' => 'Carrier Cost',
          'attributes' => ["step" => "0.01", 'placeholder' => 'Cost $'],
          'wrapperAttributes' => ['class' => 'form-group col-md-2'],
      ]);
      CRUD::addField([
          'name'  => 'probill',
          'type'  => 'text',
          'label' => 'Probill #',
          'attributes' => ['placeholder' => 'Probill #'],
          'wrapperAttributes' => ['class' => 'form-group col-md-2'],
      ]);

      // Amount & Totes
      CRUD::addField([
          'name'  => 'weight',
          'type'  => 'number',
          'label' => 'Amount',
          'attributes' => ['placeholder' => 'Weight in lbs'],
          'wrapperAttributes' => ['class' => 'form-group col-md-3'],
      ]);
      CRUD::addField([
          'name'  => 'totes',
          'type'  => 'number',
          'label' => 'Totes',
          'attributes' => ['placeholder' => '# of totes'],
          'wrapperAttributes' => ['class' => 'form-group col-md-3'],
      ]);

      // Border Details
      CRUD::addField([
          'name'  => 'border',
          'type'  => 'text',
          'label' => 'Border',
          'attributes' => ['placeholder' => 'Carson'],
          'wrapperAttributes' => ['class' => 'form-group col-md-3'],
      ]);
      CRUD::addField([
          'name'  => 'border_cost',
          'type'  => 'number',
          'label' => 'Border Cost',
          'attributes' => ["step" => "0.01", 'placeholder' => 'Cost $'],
          'wrapperAttributes' => ['class' => 'form-group col-md-2'],
      ]);
      CRUD::addField([
          'name'  => 'border_invoice',
          'type'  => 'text',
          'label' => 'Border Invoice #',
          'attributes' => ['placeholder' => 'Invoice #'],
          'wrapperAttributes' => ['class' => 'form-group col-md-2'],
      ]);

      // Other Details
      CRUD::addField([
          'name'  => 'other_description',
          'type'  => 'textarea',
          'label' => 'Other',
          'attributes' => ['placeholder' => 'Description of extra costs.'],
          'wrapperAttributes' => ['class' => 'form-group col-md-6'],
      ]);
      CRUD::addField([
          'name'  => 'other_cost',
          'type'  => 'number',
          'label' => 'Other Cost',
          'attributes' => ["step" => "0.01", 'placeholder' => 'Cost $'],
          'wrapperAttributes' => ['class' => 'form-group col-md-2'],
      ]);


  }



  protected function setupUpdateOperation()
  {
    $this->setupCreateOperation();
  }
}
