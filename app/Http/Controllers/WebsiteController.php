<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

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
        $products = Product::all();
        return view('website.order.order', compact('products'));

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
