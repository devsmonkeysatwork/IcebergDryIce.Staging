<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        // Check if customer is authenticated via custom guard
        if (Auth::guard('customer')->check()) {
            return $next($request);
        }

        // Redirect to customer login if not authenticated
        return redirect()->route('login.form')->with('error', 'Please log in as a customer.');
    }
}
