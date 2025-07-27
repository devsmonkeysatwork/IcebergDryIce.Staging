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
                        <td><input class="textbox_data" type="text" name="address" value="{{ old('address') }}" required></td>
                    </tr>

                    <tr>
                        <td>City:</td>
                        <td><input class="textbox_data" type="text" name="city" value="{{ old('city') }}" required></td>
                    </tr>

                    <tr>
                        <td>Postal Code:</td>
                        <td><input class="textbox_data" type="text" name="postal_code" value="{{ old('postal_code') }}" required></td>
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
