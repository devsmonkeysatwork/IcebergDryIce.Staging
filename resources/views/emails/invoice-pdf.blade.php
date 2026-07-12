<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Invoice</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Inter:400,600,700');

        body {
            margin: 0;
            padding: 40px 0;
            -webkit-font-smoothing: antialiased;
            font-family: 'Inter', sans-serif;
        }

        table {
            border-collapse: collapse;
        }

        p {
            margin: 0;
        }

        .fullTable {
            width: 600px;
        }

        @media only screen and (max-width: 600px) {
            .fullTable { width: 96% !important; }
        }

        @media only screen and (max-width: 420px) {
            .fullTable { width: 100% !important; }
        }
    </style>
</head>

<body>

<!-- Header -->
<table align="center" class="fullTable" bgcolor="#ffffff" style="border-radius: 10px 10px 0 0;">
    <tr>
        <td style="padding: 20px;">
            <table width="100%">
                <tr>
                    <td style="width:55px;vertical-align: top;">
                        <img src="{{asset('idi_logo.png')}}" width="54" height="54" alt="logo" />
                    </td>
                    <td>
                        <p style="font-weight: 700;font-size: 18px;color:#1A1C21;">Iceberg Dry Ice</p>
                        <p style="font-size: 10px;color:#5E6470;">329 Churchill Avenue</p>
                        <p style="font-size: 10px;color:#5E6470;">New Westminster BC V3L 4P5</p>
                        <p style="font-size: 10px;color:#5E6470;">Orders 604-524-0609</p>
                        <p style="font-size: 10px;color:#5E6470;">Office 604-524-0601</p>
                    </td>
                    <td style="vertical-align: top; text-align: right;">
{{--                        @if($order->payment_status)--}}
{{--                            <p style="background-color: #28A745; border-radius: 25px; font-weight: 600; font-size: 14px; color: #000; width: 80px; text-align: center; padding: 5px 0;">PAID</p>--}}
{{--                        @else--}}
{{--                            <p style="background-color: #FFC107; border-radius: 25px; font-weight: 600; font-size: 14px; color: #000; width: 80px; text-align: center; padding: 5px 0;">UNPAID</p>--}}
{{--                        @endif--}}
                        <p style="font-size: 36px; font-weight: 700; color:#B2B7C2; margin-top:10px;">Invoice</p>
                        <p style="font-size: 12px; color:#5E6470;">#{{ $invoice_number ?? 'N/A' }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Billing Info -->
