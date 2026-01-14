<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
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

        try {
            DB::beginTransaction();

            // Check for existing customer
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

                $customer = $existingCustomer;

                // Create or update address in customer_addresses table
                $this->createOrUpdateCustomerAddress($customer, $request);

                Auth::guard('customer')->login($customer);

                Mail::to($customer->email)->send(new CustomerRegisteredMail($request));
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

                $customer = $newCustomer;

                // Create address in customer_addresses table
                $this->createOrUpdateCustomerAddress($customer, $request);

                Auth::guard('customer')->login($customer);

                Mail::to($customer->email)->send(new CustomerRegisteredMail($request));
            }

            DB::commit();

            $redirectUrl = session()->pull('redirect_after_register', '/customer/dashboard');

            return redirect()->to($redirectUrl);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Registration failed. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Create or update customer address in customer_addresses table
     *
     * @param Customer $customer
     * @param Request $request
     * @return void
     */
    protected function createOrUpdateCustomerAddress(Customer $customer, Request $request)
    {
        // Check if customer already has a default address
        $existingDefaultAddress = CustomerAddress::where('customer_id', $customer->id)
            ->where('is_default', 1)
            ->where('is_active', 1)
            ->first();

        if ($existingDefaultAddress) {
            // Update existing default address
            $existingDefaultAddress->update([
                'address_label' => 'Primary Address',
                'location_name' => null,
                'address' => $request->address,
                'unit' => null,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'province' => $request->province,
                'country' => 'Canada',
                'delivery_instructions' => null,
                'is_default' => 1,
                'is_active' => 1,
            ]);
        } else {
            // Create new default address
            CustomerAddress::create([
                'customer_id' => $customer->id,
                'address_label' => 'Primary Address',
                'location_name' => null,
                'address' => $request->address,
                'unit' => null,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'province' => $request->province,
                'country' => 'Canada',
                'delivery_instructions' => null,
                'is_default' => 1,
                'is_active' => 1,
            ]);
        }
    }
}
