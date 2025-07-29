<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RecurringOrder;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch data for cards
        $totalSalesOnline = Order::where('origin', 'online')->sum('total_cost');
        $totalSalesManual = Order::where('origin', 'manual')->sum('total_cost');
        $dryIceUnitSold = Order::sum('amount_of_ice');
        $styrofoamBoxUnitSold = Order::sum('amount_of_boxes');
        // Add recurring orders with completed status
        $recurringOnlineSales = RecurringOrder::where('status', 'completed')
            ->whereHas('order', function($query) {
                $query->where('origin', 'online');
            })
            ->with('order')
            ->get()
            ->sum(function($recurring) {
                return $recurring->order->total_cost;
            });

        $recurringManualSales = RecurringOrder::where('status', 'completed')
            ->whereHas('order', function($query) {
                $query->where('origin', 'manual');
            })
            ->with('order')
            ->get()
            ->sum(function($recurring) {
                return $recurring->order->total_cost;
            });

        $recurringDryIce = RecurringOrder::where('status', 'completed')
            ->with('order')
            ->get()
            ->sum(function($recurring) {
                return $recurring->order->amount_of_ice;
            });

        $recurringStyrofoam = RecurringOrder::where('status', 'completed')
            ->with('order')
            ->get()
            ->sum(function($recurring) {
                return $recurring->order->amount_of_boxes;
            });

        // Add recurring to totals
        $totalSalesOnline += $recurringOnlineSales;
        $totalSalesManual += $recurringManualSales;
        $dryIceUnitSold += $recurringDryIce;
        $styrofoamBoxUnitSold += $recurringStyrofoam;

        // Last year data for stats
        $lastYearOnline = Order::where('origin', 'online')
            ->whereYear('created_at', now()->year - 1)
            ->sum('total_cost');
        $lastYearManual = Order::where('origin', 'manual')
            ->whereYear('created_at', now()->year - 1)
            ->sum('total_cost');
        $lastYearDryIce = Order::whereYear('created_at', now()->year - 1)
            ->sum('amount_of_ice');
        $lastYearStyrofoam = Order::whereYear('created_at', now()->year - 1)
            ->sum('amount_of_boxes');

        // Calculate percentage changes
        $onlineChange = $lastYearOnline > 0 ? ((intval($totalSalesOnline) - $lastYearOnline) / $lastYearOnline) * 100 : 0;
        $manualChange = $lastYearManual > 0 ? ((intval($totalSalesManual) - $lastYearManual) / $lastYearManual) * 100 : 0;
        $dryIceChange = $lastYearDryIce > 0 ? ((intval($dryIceUnitSold) - $lastYearDryIce) / $lastYearDryIce) * 100 : 0;
        $styrofoamChange = $lastYearStyrofoam > 0 ? ((intval($styrofoamBoxUnitSold) - $lastYearStyrofoam) / $lastYearStyrofoam) * 100 : 0;

        // Fetch data for tables
        $lastOrders = Order::latest()->take(10)->get();
        $ccOrders = Order::where('origin', 'online')->latest()->take(4)->get();
        $recurringOrders = RecurringOrder::where('status', 'open')
            ->where('scheduled_delivery_date', '>', now())
            ->whereHas('order', function ($query) {
                $query->where('recurring', 'recurring')
                    ->where('status', 'valid');
            })
            ->with(['order'])
            ->orderBy('scheduled_delivery_date')
            ->latest()
            ->take(4)
            ->get();

        return view('vendor.backpack.base.dashboard', compact(
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
            'recurringOrders'
        ));
    }
}
