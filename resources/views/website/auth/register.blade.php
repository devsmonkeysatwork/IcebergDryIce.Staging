@extends('website.layouts.main')

@section('content')
    <div class="container">
        <div class="info">
            <h1 class="">Register Your Account</h1>

            {{-- Display errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <b>{{ $errors->first() }}</b>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <b>{{ session('error') }}</b>
                </div>
            @endif

            @if (request()->has('redirect'))
                @php session(['redirect_after_register' => request('redirect')]); @endphp
            @endif

            <form method="POST" action="{{ route('customer.register') }}">
                @csrf
                <table border="0">
                    <tr>
                        <td width="150px">Name:</td>
                        <td><input class="textbox_data" type="text" name="name" value="{{ old('name') }}" required></td>
                    </tr>

                    <tr>
                        <td>Email:</td>
                        <td><input class="textbox_data" type="email" name="email" value="{{ old('email') }}" required></td>
                    </tr>

                    <tr>
                        <td>Phone:</td>
                        <td><input class="textbox_data" type="text" name="phone" value="{{ old('phone') }}" required></td>
                    </tr>

                    <tr>
                        <td>Address:</td>
                        <td><input class="textbox_data" type="text" name="address" value="{{ old('address') }}" pattern="^[a-zA-Z0-9\s\-\#\.\/]+$" title="Address can only contain letters, numbers, spaces, hyphens, hash symbols, periods, and forward slashes" required></td>
                    </tr>

                    <tr>
                        <td>City:</td>
                        <td><input class="textbox_data" type="text" name="city" value="{{ old('city') }}" pattern="^[a-zA-Z\s\-\.]+$" title="City can only contain letters, spaces, hyphens, and periods" required></td>
                    </tr>

                    <tr>
                        <td>Postal Code:</td>
                        <td><input class="textbox_data" type="text" name="postal_code" value="{{ old('postal_code') }}" pattern="^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$" title="Please enter a valid Canadian postal code (e.g., K1A 0A6)" required></td>
                    </tr>

                    <tr>
                        <td>Province:</td>
                        <td>
                            <select class="textbox_data w-100" name="province" required>
                                <option value="">-- Select Province --</option>
                                <option value="BC" {{ old('province') == 'BC' ? 'selected' : '' }}>British Columbia (BC)</option>
                                <option value="AB" {{ old('province') == 'AB' ? 'selected' : '' }}>Alberta (AB)</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Password:</td>
                        <td><input class="textbox_data" type="password" name="password" required></td>
                    </tr>

                    <tr>
                        <td>Confirm Password:</td>
                        <td><input class="textbox_data" type="password" name="password_confirmation" required></td>
                    </tr>

                    <tr>
                        <td colspan="2" align="right">
                            <input class="is_button" type="submit" value="Register">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
@endsection
