<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        // You can pass data to the view if needed
        return view('website.customer.customer_dashboard');
    }

    public function myOrders()
    {
        // Get the authenticated customer ID
        $customerId = auth()->guard('customer')->id();
        // If you're using a different authentication guard for customers
        // $customerId = auth()->guard('customer')->id();

        // Get orders for the current customer with pagination
        $orders = Order::where('customer_id', $customerId)
            ->orderBy('created_at', 'desc') // or 'delivery_date'
            ->paginate(request('per_page', 10));

        // Check if pagination is being used
        $isPaginated = $orders instanceof \Illuminate\Pagination\LengthAwarePaginator;

        return view('website.customer.my_orders', compact('orders', 'isPaginated'));
    }
}
