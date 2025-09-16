<?php

namespace App\Services;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\RecurringOrder;

class InvoiceService
{
    /**
     * Create invoice for original order
     */
    public function createInvoiceForOrder(Order $order): Invoice
    {
        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'invoice_type' => $order->recurring === 'recurring' ? 'recurring' : 'non-recurring',
            'invoiceable_id' => $order->id,
            'invoiceable_type' => Order::class,
            'total_amount' => $order->total_cost,
            'invoice_date' => now(),
            'recurring_sequence' => 1, // Original is always sequence 1
            'parent_invoice_id' => null // Original has no parent
        ]);

        // Update order with invoice_id
        $order->update(['invoice_id' => $invoice->id]);

        return $invoice;
    }

    /**
     * Create invoice for recurring order instance
     */
    public function createInvoiceForRecurringOrder(RecurringOrder $recurringOrder): Invoice
    {
        $originalOrder = $recurringOrder->order;
        $parentInvoice = $originalOrder->invoice;

        // Calculate sequence number
        $sequence = $parentInvoice->childInvoices()->count() + 2; // +2 because parent is 1

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'invoice_type' => 'recurring',
            'invoiceable_id' => $recurringOrder->id,
            'invoiceable_type' => RecurringOrder::class,
            'total_amount' => $originalOrder->total_cost,
            'invoice_date' => $recurringOrder->scheduled_delivery_date->subDays(2),
            'parent_invoice_id' => $parentInvoice->id,
            'recurring_sequence' => $sequence
        ]);

        // Update recurring order with invoice_id
        $recurringOrder->update(['invoice_id' => $invoice->id]);

        return $invoice;
    }

    /**
     * Get complete invoice chain for an order
     */
    public function getInvoiceChain(Order $order): \Illuminate\Support\Collection
    {
        $originalInvoice = $order->invoice;

        return collect([$originalInvoice])
            ->merge($originalInvoice->childInvoices)
            ->sortBy('recurring_sequence');
    }
}
