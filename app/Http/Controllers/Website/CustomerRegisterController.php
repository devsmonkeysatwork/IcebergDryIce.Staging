<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use App\Mail\CustomerRegisteredMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class CustomerRegisterController extends Controller
{
    public function showForm(Request $request)
    {
        if ($request->has('redirect')) {
            session(['redirect_after_register' => $request->get('redirect')]);
        }
        return view('website.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string|max:255',
            'city'           => 'required|string|max:100',
            'postal_code'    => 'required|string|max:20',
            'province'       => 'required|in:BC,AB',
            'password'       => 'required|confirmed|min:6',
        ]);

        //  Check for existing customer
        $existingCustomer = Customer::where('email', $request->email)->first();

        if ($existingCustomer) {
            if (!is_null($existingCustomer->password)) {
                // Already registered
                return back()->withErrors(['email' => 'This email is already registered. Please log in instead.']);
            }

            // Guest customer — update record
            $existingCustomer->update([
                'name'        => $request->name,
                'phone'       => $request->phone,
                'address'     => $request->address,
                'city'        => $request->city,
                'postal_code' => $request->postal_code,
                'province'    => $request->province,
                'password'    => Hash::make($request->password),
            ]);

            Auth::guard('customer')->login($existingCustomer);

            Mail::to($existingCustomer->email)->send(new CustomerRegisteredMail($request));
        } else {
            // New customer
            $newCustomer = Customer::create([
                'name'        => $request->name,
                'email'       => $request->email,
                'phone'       => $request->phone,
                'address'     => $request->address,
                'city'        => $request->city,
                'postal_code' => $request->postal_code,
                'province'    => $request->province,
                'password'    => Hash::make($request->password),
            ]);

            Auth::guard('customer')->login($newCustomer);

            Mail::to($newCustomer->email)->send(new CustomerRegisteredMail($request));
        }

        $redirectUrl = session()->pull('redirect_after_register', '/customer/dashboard');

        return redirect()->to($redirectUrl);
    }
}
