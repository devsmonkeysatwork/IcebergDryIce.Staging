<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedCustom
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Check admin
        if (Auth::guard('web')->check()) {
            return redirect('/admin/dashboard');
        }

        // Check customer
        if (Auth::guard('customer')->check()) {
            return redirect('/customer/dashboard');
        }

        return $next($request);
    }
}
