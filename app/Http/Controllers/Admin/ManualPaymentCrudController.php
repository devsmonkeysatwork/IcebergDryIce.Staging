<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ManualPaymentRequest;
use App\Mail\OrderPlacedMail;
use App\Models\RecurringOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Order;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Mail;

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


    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        $request = $this->crud->validateRequest();
        $data = $request->all();
        $order_type = $request->input('order_type');
        if($order_type == 'simple'){
            $order = Order::find($data['order_number']);

            if ($order->total_cost != $data['amount']) {
                \Alert::error('The payment amount does not match the order total.')->flash();
                return redirect()->back()->withInput();
            }
            $item = $this->crud->create($data);
            $order->payment_status = 1;
            $order->status = Order::COMPLETED;
            $order->save();
        }else{
            $id = intval(str_replace("R", "", $data['order_number']));
            $recurringOrder = RecurringOrder::whereId($id)->with('order')->first();
            if ($recurringOrder->order->total_cost != $data['amount']) {
                \Alert::error('The payment amount does not match the order total.')->flash();
                return redirect()->back()->withInput();
            }
            $data['recurring_order_id'] = $recurringOrder->id;
            $item = $this->crud->create($data);
            $recurringOrder->recurring_payment_status = 1;
            $recurringOrder->status = RecurringOrder::COMPLETED;
            $recurringOrder->save();
        }



        Mail::to($recurringOrder->order->email ?? $data['email'])->send(new OrderPlacedMail($recurringOrder->order));

        \Alert::success('Manual payment created and order updated.')->flash();

        return redirect($this->crud->route);
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
        $order_type = $request->input('order_type');
        $results = [];
        if($order_type == 'simple'){
            $results = Order::query()
                ->where('status',Order::VALID)
                ->where('id', 'like', "%{$search}%")
                ->orWhere('customer_name', 'like', "%{$search}%")
                ->limit(20)
                ->get();
        }else{
            $completedRecurringOrders = Order::query()
                ->where('status', Order::COMPLETED)
                ->where('recurring', Order::RECURRING)
                ->where(function($query) use ($search) {
                    $query->where('id', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%");
                })
                ->with(['recurringOrders' => function($query) {
                    $query->where('status', RecurringOrder::OPEN)
                        ->whereNull('recurring_payment_status');
                }])
                ->limit(20)
                ->get();
            foreach ($completedRecurringOrders as $order) {
                foreach ($order->recurringOrders as $recurringOrder) {
                    $results[] = [
                        'id' => 'R' . $recurringOrder->id,
                        'text' => '#'.$recurringOrder->order_id.'-R#'.$recurringOrder->id.' - '.$order->customer_name.'-'.Carbon::parse($recurringOrder->scheduled_delivery_date)->format('Y-m-d'),
                        'customer_name' => $order->customer_name,
                        'email' => $order->email,
                        'total_cost' => $order->total_cost
                    ];
                }
            }
        }


        return response()->json($results);
    }
}
