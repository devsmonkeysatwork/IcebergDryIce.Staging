@extends('website.layouts.main')
<script src="https://maps.googleapis.com/maps/api/js?key={{config('services.google.api_key')}}&libraries=places"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@section('content')
    <style>
        .error {
            color: red;
            font-weight: bold;
        }

        .valid {
            color: green;
            font-weight: bold;
        }
    </style>
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
                        <td>
                            <input onblur="check_email()" id="email" class="textbox_data" type="email" name="email" value="{{ old('email') }}" required>
                            <span id="email_notes"></span>
                        </td>
                    </tr>

                    <tr>
                        <td>Phone:</td>
                        <td>
                            <input onblur="check_phone();" class="textbox_data" id="phone_input" type="text" name="phone" value="{{ old('phone') }}" required>
                            <span id="phone_notes"></span>
                        </td>
                    </tr>

                    <tr>
                        <td>Address:</td>
                        <td><input class="textbox_data" type="text" name="address" id="address" value="{{ old('address') }}" pattern="^[a-zA-Z0-9\s\-\#\.\/]+$" title="Address can only contain letters, numbers, spaces, hyphens, hash symbols, periods, and forward slashes" required></td>
                    </tr>

                    <tr>
                        <td>City:</td>
                        <td><input class="textbox_data" type="text" name="city" id="city" value="{{ old('city') }}" pattern="^[a-zA-Z\s\-\.]+$" title="City can only contain letters, spaces, hyphens, and periods" required></td>
                    </tr>

                    <tr>
                        <td>Province:</td>
                        <td>
                            <select class="textbox_data w-100" name="province" id="province" required>
                                <option value="">-- Select Province --</option>
                                <option value="BC" {{ old('province') == 'BC' ? 'selected' : '' }}>British Columbia (BC)</option>
                                <option value="AB" {{ old('province') == 'AB' ? 'selected' : '' }}>Alberta (AB)</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Postal Code:</td>
                        <td>
                            <input onblur="check_postal()" id="postal" class="textbox_data" type="text" name="postal_code" value="{{ old('postal_code') }}" pattern="^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$" title="Please enter a valid Canadian postal code (e.g., K1A 0A6)" required>
                            <span id="postal_notes"></span>
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
                        <td colspan="2" align="left">
                            <input class="is_button" type="submit" value="Register">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        async function validateAddress(addressObj) {
            const apiKey = "{{config('services.google.address_api_key')}}";
            const response = await fetch(`https://addressvalidation.googleapis.com/v1:validateAddress?key=${apiKey}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    address: {
                        regionCode: "CA", // For Canada
                        addressLines: [addressObj.address],
                        locality: addressObj.city,
                        administrativeArea: addressObj.province,
                        postalCode: addressObj.postal
                    }
                })
            });

            const data = await response.json();
            return data;
        }

        document.querySelector("form").addEventListener("submit", async function (e) {
            e.preventDefault();
            const address = document.getElementById("address").value.trim();
            const city = document.getElementById("city").value.trim();
            const province = document.getElementById("province").value.trim();
            const postal = document.getElementById("postal").value.trim();

            const result = await validateAddress({ address, city, province, postal });
            console.log(result);
            // Check if address is invalid
            if (result.result.verdict.possibleNextAction === "FIX" || result.result.verdict.hasUnconfirmedComponents) {
                const suggestions = result.result.address.formattedAddress;

                Swal.fire({
                    title: 'Address Validation',
                    html: `Address could not be confirmed. Please provide proper address. <br>Street Address, City, Province, Postal`,
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#d33',
                });
            } else {
                this.submit();
            }
        });





        function check_phone() {
            var error_msg = '';
            if (document.getElementById('phone_input').value != '') {
                var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
                if (!phoneno.test(document.getElementById('phone_input').value))
                    error_msg = 'Please enter a valid 10 digit phone number.';
                set_msg('phone_notes', error_msg, true);
            }
            return error_msg;
        }
        function check_email() {
            var error_msg = '';
            if (document.getElementById('email').value != '') {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if (!re.test(document.getElementById('email').value))
                    error_msg = 'Please enter a valid email.';
                set_msg('email_notes', error_msg, true);
            }
            return error_msg;
        }
        function check_postal() {
            var postal = document.getElementById('postal').value;
            var address = document.getElementById('address').value;
            var city = document.getElementById('city').value;
            var prov = document.getElementById('province').value;
            var error_msg = '';
            if (address === '' || city === '' || prov === '' || postal === ''){
                error_msg = 'Please enter all address information.';
                set_msg('postal_notes', error_msg, true);
                return 0;
            }

            if (postal != '') {
                var no_spaces_postal = postal.replace(/ /g, '');
                no_spaces_postal = no_spaces_postal.toUpperCase();
                document.getElementById('postal').value = no_spaces_postal;
                postal = no_spaces_postal;
                var regex = new RegExp(/^[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ]( )?\d[ABCEGHJKLMNPRSTVWXYZ]\d$/i);
                if (!regex.test(postal))
                    error_msg += '<br>Postal code must be in the form A1A1A1.';
            }
            set_msg('postal_notes', error_msg, true);
        }
        function set_msg(elementId, message, isError) {
            var element = document.getElementById(elementId);
            if (element) {
                element.innerHTML = message;
                if (isError) {
                    element.className = 'error';
                } else {
                    element.className = 'valid';
                }
            }
        }
    </script>
@endsection
