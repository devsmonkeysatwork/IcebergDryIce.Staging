<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        // You can pass data to the view if needed
        return view('website.customer.customer_dashboard');
    }

    public function myOrders()
    {
        $customerId = auth()->guard('customer')->id();

        // $customerId = auth()->guard('customer')->id();

        // Get orders for the current customer with pagination
        $orders = Order::where('customer_id', $customerId)
            ->orderBy('created_at', 'desc') // or 'delivery_date'
            ->paginate(request('per_page', 10));

        // Check if pagination is being used
        $isPaginated = $orders instanceof \Illuminate\Pagination\LengthAwarePaginator;

        return view('website.customer.my_orders', compact('orders', 'isPaginated'));
    }

    public function orderDetails($id)
    {
        $order = Order::with(['items.product'])->findOrFail($id);
        $status = null;
        if($order->novex_order_id){
            $status = $this->getOrderStatus($order->novex_order_id);
        }
        // Optional: ensure the order belongs to logged-in customer
        if ($order->customer_id !== auth()->guard('customer')->id()) {
            abort(403);
        }
        return view('website.customer.partials.order_details', compact('order','status'));
    }


    function getOrderStatus($orderNumber)
    {
        try {
            $response = Http::withOptions([
                'verify' => config('services.http_verify'),
            ])->withHeaders([
                'Authorization' => 'Basic ' . config('services.novex.auth_key'),
                'Content-Type' => 'application/json'
            ])->get(config('services.novex.push_url') . "/{$orderNumber}");

            if ($response->successful()) {
                $data = $response->json();
                return $data['status'];
            }

            return null;

        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return null;
        }
    }

}
