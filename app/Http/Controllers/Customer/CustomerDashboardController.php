<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\OrderPlacedMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        // You can pass data to the view if needed

        $customerId = auth()->guard('customer')->id();


        // Get all recurring orders for the customer
        $recurringOrders = Order::where('recurring', Order::RECURRING)
            ->where('customer_id', $customerId)
            ->get();

        // Get the order which has the next recurring due
        $orderWithNextRecurringDue = Order::where('recurring', Order::RECURRING)
            ->where('status',Order::VALID)
            ->where('customer_id', $customerId)
            ->whereHas('recurringOrders', function ($query) {
                $query->where('status', 'open')
                    ->where('scheduled_delivery_date', '>', now());
            })
            ->with(['recurringOrders' => function ($query) {
                $query->where('status', 'open')
                    ->where('scheduled_delivery_date', '>', now())
                    ->orderBy('scheduled_delivery_date');
            }])
            ->get()
            ->sortBy(function ($order) {
                return $order->recurringOrders->first()?->scheduled_delivery_date;
            })
            ->first();

        // Get orders for the current customer with pagination
        $orders = Order::with('invoice')
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc') // or 'delivery_date'
            ->paginate(request('per_page', 10));

        // Check if pagination is being used
        $isPaginated = $orders instanceof \Illuminate\Pagination\LengthAwarePaginator;

        return view('website.customer.customer_dashboard',compact('orders', 'isPaginated','orderWithNextRecurringDue'));
    }

    public function myOrders()
    {
        $customerId = auth()->guard('customer')->id();

        // $customerId = auth()->guard('customer')->id();

        // Get orders for the current customer with pagination
        $orders = Order::with('invoice')
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc') // or 'delivery_date'
            ->paginate(request('per_page', 10));

        // Check if pagination is being used
        $isPaginated = $orders instanceof \Illuminate\Pagination\LengthAwarePaginator;

        return view('website.customer.my_orders', compact('orders', 'isPaginated'));
    }

    public function orderDetails($id, Request $request)
    {
        $recurring = intval($request->query('recurring'));
        if($recurring){
            $order = Order::where('recurring', Order::RECURRING)
                ->where('id', $id)
                ->whereHas('recurringOrders', function ($query) {
                    $query->where('status', 'open')
                        ->where('scheduled_delivery_date', '>', now());
                })
                ->with(['recurringOrders' => function ($query) {
                    $query->where('status', 'open')
                        ->where('scheduled_delivery_date', '>', now())
                        ->orderBy('scheduled_delivery_date');
                }])
                ->with(['items.product'])
                ->get()
                ->sortBy(function ($order) {
                    return $order->recurringOrders->first()?->scheduled_delivery_date;
                })
                ->first();
            $status = null;
            if ($order->recurringOrders?->first()->novex_order_id) {
                $cacheKey = 'order_status_' . $order->recurringOrders?->first()->novex_order_id;
                $status = Cache::remember($cacheKey, now()->addHours(3), function () use ($order) {
                    return $this->getOrderStatus($order->recurringOrders?->first()->novex_order_id);
                });
            }
        }else{
            $order = Order::with(['items.product'])->findOrFail($id);
            $status = null;
            if ($order->novex_order_id) {
                $cacheKey = 'order_status_' . $order->novex_order_id;
                $status = Cache::remember($cacheKey, now()->addHours(3), function () use ($order) {
                    return $this->getOrderStatus($order->novex_order_id);
                });
            }
        }

        if ($order->customer_id !== auth()->guard('customer')->id()) {
            abort(403);
        }
        return view('website.customer.partials.order_details', compact('order','status','recurring'));
    }
    public function orderInvoice($id)
    {
        $order = Order::with(['items.product'])->findOrFail($id);

        if ($order->customer_id !== auth()->guard('customer')->id()) {
            abort(403);
        }
        return response()->json([
            'html' => view('website.customer.partials.invoice', compact('order'))->render()
        ]);
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
