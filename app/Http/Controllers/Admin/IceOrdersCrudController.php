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
    $this->crud->removeAllButtonsFromStack('line');
    $this->crud->addButtonFromView('line', 'view_button', 'view-button', 'beginning');


  }

  protected function setupCreateOperation(){

      CRUD::setValidation(IceOrderRequest::class);
      $this->crud->setCreateView('vendor.backpack.crud.ice-order-create');

  }

    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        // Manually validate (or rely on IceOrderRequest)
        $data = request()->all();

        // Optionally manipulate data here
        // $data['something'] = strtoupper($data['something']);

        $item = $this->crud->create($data);

        \Alert::success('Ice order added successfully.')->flash();

        return redirect()->to($this->crud->route);
    }

    public function view($id)
    {
        $this->crud->hasAccessOrFail('update'); // or 'view'

        $entry = \App\Models\IceOrder::findOrFail($id);

        return view('vendor.backpack.crud.ice-order-edit', [
            'entry' => $entry,
            'crud' => $this->crud,
        ]);
    }

    public function updateFromView($id)
    {
        $this->crud->hasAccessOrFail('update');
        $data = request()->all();

        $entry = \App\Models\IceOrder::findOrFail($id);
        $entry->update($data);

        \Alert::success('Ice order updated successfully.')->flash();
        return redirect()->route('ice-orders.view', $id);
    }

    public function deleteFromView($id)
    {
        $this->crud->hasAccessOrFail('delete');

        \App\Models\IceOrder::findOrFail($id)->delete();

        \Alert::success('Ice Order Deleted.')->flash();

        return redirect($this->crud->route);
    }




    protected function setupUpdateOperation()
  {
    $this->setupCreateOperation();
  }
}
