<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('website.index');
    }

    public function contact()
    {
        return view('website.contact');
    }
    public function showOrderForm()
    {
        return view('website.order.order');
    }
    public function storeOrder()
    {
        return view('website.order.location');
    }
    public function location()
    {
        return view('website.order.location');
    }

    public function storeLocation()
    {
        return redirect()->route('review');
    }

    public function review()
    {
        return view('website.order.review');
    }
}
