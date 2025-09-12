<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ManualPaymentRequest;
use App\Models\ManualPayment;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderPlacedMail;
use App\Models\Invoice;
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


    public function index()
    {
        $this->crud->hasAccessOrFail('list');


        $payments = \App\Models\ManualPayment::all();

        foreach ($payments as $p) {
            $invoice = Invoice::where('id',$p->invoice_id)->first();
        }

        return view('vendor.backpack.crud.manualPayments-list', [
            'payments' => $payments,
            'invoice' => $invoice
        ]);
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
//        dd(\App\Models\ManualPayment::all());
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


    public function Store(Request $request)
    {
        $this->crud->hasAccessOrFail('create');

        $request = $this->crud->validateRequest();
        $data = $request->all();

        $invoice = Invoice::where('id', $data['invoice_id'])
            ->where('payment_status', Invoice::PENDING)
            ->first();

        if (!$invoice) {
            \Alert::error('Invoice not found or already paid.')->flash();
            return redirect()->back()->withInput();
        }

        try {
            DB::beginTransaction();

            // Create manual payment record
            ManualPayment::create([
                'invoice_id' => $invoice->id,
//                'invoice_number' => $data['invoice_id'],
                'contact_name' => $data['contact_name'],
                'email' => $data['email'],
                'description' => $data['description'],
                'amount' => $data['amount'],
            ]);

            // Update invoice payment status
            $invoice->update([
                'payment_status' => Invoice::PAID,
//                'paid_at' => now()
            ]);

            // Optional: Update related order status based on invoice type
            if ($invoice->invoiceable_type === 'App\Models\Order') {
                // Update direct order
                Order::where('invoice_id', $invoice->id)
                    ->update(['payment_status' => 'paid']);
            } else {
                // Update recurring order
                RecurringOrder::where('id', $invoice->invoiceable_id)
                    ->update(['recurring_payment_status' => 'paid']);
            }

            DB::commit();

            \Alert::success('Manual payment created and order updated.')->flash();

        } catch (\Exception $e) {
            DB::rollback();
            \Alert::error('Error processing payment: ' . $e->getMessage())->flash();
            return redirect()->back()->withInput();
        }

        return redirect($this->crud->route);
    }


    public function view($id)
    {
        $this->crud->hasAccessOrFail('update'); // or 'view'

        $entry = \App\Models\ManualPayment::findOrFail($id);
        $orderNumbers = Order::all();
        $invoice = Invoice::where('id',$entry->invoice_id)->first();


        return view('vendor.backpack.crud.manualPayments-edit', [
            'entry' => $entry,
            'crud' => $this->crud,
            'invoice' => $invoice,
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
    public function ajaxSearch_invoices(Request $request)
    {
        $search = $request->input('q');

        $invoices = Invoice::where('payment_status', Invoice::PENDING)
            ->where('invoice_number', 'like', "%{$search}%")
            ->limit(20)
            ->get();

        $results = $invoices->map(function($invoice) {
            if ($invoice->invoiceable_type === 'App\Models\Order') {
                // Direct order - use invoice ID to find the order
                $order = Order::where('invoice_id', $invoice->id)->first();
                return $order ? [
                    'id' => $invoice->id,
                    'order_id' => $order->id,
                    'invoice_number' => $invoice->invoice_number,
                    'customer_name' => $order->customer_name,
                    'email' => $order->email,
                    'total_cost' => $order->total_cost ?? $invoice->amount ?? 0,
                    'type' => 'direct'
                ] : null;
            } else {
                // Recurring order - use invoice ID to find recurring order, then get parent order
                $recurring = RecurringOrder::where('id', $invoice->invoiceable_id)
                    ->where('status', RecurringOrder::OPEN)
                    ->whereNull('recurring_payment_status')
                    ->with('order')
                    ->first();

                return $recurring ? [
                    'id' => $invoice->id, // Return invoice ID for selection
                    'order_id' => $recurring->order->id,
                    'invoice_number' => $invoice->invoice_number,
                    'customer_name' => $recurring->order->customer_name,
                    'email' => $recurring->order->email,
                    'total_cost' => $recurring->order->total_cost ?? $invoice->amount ?? 0,
                    'type' => 'recurring'
                ] : null;
            }
        })->filter()->values();


        return response()->json($results);
    }

}
