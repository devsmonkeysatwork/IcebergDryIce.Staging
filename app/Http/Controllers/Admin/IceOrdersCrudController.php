<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\IceOrderRequest;
use App\Models\Product;
use App\Models\StockMovement;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Events\IceOrderPlaced;
use Illuminate\Support\Facades\DB;

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

        $data = request()->all();

        DB::beginTransaction();

        try {
            // 1. Create the ice order
            $item = $this->crud->create($data);

            // 2. Update stock only if order created successfully
            if ($item && isset($data['weight'])) {
                $iceProduct = Product::find(1);

                if ($iceProduct) {
                    // Update current stock
                    $iceProduct->increment('current_stock', $data['weight']);

                    // Log in stock_movements
                    StockMovement::create([
                        'product_id' => $iceProduct->id,
                        'order_id' => $item->id,
                        'change_type' => 'in',
                        'quantity' => $data['weight'],
                        'description' => 'Ice order received (Order ID: ' . $item->id . ')',
                    ]);
                }
            }

            DB::commit();

            \Alert::success('Ice order added and stock updated successfully.')->flash();
            return redirect()->to($this->crud->route);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Failed to create ice order: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ', $data);

            \Alert::error('Failed to create ice order: ' . $e->getMessage())->flash();
            return redirect()->back()->withInput();
        }
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
