<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\RecurringOrder;
use Illuminate\Http\Request;

class RecurringOrderController extends Controller
{
    public function getNextRecurringOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        $nextRecurring = $order->nextRecurringOrder();

        if (!$nextRecurring) {
            return response()->json(['message' => 'No upcoming recurring order found'], 404);
        }

        return response()->json([
            'order' => $order,
            'next_recurring' => $nextRecurring
        ]);
    }

    public function cancelRecurringOrder($recurringOrderId)
    {
        $recurringOrder = RecurringOrder::findOrFail($recurringOrderId);

        $recurringOrder->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Recurring order cancelled successfully']);
    }
}