<table align="center" class="fullTable" bgcolor="#ffffff">
    <tr>
        <td style="padding: 20px;">
            <table width="100%">
                <tr>
                    <td style="vertical-align: top;">
                        <p style="font-size: 10px;color:#5E6470;">Billed to</p>
                        <p style="font-weight: 600;font-size: 10px;color:#1A1C21;">{{ $order->customer_name }}</p>
                        <p style="font-size: 10px;color:#5E6470;">{{ $order->address }}{{ $order->unit ? ', ' . $order->unit : '' }}</p>
                        <p style="font-size: 10px;color:#5E6470;">{{ $order->city }}, {{ $order->country }} - {{ $order->postal_code }}</p>
                        <p style="font-size: 10px;color:#5E6470;">{{ $order->phone }}</p>
                    </td>
                    <td style="vertical-align: top;">
                        <p style="font-size: 10px;color:#5E6470;">Invoice Date</p>
                        <p style="font-weight: 600;font-size: 10px;color:#1A1C21;">{{ $order->created_at->format('d.m.Y') }}</p>
{{--                        <p style="margin-top:10px;font-size: 10px;color:#5E6470;">Due Date</p>--}}
{{--                        <p style="font-weight: 600;font-size: 10px;color:#1A1C21;">{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d.m.Y') : 'N/A' }}</p>--}}
                    </td>
                    <td style="vertical-align: top;text-align:right;">
                        <p style="font-size: 10px;color:#5E6470;">Amount</p>
                        <p style="background-color:#0B75AF;color:#fff;font-weight:700;font-size:20px;padding:5px 10px;display:inline-block;border-radius:4px;">
                            ${{ number_format($order->total_cost, 2) }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Items Table -->
<table align="center" class="fullTable" bgcolor="#ffffff">
    <tr>
        <td style="padding: 20px;">
            <table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-color:#D7DAE0;">
                <thead style="background-color:#f8f9fa;">
                <tr>
                    <th style="font-size: 9px; color:#5E6470; text-align:left;">#</th>
                    <th style="font-size: 9px; color:#5E6470; text-align:left;">Item</th>
                    <th style="font-size: 9px; color:#5E6470; text-align:center;">Unit Price</th>
                    <th style="font-size: 9px; color:#5E6470; text-align:center;">Quantity</th>
                    <th style="font-size: 9px; color:#5E6470; text-align:right;">Subtotal</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->items as $index => $item)
                    <tr>
                        <td style="font-size: 10px; color:#1A1C21;">{{ $index + 1 }}</td>
                        <td style="font-size: 10px; color:#1A1C21;">{{ $item->product->product_name ?? 'Product' }}</td>
                        <td style="font-size: 10px; color:#1A1C21; text-align:center;">${{ number_format($item->unit_price, 2) }}</td>
                        <td style="font-size: 10px; color:#1A1C21; text-align:center;">{{ $item->amount_of_items }} {{ $item->product->unit }}</td>
                        <td style="font-size: 10px; color:#1A1C21; text-align:right;">${{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </td>
    </tr>
</table>

<!-- Totals -->
<table align="center" class="fullTable" bgcolor="#ffffff">
    <tr>
        <td style="padding: 20px;">
            <table width="100%">
                <tr>
                    <td style="font-size:10px;font-weight:700;color:#1A1C21;">Subtotal</td>
                    <td style="font-size:10px;font-weight:700;color:#1A1C21;text-align:right;">${{ number_format($order->sub_total, 2) }}</td>
                </tr>
                @if($order->delivery_cost > 0)
                    <tr>
                        <td style="font-size:10px;font-weight:700;color:#1A1C21;">Delivery Cost</td>
                        <td style="font-size:10px;font-weight:700;color:#1A1C21;text-align:right;">${{ number_format($order->delivery_cost, 2) }}</td>
                    </tr>
                @endif
                <tr>
                    <td style="font-size:10px;font-weight:700;color:#1A1C21;">Tax</td>
                    <td style="font-size:10px;font-weight:700;color:#1A1C21;text-align:right;">${{ number_format($order->tax, 2) }}</td>
                </tr>
                <tr>
                    <td style="font-size:10px;font-weight:700;color:#1A1C21;">Hazmat Fee</td>
                    <td style="font-size:10px;font-weight:700;color:#1A1C21;text-align:right;">${{ number_format($order->hazmat, 2) }}</td>
                </tr>
                <tr>
                    <td style="font-size:10px;font-weight:700;color:#1A1C21;">Total</td>
                    <td style="font-size:10px;font-weight:700;color:#1A1C21;text-align:right;">${{ number_format($order->total_cost, 2) }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Terms -->
<table align="center" class="fullTable" bgcolor="#ffffff">
    <tr>
        <td style="padding: 20px;">
            <p style="font-size:8px; font-weight:600; color:#5E6470;">Terms</p>
            <p style="border-top:1px solid #D7DAE0; margin:4px 0;"></p>
            <p style="font-size:10px;color:#5E6470;">
                Net 15 days, interest of 2% per month (24% per annum) charged on all overdue accounts.
            </p>
            <p style="font-size:10px;color:#5685e8;font-weight: 700;">
                All totes must be returned within two weeks of delivery unless arrangements have
                been made with Iceberg. Late fees will apply.
            </p>
            @if($order->notes)
                <p style="margin-top:10px;font-size:8px;font-weight:600;color:#5E6470;">Notes</p>
                <p style="border-top:1px solid #D7DAE0; margin:4px 0;"></p>
                <p style="font-size:10px;color:#5E6470;">{{ $order->notes }}</p>
            @endif
            <p style="margin-top:20px;font-size:10px;color:#5E6470;">Thank you for your business!</p>
        </td>
    </tr>
</table>

<!-- Business Info -->
<table align="center" class="fullTable" bgcolor="#ffffff" style="border-radius:0 0 10px 10px;">
    <tr>
        <td style="padding: 20px;">
            <p style="font-size:8px;font-weight:600;color:#5E6470;">Business Information</p>
            <p style="border-top:1px solid #D7DAE0;margin:4px 0;"></p>
            <table width="100%">
                <tr>
                    <td style="width:50%;">
                        <p style="font-size:8px;font-weight:600;color:#1A1C21;">GST #</p>
                        <p style="font-size:8px;color:#5E6470;">82379 8541</p>
                    </td>
                    <td>
                        <p style="font-size:8px;font-weight:600;color:#1A1C21;">Contact</p>
                        <p style="font-size:8px;color:#5E6470;text-decoration:underline;">admin@icebergdryice.com</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>
