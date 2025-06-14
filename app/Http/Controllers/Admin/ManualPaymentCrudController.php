<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ManualPaymentRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ManualPaymentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ManualPaymentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\ManualPayment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/manual-payments');
        CRUD::setEntityNameStrings('manual payment', 'manual payments');
        CRUD::setCreateView('vendor.backpack.base.manual_payments');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb();
        $this->crud->removeAllButtonsFromStack('line');
        $this->crud->addButtonFromView('line', 'view_button', 'view-button', 'beginning');

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ManualPaymentRequest::class);

        CRUD::field('contact_name')->type('text')->label('Contact Name');
        CRUD::field('email')->type('email');
        CRUD::field('order_number')->type('text');
        CRUD::field('description')->type('textarea');
        CRUD::field('amount')->type('number');
    }

    public function view($id)
    {
        $this->crud->hasAccessOrFail('update'); // or 'view'

        $entry = \App\Models\ManualPayment::findOrFail($id);
        $orderNumbers = Order::all();

        return view('vendor.backpack.crud.manualPayments-edit', [
            'entry' => $entry,
            'crud' => $this->crud,
            'orderNumbers' => $orderNumbers,
        ]);
    }

    public function updateFromView($id)
    {
        $this->crud->hasAccessOrFail('update');
        $data = request()->all();

        $entry = \App\Models\ManualPayment::findOrFail($id);
        $entry->update($data);

        \Alert::success('Payment updated successfully.')->flash();
        return redirect()->route('payments.view', $id);
    }

    public function deleteFromView($id)
    {
        $this->crud->hasAccessOrFail('delete');

        \App\Models\ManualPayment::findOrFail($id)->delete();

        \Alert::success('Payment Deleted.')->flash();

        return redirect($this->crud->route);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
    public function ajaxSearch(\Illuminate\Http\Request $request)
    {
        $search = $request->input('q');

        $results = Order::query()
            ->where('id', 'like', "%{$search}%")
            ->orWhere('customer_name', 'like', "%{$search}%")
            ->limit(20)
            ->get(['id', 'customer_name']);

        return response()->json($results);
    }
}
