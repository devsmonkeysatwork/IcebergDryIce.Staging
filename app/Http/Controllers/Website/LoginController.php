<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('website.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');


        // Try admin first
        if (Auth::guard('web')->attempt($credentials)) {
            return redirect()->intended('/admin/dashboard');
        }

        // Then try customer
        if (Auth::guard('customer')->attempt($credentials)) {
            $redirect = $request->input('redirect') ?? '/customer/dashboard';
            return redirect()->intended($redirect);
        }

        // Failed login
        return back()->with('ibdi_error', 'Invalid login credentials');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::guard('customer')->logout();
        return redirect()->route('login.form');
    }
}
