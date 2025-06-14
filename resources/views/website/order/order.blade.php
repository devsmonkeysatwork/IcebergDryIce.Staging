@extends('website.layouts.main')

<script>
    window.isLoggedIn = @json(Auth::guard('customer')->check());
    window.customerData = @json(Auth::guard('customer')->user());

</script>

@section('content')
    <style>

            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
                background-color: #f5f5f5;
            }

            th {
                background-color: #006;
                color: #FFF;
                font-weight: bold;
            }

            td {
                padding: 1px;
            }

            table {
                border-collapse: separate;
                margin: 0px auto;
            }



            .is_button {
                padding: 10px 20px;
                font-size: 16px;
                cursor: pointer;
                background-color: #f0f0f0;
            }

            .tab-content {
                display: none;
            }

            .tab-content.active {
                display: block;
            }

            .error {
                color: red;
                font-weight: bold;
            }

            .valid {
                color: green;
                font-weight: bold;
            }



            hr {
                margin: 10px 0;
            }

            /* Modal Styles */
            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
            }

            .modal-content {
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border: 2px solid #006;
                border-radius: 10px;
                width: 400px;
                max-width: 90%;
                text-align: center;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            }

            .modal-header {
                background-color: #006;
                color: white;
                padding: 15px;
                margin: -20px -20px 20px -20px;
                border-radius: 8px 8px 0 0;
                font-size: 18px;
                font-weight: bold;
            }

            .modal-buttons {
                margin-top: 20px;
            }

            .modal-button {
                padding: 12px 25px;
                margin: 0 10px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                font-weight: bold;
                min-width: 120px;
            }

            .signup-btn {
                background-color: #006;
                color: white;
                margin-bottom: 8px;
            }

            .signup-btn:hover {
                background-color: #004;
            }

            .guest-btn {
                background-color: #28a745;
                color: white;
            }

            .guest-btn:hover {
                background-color: #218838;
            }

            .modal-text {
                font-size: 16px;
                line-height: 1.5;
                margin: 15px 0;
            }

    </style>


    <body onload="document.getElementById('weight').focus();">

    <br>




    <center>
        <!-- Tab Navigation -->
        <table width='90%'>
            <tr>
                <td class='forsale_menu selected' id='tab-order' onclick='showTab("order")'>ORDER</td>
                <td class='forsale_menu' id='tab-location' onclick='showTab("location")'>Location</td>
                <td class='forsale_menu' id='tab-review' onclick='showTab("review")'>Review</td>
            </tr>
        </table>

        <br>

        <!-- ORDER TAB -->
        <div id="order-content" class="tab-content active">
            <form id='order_form'>
                <table>
                    <tr>
                        <th colspan='4'>ORDER</th>
                    </tr>
                    <tr>
                        <td>Dry Ice (lbs.)</td>
                        <td>
                            <input class='textbox_data' style='text-align: center;' size='10' type='text' name='weight' id='weight' value='0' onblur="calc_weight();">
                        </td>
                        <td width='130px' align='right'>
                            $ <input class='textbox_data' style='text-align: right;' readonly size='7' type='text' name='weight_cost' id='weight_cost' value='0.00'>
                        </td>
                        <td align='right' width='300px'>
                            <div id='weight_notes'><i>$1.95 / lbs., minimum 10 lbs.</i></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Styrofoam Box</td>
                        <td>
                            <input style='text-align: center;' class='textbox_data' size='10' type='text' name='box' id='box' value='0' onblur="calc_boxes();">
                        </td>
                        <td align='right'>
                            $ <input class='textbox_data' style='text-align: right;' readonly size='7' type='text' name='box_cost' id='box_cost' value='0.00'>
                        </td>
                        <td align='right'>
                            <div id='box_notes'><i>$30 / empty box, holds up to 50 lbs.</i></div>
                        </td>
                    </tr>
                </table>
                <br>
                <input style='width: 100px; text-align: center; color: #090; border: outset 2px #090;' class='is_button' type='button' value='NEXT' onclick='nextFromOrder();'>
            </form>
        </div>

        <!-- Signup/Guest Modal -->
        <div id="signupModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    Welcome
                </div>
                <div class="modal-text">
                    <p>Would you like to log in or sign up to access your account, or continue as a guest customer?</p>
                </div>
                <div class="modal-buttons">
                    <button class="modal-button signup-btn" onclick="redirectToLoginOrSignup()">
                        LOGIN / SIGN UP
                    </button>
                    <br>
                    <button class="modal-button guest-btn" onclick="proceedAsGuest()">
                        CONTINUE AS GUEST
                    </button>
                </div>
            </div>
        </div>


        <!-- LOCATION TAB -->
        <div id="location-content" class="tab-content">
            <form id='location_form'>
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
                        <td><input class='textbox_data' size='22' type='text' id='city' name='city'></td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td></td>
                        <td><input class='textbox_data' size='22' type='text' id='address' name='address'></td>
                        <td rowspan='4'>
                            <div id='address_notes'></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Postal</td>
                        <td></td>
                        <td><input class='textbox_data' size='22' type='text' id='postal' name='postal_code'></td>
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
                                    <option value="{{ $index + 1 }}" {{ date('n') == $index + 1 ? 'selected' : '' }}>{{ $month }}</option>
                                @endforeach
                            </select>
                            <select name="day" id='day' style='width: 80px;' class='textbox_data'>
                                @for ($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}" {{ date('j') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            <select name="year" id='year' style='width: 80px;' class='textbox_data'>
                                @for ($i = date('Y'); $i <= date('Y') + 3; $i++)
                                    <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
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
                        <td><input class='textbox_data' style='text-align: left;' size='22' type='text' name='name' id='name' value=''></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Location Type</td>
                        <td></td>
                        <td>
                            <select class='textbox_data' style='width: 215px;' id='location_type' onchange="set_name();">
                                <option value='2'>Company</option>
                                <option value='1'>Home Residence</option>
                            </select>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Location Name</td>
                        <td></td>
                        <td><input onblur='check_company_name();' class='textbox_data' size='22' type='text' id='company' name='company' value=''></td>
                        <td>
                            <div id='company_notes'></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td></td>
                        <td><input onblur="check_phone();" class='textbox_data' style='text-align: left;' size='22' type='text' name='phone' id='phone' value=''></td>
                        <td>
                            <div id='phone_notes'></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td></td>
                        <td><input onblur="check_email();" class='textbox_data' style='text-align: left;' size='22' type='text' name='email' id='email' value=''></td>
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
                    <input style='width: 100px; text-align: center; color: #090; border: outset 2px #090;' type="button" onclick="nextFromLocation()" value="NEXT" class="is_button">
                </center>
            </form>
            <center>
                <br><b>We deliver to the Greater Vancouver Regional District, and Calgary<br>
                    Deliveries require minimum 5 hour window. (If necessary, write in notes: "Deliver before 1pm" or "deliver after 12")</b>
            </center>
        </div>

        <!-- REVIEW TAB -->
        <div id="review-content" class="tab-content">
            <form id="orderForm" action="/order/ajax-create-from-review" method="POST">
                @csrf
                <!-- Hidden fields to store all order data - matching review controller validation -->
                <input type="hidden" id="hidden_customer_name" name="customer_name" value="">
                <input type="hidden" id="hidden_email" name="email" value="">
                <input type="hidden" id="hidden_phone" name="phone" value="">
                <input type="hidden" id="hidden_amount_of_ice" name="amount_of_ice" value="">
                <input type="hidden" id="hidden_amount_of_boxes" name="amount_of_boxes" value="">
                <input type="hidden" id="hidden_recurring" name="recurring" value="non-recurring">
                <input type="hidden" id="hidden_location_name" name="location_name" value="">
                <input type="hidden" id="hidden_address" name="address" value="">
                <input type="hidden" id="hidden_unit" name="unit" value="">
                <input type="hidden" id="hidden_city" name="city" value="">
                <input type="hidden" id="hidden_postal_code" name="postal_code" value="">
                <input type="hidden" id="hidden_province" name="province" value="">
                <input type="hidden" id="hidden_country" name="country" value="Canada">
                <input type="hidden" id="hidden_delivery_date" name="delivery_date" value="">
                <input type="hidden" id="hidden_notes" name="notes" value="">
                <input type="hidden" id="hidden_status" name="status" value="valid">
                <input type="hidden" id="hidden_pickup_delivery" name="pickup_delivery" value="delivery">
                <!-- Review-specific cost fields -->
                <input type="hidden" id="hidden_weight_cost" name="weight_cost" value="">
                <input type="hidden" id="hidden_box_cost" name="box_cost" value="">
                <input type="hidden" id="hidden_subtotal" name="subtotal" value="">
                <input type="hidden" id="hidden_delivery_cost" name="delivery_cost" value="5.00">
                <input type="hidden" id="hidden_tax" name="tax" value="">
                <input type="hidden" id="hidden_total_cost" name="total_cost" value="">

                <table>
                    <tr>
                        <th colspan='4'>Order</th>
                    </tr>
                    <tr>
                        <td width='300px' id='order-summary'>
                            <!-- Order summary will be populated by JavaScript -->
                        </td>
                        <td align='right' id='order-costs'>
                            <!-- Order costs will be populated by JavaScript -->
                        </td>
                    </tr>
                    <tr>
                        <td>Delivery</td>
                        <td align='right'>$5.00</td>
                    </tr>
                    <tr>
                        <td>
                            TAX<br>
                            <hr>
                            <b>TOTAL</b>
                        </td>
                        <td align='right' id='tax-total'>
                            <!-- Tax and total will be calculated -->
                        </td>
                    </tr>

                    <tr>
                        <th colspan='4'>Location</th>
                    </tr>
                    <tr>
                        <td colspan='4' id='delivery-location'>
                            <!-- Location details will be populated -->
                        </td>
                    </tr>

                    <tr>
                        <th colspan='4'>Date</th>
                    </tr>
                    <tr>
                        <td colspan='4' id='delivery-date'>
                            <!-- Date will be populated -->
                        </td>
                    </tr>

                    <tr>
                        <th colspan='4'>Contact Information</th>
                    </tr>
                    <tr>
                        <td colspan='4' id='contact-info'>
                            <!-- Contact info will be populated -->
                        </td>
                    </tr>

                    <tr>
                        <th colspan='4'>Notes</th>
                    </tr>
                    <tr>
                        <td colspan='4' id='order-notes'>
                            <!-- Notes will be populated -->
                        </td>
                    </tr>
                </table>

                <div class="safety-disclaimer" style="text-align: left">
                    <h1>Safety</h1>
                    <p>1. Never touch dry ice with bare skin.</p>
                    <p>2. Never seal dry ice in an airtight container.</p>
                    <p>3. Never let dry ice vapor fill a room.</p>

                    <h1>Disclaimer</h1>
                    <p>1. Same day deliveries must be scheduled by 8:30am.</p>
                    <p>2. Couriers need a 5-hour window for delivery.</p>
                    <p>3. We are not open on holidays.</p>
                    <p>4. You confirm you have provided an accurate address and contact information.</p>
                    <p>5. You confirm you will be available to accept the delivery.</p>
                    <p><i>Due to the nature of dry ice, we cannot accept returns or offer refunds once the delivery is en route.</i></p>
                    <hr>
                </div>

                <div class="submit-section">
                    <div class="checkbox-section">
                        <b>
                            <input type="checkbox" id="accept" required name="accept">
                            I have read and understand the above safety information and disclaimer.
                        </b>
                    </div>
                    <input type="image"
                           src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif"
                           alt="Buy Now with PayPal"
                           class="submit-btn" style="margin-top: 3px;">
                </div>
            </form>
        </div>
    </center>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        function redirectToLoginOrSignup() {
            const currentUrl = window.location.pathname; // e.g., "/order"
            window.location.href = `/login?redirect=${encodeURIComponent(currentUrl)}`;
        }

        // Global variables
        var cost_per_pound = 1.95;
        var cost_per_box = 30;
        var minimum_pounds = 10;
        var currentTab = 'order';

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            populateDays();
            document.getElementById('weight').focus();
        });

        function populateCustomerData() {
            if (window.isLoggedIn && window.customerData) {
                const customer = window.customerData;

                // Populate form fields with customer data
                if (customer.name) document.getElementById('name').value = customer.name;
                if (customer.email) document.getElementById('email').value = customer.email;
                if (customer.phone) document.getElementById('phone').value = customer.phone;
                if (customer.address) document.getElementById('address').value = customer.address;
                if (customer.city) document.getElementById('city').value = customer.city;
                if (customer.postal_code) document.getElementById('postal').value = customer.postal_code;
                if (customer.province) document.getElementById('province').value = customer.province;
            }
        }
        // Tab switching function
        function showTab(tabName) {
            console.log('Switching to tab:', tabName);

            // Hide all tab contents
            var contents = document.querySelectorAll('.tab-content');
            contents.forEach(function(content) {
                content.classList.remove('active');
            });

            // Remove selected class from all tabs
            var tabs = document.querySelectorAll('.forsale_menu');
            tabs.forEach(function(tab) {
                tab.classList.remove('selected');
            });

            // Show selected tab content
            var targetContent = document.getElementById(tabName + '-content');
            var targetTab = document.getElementById('tab-' + tabName);

            if (targetContent && targetTab) {
                targetContent.classList.add('active');
                targetTab.classList.add('selected');
                currentTab = tabName;

                // Populate customer data when showing location tab
                if (tabName === 'location') {
                    populateCustomerData();
                }

                // If showing review tab, populate it
                if (tabName === 'review') {
                    populateReview();
                }
            } else {
                console.error('Tab elements not found:', tabName);
            }
        }
        // Navigation functions
        function nextFromOrder() {
            if (calc_weight() && calc_boxes()) {
                if (document.getElementById('weight').value == 0 && document.getElementById('box').value == 0) {
                    set_msg('weight_notes', 'Please add something to your order', true);
                } else {
                    // Check if user is logged in
                    if (window.isLoggedIn) {
                        // Skip modal and go directly to location
                        showTab('location');
                    } else {
                        // Show the signup/guest modal
                        document.getElementById('signupModal').style.display = 'block';
                    }
                }
            }
        }

        function proceedWithSignup() {
            // Hide modal
            document.getElementById('signupModal').style.display = 'none';
            alert('Signup functionality would be implemented here. Proceeding to location for now.');
            showTab('location');
        }

        function proceedAsGuest() {
            // Hide modal and proceed to location tab
            document.getElementById('signupModal').style.display = 'none';
            showTab('location');
        }

        // Close modal if user clicks outside of it
        window.onclick = function(event) {
            var modal = document.getElementById('signupModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        function nextFromLocation() {
            var addressError = check_address();
            var checkDate = check_date();
            var companyError = check_company_name();
            var contactError = check_contact();
            var phoneError = check_phone();
            var emailError = check_email();

            if (!(checkDate || addressError || companyError || contactError || phoneError || emailError)) {
                showTab('review');
            }
        }

        // Order calculation functions
        function calc_weight() {
            set_msg('weight_notes', '<center><b>VALID</b></center>', false);
            var weight = document.getElementById('weight').value;
            if (isNaN(weight) || weight < minimum_pounds) {
                set_msg('weight_notes', 'Minimum order weight is 10 pounds.', true);
                return false;
            }
            document.getElementById('weight_cost').value = (weight * cost_per_pound).toFixed(2);
            return true;
        }

        function calc_boxes() {
            set_msg('box_notes', '<center><b>VALID</b></center>', false);
            var boxes = document.getElementById('box').value;
            if (isNaN(boxes)) {
                set_msg('box_notes', 'Boxes must be a number.', true);
                return false;
            }
            document.getElementById('box_cost').value = (boxes * cost_per_box).toFixed(2);
            return true;
        }

        // Location validation functions
        function check_date() {
            var year = parseInt(document.getElementById('year').value);
            var month = parseInt(document.getElementById('month').value) - 1;
            var day = parseInt(document.getElementById('day').value);
            var error_msg = '';

            var date = new Date(year, month, day);
            var today = new Date();
            var now = new Date();

            var hour_now = now.getHours();
            var min_now = now.getMinutes();

            today.setHours(0, 0, 0, 0);
            var tomorrow = new Date(today.getTime() + 24 * 60 * 60 * 1000);

            if ([0, 6].includes(date.getDay())) {
                error_msg += 'Week days only please.<br>';
            }

            if (
                date.getFullYear() === today.getFullYear() &&
                date.getMonth() === today.getMonth() &&
                date.getDate() === today.getDate()
            ) {
                if ((hour_now === 8 && min_now > 30) || hour_now > 8) {
                    error_msg += 'Same day orders must be completed before 8:30am.<br>';
                }
            } else if (date < tomorrow) {
                error_msg += 'Orders must be completed before 8:30am day of.<br>';
            }

            if (error_msg === '') {
                set_msg('date_notes', '<center><b>VALID</b></center>', false);
            } else {
                set_msg('date_notes', error_msg, true);
            }

            return error_msg;
        }

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
            var error_msg = '';
            if (document.getElementById('email').value === '' && document.getElementById('phone').value === '')
                error_msg += 'Please enter an email or phone number so we can contact you if there is any trouble with your order.';
            set_msg('phone_notes', error_msg, true);
            return error_msg;
        }

        function check_phone() {
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
            if (document.getElementById('location_type').value == 1) {
                document.getElementById('company').value = 'Residence';
            }
        }

        // Utility functions
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


        function populateReview() {
            const getValue = (id) => document.getElementById(id)?.value.trim() || '';

            const weight = getValue('weight');
            const boxes = getValue('box');
            const weightCost = parseFloat(getValue('weight_cost')) || 0;
            const boxCost = parseFloat(getValue('box_cost')) || 0;
            const subtotal = weightCost + boxCost;
            const delivery = 5.00;
            const tax = (subtotal + delivery) * 0.12;
            const total = subtotal + delivery + tax;

            // Order Summary
            let orderSummary = '';
            let orderCosts = '';

            if (weight > 0) {
                orderSummary += `<font color="#00f"><b>${weight}</b></font> lbs. of DRY ICE<br>`;
                orderCosts += `$${weightCost.toFixed(2)}<br>`;
            }

            if (boxes > 0) {
                orderSummary += `<font color="#00f"><b>${boxes}</b></font> styrofoam boxes<br>`;
                orderCosts += `$${boxCost.toFixed(2)}<br>`;
            }

            orderSummary += '<b>Sub Total</b>';
            orderCosts += `$${subtotal.toFixed(2)}`;

            document.getElementById('order-summary').innerHTML = orderSummary;
            document.getElementById('order-costs').innerHTML = orderCosts;

            document.getElementById('tax-total').innerHTML =
                `$${tax.toFixed(2)}<br><hr><b>$${total.toFixed(2)}</b>`;

            // Location
            const address = getValue('address');
            const city = getValue('city');
            const province = getValue('province');
            const postal = getValue('postal');
            const company = getValue('company');

            let locationHtml = '';
            if (company && company !== 'Residence') locationHtml += `<b>${company}</b><br>`;
            if (address) locationHtml += `${address}<br>`;
            if (city || province || postal) {
                locationHtml += city;
                if (province) locationHtml += `, ${province}`;
                if (postal) locationHtml += ` ${postal}`;
            }

            document.getElementById('delivery-location').innerHTML = locationHtml || 'No address provided';

            // Delivery Date
            const month = getValue('month');
            const day = getValue('day');
            const year = getValue('year');
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"];
            const dateHtml = (month && day && year)
                ? `${monthNames[month - 1]} ${day}, ${year}`
                : 'No date selected';

            document.getElementById('delivery-date').innerHTML = dateHtml;

            // Contact
            const name = getValue('name');
            const phone = getValue('phone');
            const email = getValue('email');

            let contactHtml = '';
            if (name) contactHtml += `<b>${name}</b><br>`;
            if (phone) contactHtml += `Phone: ${phone}<br>`;
            if (email) contactHtml += `Email: ${email}`;
            if (!contactHtml) contactHtml = 'No contact information provided';

            document.getElementById('contact-info').innerHTML = contactHtml;

            // Notes
            const notes = getValue('notes');
            document.getElementById('order-notes').innerHTML = notes || 'No special notes';

            // Hidden fields
            document.getElementById('hidden_customer_name').value = name;
            document.getElementById('hidden_email').value = email;
            document.getElementById('hidden_phone').value = phone;
            document.getElementById('hidden_amount_of_ice').value = weight;
            document.getElementById('hidden_amount_of_boxes').value = boxes;
            document.getElementById('hidden_location_name').value = company;
            document.getElementById('hidden_address').value = address;
            document.getElementById('hidden_unit').value = getValue('unit');
            document.getElementById('hidden_city').value = city;
            document.getElementById('hidden_postal_code').value = postal;
            document.getElementById('hidden_province').value = province;
            document.getElementById('hidden_notes').value = notes;

            document.getElementById('hidden_weight_cost').value = weightCost.toFixed(2);
            document.getElementById('hidden_box_cost').value = boxCost.toFixed(2);
            document.getElementById('hidden_subtotal').value = subtotal.toFixed(2);
            document.getElementById('hidden_tax').value = tax.toFixed(2);
            document.getElementById('hidden_total_cost').value = total.toFixed(2);

            // Delivery date hidden value
            const deliveryDate = (month && day && year)
                ? `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
                : '';
            document.getElementById('hidden_delivery_date').value = deliveryDate;
        }


        // Handle form submission with AJAX
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate required checkbox
            if (!document.getElementById('accept').checked) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required',
                    text: 'Please accept the safety information and disclaimer before submitting.'
                });
                return;
            }

            // Prepare form data
            const formData = new FormData(this);

            const submitBtn = document.querySelector('.submit-btn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Submitting...';
            submitBtn.disabled = true;

            fetch('/order/ajax-create-from-review', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Order Submitted!',
                            text: `Order ID: ${data.order_id}`
                        }).then(() => {
                            //Redirect if needed
                            window.location.href = `/`;
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Submission Failed',
                            text: data.message || 'Unknown error occurred.'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'A network error occurred. Please try again.'
                    });
                })
                .finally(() => {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
        });

        // Populate the review when page loads
        document.addEventListener('DOMContentLoaded', function() {
            populateReview();
        });


    </script>



    </body>
@endsection
