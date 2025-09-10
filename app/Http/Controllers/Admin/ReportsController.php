<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RecurringOrder;

class ReportsController extends Controller
{
    public function index()
    {


        $currentYear = now()->year;
        $lastYear = $currentYear - 1;

// ------------------ CURRENT YEAR TOTALS (YTD) ------------------ //
        $totalSalesOnline = Order::where('origin', 'online')
            ->whereYear('created_at', $currentYear)
            ->sum('total_cost');

        $totalSalesManual = Order::where('origin', 'manual')
            ->whereYear('created_at', $currentYear)
            ->sum('total_cost');

        $dryIceUnitSold = Order::whereYear('created_at', $currentYear)
            ->sum('amount_of_ice');

        $styrofoamBoxUnitSold = Order::whereYear('created_at', $currentYear)
            ->sum('amount_of_boxes');

// Recurring orders (completed only, current year YTD)
        $recurringOnlineSales = RecurringOrder::where('status', 'completed')
            ->whereHas('order', fn($q) => $q->where('origin', 'online')
                ->whereYear('created_at', $currentYear))
            ->with('order')
            ->get()
            ->sum(fn($recurring) => $recurring->order->total_cost);

        $recurringManualSales = RecurringOrder::where('status', 'completed')
            ->whereHas('order', fn($q) => $q->where('origin', 'manual')
                ->whereYear('created_at', $currentYear))
            ->with('order')
            ->get()
            ->sum(fn($recurring) => $recurring->order->total_cost);

        $recurringDryIce = RecurringOrder::where('status', 'completed')
            ->whereHas('order', fn($q) => $q->whereYear('created_at', $currentYear))
            ->with('order')
            ->get()
            ->sum(fn($recurring) => $recurring->order->amount_of_ice);

        $recurringStyrofoam = RecurringOrder::where('status', 'completed')
            ->whereHas('order', fn($q) => $q->whereYear('created_at', $currentYear))
            ->with('order')
            ->get()
            ->sum(fn($recurring) => $recurring->order->amount_of_boxes);

// Add recurring to current totals
        $totalSalesOnline += $recurringOnlineSales;
        $totalSalesManual += $recurringManualSales;
        $dryIceUnitSold += $recurringDryIce;
        $styrofoamBoxUnitSold += $recurringStyrofoam;


// ------------------ LAST YEAR TOTALS (FULL YEAR) ------------------ //
        $lastYearOnline = Order::where('origin', 'online')
            ->whereYear('created_at', $lastYear)
            ->sum('total_cost');

        $lastYearManual = Order::where('origin', 'manual')
            ->whereYear('created_at', $lastYear)
            ->sum('total_cost');

        $lastYearDryIce = Order::whereYear('created_at', $lastYear)
            ->sum('amount_of_ice');

        $lastYearStyrofoam = Order::whereYear('created_at', $lastYear)
            ->sum('amount_of_boxes');

// Recurring (completed only, last year FULL YEAR)
        $lastYearRecurringOnline = RecurringOrder::where('status', 'completed')
            ->whereHas('order', fn($q) => $q->where('origin', 'online')
                ->whereYear('created_at', $lastYear))
            ->with('order')
            ->get()
            ->sum(fn($recurring) => $recurring->order->total_cost);

        $lastYearRecurringManual = RecurringOrder::where('status', 'completed')
            ->whereHas('order', fn($q) => $q->where('origin', 'manual')
                ->whereYear('created_at', $lastYear))
            ->with('order')
            ->get()
            ->sum(fn($recurring) => $recurring->order->total_cost);

        $lastYearRecurringDryIce = RecurringOrder::where('status', 'completed')
            ->whereHas('order', fn($q) => $q->whereYear('created_at', $lastYear))
            ->with('order')
            ->get()
            ->sum(fn($recurring) => $recurring->order->amount_of_ice);

        $lastYearRecurringStyrofoam = RecurringOrder::where('status', 'completed')
            ->whereHas('order', fn($q) => $q->whereYear('created_at', $lastYear))
            ->with('order')
            ->get()
            ->sum(fn($recurring) => $recurring->order->amount_of_boxes);

// Add recurring to last year totals
        $lastYearOnline += $lastYearRecurringOnline;
        $lastYearManual += $lastYearRecurringManual;
        $lastYearDryIce += $lastYearRecurringDryIce;
        $lastYearStyrofoam += $lastYearRecurringStyrofoam;


// ------------------ PERCENTAGE CHANGE ------------------ //
        $onlineChange = $lastYearOnline > 0
            ? round((($totalSalesOnline - $lastYearOnline) / $lastYearOnline) * 100, 2)
            : 0;

        $manualChange = $lastYearManual > 0
            ? round((($totalSalesManual - $lastYearManual) / $lastYearManual) * 100, 2)
            : 0;

        $dryIceChange = $lastYearDryIce > 0
            ? round((($dryIceUnitSold - $lastYearDryIce) / $lastYearDryIce) * 100, 2)
            : 0;

        $styrofoamChange = $lastYearStyrofoam > 0
            ? round((($styrofoamBoxUnitSold - $lastYearStyrofoam) / $lastYearStyrofoam) * 100, 2)
            : 0;


        // Fetch data for tables
        // Fetch data for tables
        $orders = Order::select('id', 'customer_name', 'delivery_date', 'status', 'total_cost','address', 'origin','amount_of_boxes','amount_of_ice')
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
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
        $lastOrders = $orders->concat($recurringOrdersData)
            ->sortByDesc('delivery_date')
            ->take(5)
            ->values();

        $ccOrders = $orders->concat($recurringOrdersData)
            ->where('origin', 'online')
            ->sortByDesc('delivery_date')
            ->take(5)
            ->values();



        $recurringOrders = RecurringOrder::where('status', RecurringOrder::OPEN)
            ->where('scheduled_delivery_date', '>', now())
            ->whereHas('order', function ($query) {
                $query->where('recurring', 'recurring')
                    ->whereIn('status', [Order::VALID,Order::COMPLETED]);
            })
            ->with(['order'])
            ->orderBy('scheduled_delivery_date')
            ->latest()
            ->take(4)
            ->get();


        return view('vendor.backpack.base.reports', compact(
            'totalSalesOnline',
            'totalSalesManual',
            'dryIceUnitSold',
            'styrofoamBoxUnitSold',
            'onlineChange',
            'manualChange',
            'dryIceChange',
            'styrofoamChange',
            'lastOrders',
            'ccOrders',
            'recurringOrders',
            'lastYearOnline'
        ));
    }
}
