<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        // You can pass data to the view if needed
        return view('website.customer_dashboard');
    }
}
