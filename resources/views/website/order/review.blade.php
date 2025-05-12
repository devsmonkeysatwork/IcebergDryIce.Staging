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
                <td class='forsale_menu'><a href="{{ url('/location') }}">Location</a></td>
                <td class='forsale_menu selected'>Review</td>
            </tr>
        </table>

        <br>
        <table>
            <tr>
                <th colspan='4'>Order</th>
            </tr>
            <tr>
                <td width='300px'>
                    <font color='#00f'><b>10</b></font> lbs. of DRY ICE<br>
                    <font color='#00f'><b>5</b></font> styrofoam boxes<br>
                    <b>Sub Total</b>
                </td>
                <td align='right'>
                    $20.00<br>
                    $15.00<br>
                    $35.00
                </td>
            </tr>
            <tr>
                <td>
                    Delivery
                </td>
                <td align='right'>
                    $5.00
                </td>
            </tr>
            <tr>
                <td>
                    TAX<br>
                    <hr>
                    <b>TOTAL</b>
                </td>
                <td align='right'>
                    $3.50<br>
                    <hr>
                    <b>$43.50</b>
                </td>
            </tr>

            <tr>
                <th colspan='4'>Location</th>
            </tr>
            <tr>
                <td>
                    <b>PICKUP</b> - Oct 27, 28, 31<br>
                    #106 - 3011 Underhill Ave., Burnaby
                </td>
            </tr>

            <tr>
                <th colspan='4'>Date</th>
            </tr>
            <tr>
                <td colspan='4'>
                    October 27, 2025
                </td>
            </tr>

            <tr>
                <th colspan='4'>Contact Information</th>
            </tr>
            <tr>
                <td colspan='4'>
                    John Doe<br>
                    (123) 456-7890<br>
                    johndoe@example.com
                </td>
            </tr>

            <tr>
                <th colspan='4'>Notes</th>
            </tr>
        </table>

        <center>
            <div style="text-align: left;">
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

            <form action="https://checkout.e-xact.com/payment" method="post">
                @csrf
                <input type="hidden" name='x_login' value="WSP-ICEBE-DUMMY" />
                <input type="hidden" name='x_fp_sequence' value="123456789" />
                <input type="hidden" name='x_fp_timestamp' value="{{ time() }}" />
                <input type="hidden" name='x_fp_hash' value="DUMMYHASH123" />
                <input type="hidden" name='x_currency_code' value="CAD" />
                <input type="hidden" name='x_amount' value="43.50" />
                <input type="hidden" name='x_invoice_num' value="INV-12345" />
                <input type="hidden" name='x_po_num' value="2" />
                <input type="hidden" name='x_show_form' value='PAYMENT_FORM' />
                <input type="hidden" name='x_test_request' value='TRUE' />

                @php
                    $color = '#666';
//                    if (session()->has('cc_order') && !session('cc_order')->terms) {
//                        $color = '#f00';
//                    }
                @endphp

                <font color="{{ $color }}">
                    <b>
                        <input type="checkbox" id="accept" required name="accept">
{{--                               @if(session()->has('cc_order') && session('cc_order')->terms) checked @endif>--}}
                        I have read and understand the above safety information and disclaimer.
                    </b>
                </font>
                <br>

                <td colspan="4">
{{--                    {{ session('cc_order')->notes ?? '' }}--}}

{{--                    @if(session()->has('cc_order') && !session('cc_order')->terms)--}}
{{--                        <br>--}}
{{--                        <font color="#f00">--}}
{{--                            <center>TERMS and DISCLAIMER NOT ACCEPTED YET</center>--}}
{{--                        </font>--}}
{{--                    @endif--}}
                </td>
                <input type="image" src="//www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Make payments with PayPal - its fast, free and secure!">
            </form>
        </center>
    </center>

@endsection
