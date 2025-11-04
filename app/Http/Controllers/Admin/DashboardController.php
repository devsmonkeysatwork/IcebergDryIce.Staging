<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RecurringOrder;

class DashboardController extends Controller
{
    public function index() {

        $orders = Order::select('id', 'customer_name', 'delivery_date', 'recurring', 'status', 'total_cost', 'address', 'origin', 'amount_of_boxes', 'amount_of_ice', 'invoice_id')
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'invoice_id' => $order->invoice_id,
                    'customer_name' => $order->customer_name,
                    'delivery_date' => $order->delivery_date,
                    'status' => $order->status,
                    'amount_of_ice' => $order->amount_of_ice,
                    'amount_of_boxes' => $order->amount_of_boxes,
                    'total_cost' => $order->total_cost,
                    'origin' => $order->origin,
                    'address' => $order->address,
                    'type' => 'order'
                ];
            });

        $recurringOrdersData = RecurringOrder::with('order')
            ->get()
            ->map(function($recurring) {
                return [
                    'id' => $recurring->order->id,
                    'invoice_id' => $recurring->invoice_id, // Use invoice_id from RecurringOrder table
                    'customer_name' => $recurring->order->customer_name,
                    'delivery_date' => $recurring->scheduled_delivery_date,
                    'status' => $recurring->status,
                    'amount_of_ice' => $recurring->order->amount_of_ice,
                    'amount_of_boxes' => $recurring->order->amount_of_boxes,
                    'total_cost' => $recurring->order->total_cost,
                    'origin' => $recurring->order->origin,
                    'address' => $recurring->order->address,
                    'type' => 'recurring'
                ];
            });

        $manualOrders = $orders->concat($recurringOrdersData)
            ->where('origin', 'manual')
            ->sortByDesc('delivery_date')
            ->take(15)
            ->values();

        $onlineOrders = $orders->concat($recurringOrdersData)
            ->where('origin', 'online')
            ->sortByDesc('id')
            ->take(15)
            ->values();

        $recurringOrders = RecurringOrder::where('status', RecurringOrder::OPEN)
            ->where('scheduled_delivery_date', '>', now())
            ->whereHas('order', function ($query) {
                $query->where('recurring', 'recurring')
                    ->whereIn('status', [Order::VALID, Order::COMPLETED]);
            })
            ->with(['order'])
            ->orderBy('scheduled_delivery_date')
            ->latest()
            ->take(15)
            ->get();

        $oneTimeOrders = Order::where('recurring', 'non-recurring')
            ->whereIn('status', [Order::VALID, Order::COMPLETED])
            ->orderBy('id', 'desc')
            ->take(15)
            ->get();

        $recurringFromOrders = Order::where('recurring', 'recurring')
            ->whereIn('status', [Order::VALID, Order::COMPLETED])
            ->orderBy('delivery_date')
            ->take(15)
            ->get();

        $recurringFromRecurringTable = RecurringOrder::where('status', RecurringOrder::OPEN)
            ->where('scheduled_delivery_date', '>', now())
            ->whereHas('order', function ($query) {
                $query->where('recurring', 'recurring')
                    ->whereIn('status', [Order::VALID]);
            })
            ->with(['order'])
            ->orderBy('scheduled_delivery_date')
            ->take(15)
            ->get();

        $allRecurringOrders = collect()
            ->merge($recurringFromOrders)
            ->merge($recurringFromRecurringTable)
            ->sortBy(function ($item) {
                return $item instanceof RecurringOrder
                    ? $item->scheduled_delivery_date
                    : $item->delivery_date;
            });

        return view('vendor.backpack.base.dashboard', compact(
            'manualOrders',
            'onlineOrders',
            'recurringOrders',
            'oneTimeOrders',
            'allRecurringOrders',
        ));
    }
}
