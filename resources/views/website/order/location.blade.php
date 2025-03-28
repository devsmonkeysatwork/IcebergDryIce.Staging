@extends('website.layouts.main')

@section('content')
    <style>
        th {
            background-color: #006;
            color: #FFF;
            font-weight: bold;
        }
    </style>

    <body onload="document.getElementById('weight').focus();">

    <br>

    <center>

        <table width='90%'>
            <tr>
                <td class='forsale_menu'><a href='{{ url('/order') }}'>Order</a></td>
                <td class='forsale_menu selected'>Location</td>
                <td class='forsale_menu'><a href='{{ url('/review') }}'>Review</a></td>
            </tr>
        </table>

        <br>
        <form action="" method="post" id='location_form'>
            <input type='hidden' name='area' id='area'>

            <table width="95%">

                <tr>
                    <th colspan='4'>Delivery Location</th>
                </tr>
                <tr>
                    <td>Province</td>
                    <td></td>
                    <td>
                        <select name='province' id='province' style='width: 210px;' class='textbox_data'>
                            <option value='BC'>British Columbia</option>
                            <option value='AB'>Alberta</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>City</td>
                    <td></td>
                    <td><input onChange='get_delivery();' class='textbox_data' size='22' type='text' id='city' name='city'></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td></td>
                    <td><input onChange='get_delivery();' class='textbox_data' size='22' type='text' id='address' name='address'></td>
                    <td rowspan='4'>
                        <div id='address_notes'></div>
                    </td>
                </tr>
                <tr>
                    <td>Postal</td>
                    <td></td>
                    <td><input onChange='get_delivery();' class='textbox_data' size='22' type='text' id='postal' name='postal'></td>
                </tr>
                <tr>
                    <td colspan='4'><br></td>
                </tr>
                <tr>
                    <th colspan='4'>Delivery Date</th>
                </tr>
                <tr>
                    <td colspan='3'>
                        <select name="month" id='month' style='width: 80px;' class='textbox_data'>
                            @foreach (["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"] as $index => $month)
                                <option value="{{ $index + 1 }}" {{ old('month', session('cc_order.month', date('m'))) == $index + 1 ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                        <select name="day" id='day' style='width: 80px;' class='textbox_data'>
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}" {{ old('day', session('cc_order.day', date('d'))) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        <select name="year" id='year' style='width: 80px;' class='textbox_data'>
                            @for ($i = 2022; $i <= 2024; $i++)
                                <option value="{{ $i }}" {{ old('year', session('cc_order.year', date('Y'))) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </td>
                    <td>
                        <br>
                        <div id='date_notes'>Minimum 12 hours notice required for new orders.
                        <br>
                        <br>
                        </div>
                    </td>
                </tr>


                <tr>
                    <th colspan='4'>Contact Information and Notes</th>
                </tr>
                <tr>
                    <td width='130px'>Your Name</td>
                    <td></td>
                    <td><input class='textbox_data' style='text-align: left;' size='22' type='text' name='name' id='name' value='<?php if (isset($_SESSION['cc_order'])) echo $_SESSION['cc_order']->name; ?>'></td>
                    <td></td>
                </tr>
                <tr>

                    <td>Location Type</td>
                    <td></td>
                    <td>
                        <select class='textbox_data' style='width: 215px;' id='location_type' onChange="set_name();">
                            <option value='2'>Company</option>
                            <option value='1'>Home Residence</option>
                        </select>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Location Name</td>
                    <td></td>
                    <td><input onBlur='check_company_name();' class='textbox_data' size='22' type='text' id='company' name='company' value=''></td>
                    <td>
                        <div id='company_notes'></div>
                    </td>

                </tr>
                <tr>
                    <td>Phone</td>
                    <td></td>
                    <td><input onBlur="check_phone();" class='textbox_data' style='text-align: left;' size='22' type='text' name='phone' id='phone' value=''></td>
                    <td>
                        <div id='phone_notes'></div>
                    </td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td></td>
                    <td><input onBlur="check_email();" class='textbox_data' style='text-align: left;' size='22' type='text' name='email' id='email' value=''></td>
                    <td>
                        <div id='email_notes'></div>
                    </td>
                </tr>
                <tr>
                    <td>Notes</td>
                    <td></td>
                    <td><input class='textbox_data' style='text-align: left;' size='22' type='text' name='notes' id='notes' value=''></td>
                    <td></td>
                </tr>

            </table>
            <center>
                <input style='width: 100px; text-align: center; color: #090; border: outset 2px #090;' class='is_button' name='next' value='NEXT' onClick="next_page();">
            </center>
        </form>
    </center>
    <center>
        <br><b>We deliver to the Greater Vancouver Regional District, and Calgary<br>
        Deliveries require minimum 5 hour window. (If necessary, write in notes: "Deliver before 1pm" or "deliver after 12")</b>
    </center>

    <script type='text/javascript'>
        function next_page() {
            if (check_date() === '' && check_address() === '' && check_company_name() === '' && check_contact() === '' && check_phone() === '' && check_email() === '')
                document.getElementById('location_form').submit();
        }

        // date must be tomorrow+, or it needs to be before 830am
        // date must be a business day
        function check_date() {
            var year = document.getElementById('year').value;
            var month = document.getElementById('month').value;
            var day = document.getElementById('day').value;
            var error_msg = '';

            var date = new Date(year, month - 1, day); // javascript dates are base 0
            var today = new Date();
            var hour_now = today.getHours();
            var min_now = today.getMinutes();
            today.setHours(0, 0, 0, 0);
            var tomorrow = new Date(today.getTime() + 24 * 60 * 60 * 1000);

            if (date.getDay() == 0 || /*date.getDay() == 2 || date.getDay() == 4 ||*/ date.getDay() == 6)
                error_msg += 'Week days only please.<br>';
            if (date.getYear() == today.getYear() && date.getMonth() == today.getMonth() && date.getDate() == today.getDate()) {
                if (hour_now == 8 && min_now > 30 || hour_now > 8)
                    error_msg += 'Same day orders must be completed before 8:30am.<br>';
            } else if (date < tomorrow)
                error_msg += 'Orders must be completed before 8:30am day of.<br>';

            if (error_msg === '')
                set_msg('date_notes', '<center><b>VALID</b></center>', false);
            else
                set_msg('date_notes', error_msg, true);

            return error_msg;
        }

        // all address values need to be filled out (address, city, postal)
        // postal must be valid
        function check_address() {
            var address = document.getElementById('address').value;
            var city = document.getElementById('city').value;
            var prov = document.getElementById('province').value;
            var postal = document.getElementById('postal').value;
            var error_msg = '';

            if (address === '' || city === '' || prov === '' || postal === '')
                error_msg = 'Please enter all address information.';

            if (postal != '') {
                var no_spaces_postal = postal.replace(/ /g, '');
                no_spaces_postal = no_spaces_postal.toUpperCase();
                document.getElementById('postal').value = no_spaces_postal;
                postal = no_spaces_postal;
                var regex = new RegExp(/^[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ]( )?\d[ABCEGHJKLMNPRSTVWXYZ]\d$/i);
                if (!regex.test(postal))
                    error_msg += '<br>Postal code must be in the form A1A1A1.';
            }

            if (error_msg === '')
                set_msg('address_notes', '<center><b>VALID</b></center>', false);
            else
                set_msg('address_notes', error_msg, true);

            return error_msg;
        }

        function check_company_name() {
            //	alert("Checking company name: " + document.getElementById('company').value);
            var error_msg = '';
            if (document.getElementById('location_type').value == 1) {
                document.getElementById('company').value = 'Residence';
            } else {
                if (document.getElementById('company').value === '')
                    error_msg = "Please enter the company name here.";
            }
            set_msg('company_notes', error_msg, true);
            return error_msg;
        }

        function check_contact() {
            //	alert('check_contact: ' + document.getElementById('phone').value + ' / ' + document.getElementById('email').value);
            var error_msg = '';
            if (document.getElementById('email').value === '' && document.getElementById('phone').value === '')
                error_msg += 'Please enter an email or phone number so we can contact you if there is any trouble with your order.';
            set_msg('phone_notes', error_msg, true);

            return error_msg;
        }

        function check_phone() {
            //	alert("Checking phone#: " + document.getElementById('phone').value);
            var error_msg = '';
            if (document.getElementById('phone').value != '') {
                var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
                if (!phoneno.test(document.getElementById('phone').value))
                    error_msg = 'Please enter a valid 10 digit phone number.';
                set_msg('phone_notes', error_msg, true);
            }
            return error_msg;
        }

        function check_email() {
            //	alert('check_email' + document.getElementById('email').value);
            var error_msg = '';
            if (document.getElementById('email').value != '') {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if (!re.test(document.getElementById('email').value))
                    error_msg = 'Please enter a valid email.';
                set_msg('email_notes', error_msg, true);
            }
            return error_msg;
        }

        function set_name() {
            if (document.getElementById('location_type').value == 1);
            document.getElementById('company').value = 'Residence';
        }
    </script>
@endsection
