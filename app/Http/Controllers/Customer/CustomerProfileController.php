<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
class CustomerProfileController extends Controller
{


    /**
     * Show the customer profile form
     */
    public function show()
    {
        // Get the authenticated customer
        $customer = auth()->guard('customer')->user();

        // Alternative ways to get customer if the above doesn't work:
        // $customer = session('customer');
        // $customerId = session('customer_id');
        // $customer = Customer::find($customerId);

        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Please login to view your profile');
        }

        return view('website.customer.my_profile', compact('customer'));
    }

    /**
     * Update the customer profile
     */
    public function updateProfile(Request $request)
    {
        $customer = auth()->guard('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Please login to update your profile');
        }

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('customers')->ignore($customer->id)
            ],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'province' => 'required|in:BC,AB',
            'country' => 'nullable|string|max:100'
        ]);

        // Update customer information
        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'province' => $request->province,
            'country' => $request->country ?? 'Canada'
        ]);

        return redirect()->route('customer.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the customer password
     */


    public function updatePassword(Request $request)
    {
        $customer = auth()->guard('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Please login to change your password');
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => [
                'required',
                'min:8',
                'confirmed',
                // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'
            ],
            'new_password_confirmation' => 'required'
        ], [
            'new_password.regex' => 'Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character.',
            'new_password.confirmed' => 'New password confirmation does not match.'
        ]);

        // Add custom check for current password
        $validator->after(function ($validator) use ($request, $customer) {
            if (!Hash::check($request->current_password, $customer->password)) {
                $validator->errors()->add('current_password', 'The current password you entered is incorrect. If you’ve forgotten it, please reset your password using the "Forgot Password" option.');
            }
        });

        // Handle validation failure
        if ($validator->fails()) {
            return redirect()->route('customer.profile')
                ->withErrors($validator)
                ->withInput();
        }

        // Update password
        $customer->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('customer.profile')
            ->with('success', 'Password changed successfully!');
    }

    /**
     * Alternative method if you're using session-based authentication
     */
    public function showAlternative()
    {
        $customerId = session('customer_id') ?? session('logged_customer_id');

        if (!$customerId) {
            return redirect()->route('customer.login')->with('error', 'Please login to view your profile');
        }

        $customer = Customer::find($customerId);

        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Customer not found');
        }

        return view('website.customer.profile', compact('customer'));
    }

    /**
     * Update profile using session-based authentication
     */
    public function updateProfileAlternative(Request $request)
    {
        $customerId = session('customer_id') ?? session('logged_customer_id');

        if (!$customerId) {
            return redirect()->route('customer.login')->with('error', 'Please login to update your profile');
        }

        $customer = Customer::find($customerId);

        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Customer not found');
        }

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('customers')->ignore($customer->id)
            ],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'province' => 'required|in:BC,AB',
            'country' => 'nullable|string|max:100'
        ]);

        // Update customer information
        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'province' => $request->province,
            'country' => $request->country ?? 'Canada'
        ]);

        // Update session data if needed
        session(['customer' => $customer->toArray()]);

        return redirect()->route('customer.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update password using session-based authentication
     */
    public function updatePasswordAlternative(Request $request)
    {
        $customerId = session('customer_id') ?? session('logged_customer_id');

        if (!$customerId) {
            return redirect()->route('customer.login')->with('error', 'Please login to change your password');
        }

        $customer = Customer::find($customerId);

        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Customer not found');
        }

        // Validate the request
        $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'
            ],
            'new_password_confirmation' => 'required'
        ], [
            'new_password.regex' => 'Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character.',
            'new_password.confirmed' => 'New password confirmation does not match.'
        ]);

        // Check if current password is correct
        if (!Hash::check($request->current_password, $customer->password)) {
            return redirect()->route('customer.profile')
                ->with('error', 'Current password is incorrect.');
        }

        // Update password
        $customer->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('customer.profile')
            ->with('success', 'Password changed successfully!');
    }


}
