<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consolidated Invoice</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Inter:400,600,700');

        body {
            margin: 0;
            padding: 40px 0;
            -webkit-font-smoothing: antialiased;
            font-family: 'Inter', sans-serif;
        }
        table { border-collapse: collapse; }
        p { margin: 0; }
        .fullTable { width: 600px; }

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
                        <p style="font-size: 36px; font-weight: 700; color:#B2B7C2; margin-top:10px;">Invoice</p>
                        <p style="font-size: 12px; color:#5E6470;">Invoice Number: <strong>{{ $invoice->invoice_number }}</strong></p>
                        <p style="font-size: 12px; color:#5E6470;">Customer Number: <strong>{{ $customer->id ?? 'N/A' }}</strong></p>
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
                        <p style="font-weight: 600;font-size: 10px;color:#1A1C21;">{{ $customer->name ?? 'N/A' }}</p>
                        <p style="font-size: 10px;color:#5E6470;">{{ $customer->email ?? '' }}</p>
                        <p style="font-size: 10px;color:#5E6470;">{{ $customer->phone ?? '' }}</p>
                    </td>
                    <td style="vertical-align: top;">
                        <p style="font-size: 10px;color:#5E6470;">Invoice Date</p>
                        <p style="font-weight: 600;font-size: 10px;color:#1A1C21;">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d.m.Y') }}</p>
                    </td>
                    <td style="vertical-align: top;text-align:right;">
                        <p style="font-size: 10px;color:#5E6470;">Amount</p>
                        <p style="background-color:#0B75AF;color:#fff;font-weight:700;font-size:20px;padding:5px 10px;display:inline-block;border-radius:4px;">
                            ${{ number_format($totalAmount, 2) }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Line Items Table -->
<table align="center" class="fullTable" bgcolor="#ffffff">
    <tr>
        <td style="padding: 20px;">
            <table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-color:#D7DAE0;">
                <thead style="background-color:#f8f9fa;">
                <tr>
                    <th style="font-size: 9px; color:#5E6470; text-align:left;">Date</th>
                    <th style="font-size: 9px; color:#5E6470; text-align:left;">PO#</th>
                    <th style="font-size: 9px; color:#5E6470; text-align:center;">Quantity</th>
                    <th style="font-size: 9px; color:#5E6470; text-align:left;">Item</th>
                    <th style="font-size: 9px; color:#5E6470; text-align:center;">Price/Unit</th>
                    <th style="font-size: 9px; color:#5E6470; text-align:right;">Product Cost</th>
                    <th style="font-size: 9px; color:#5E6470; text-align:right;">GST</th>
                    <th style="font-size: 9px; color:#5E6470; text-align:right;">PST</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lineItems as $item)
                    <tr>
                        <td style="font-size: 10px; color:#1A1C21;">{{ $item->delivery_date ? \Carbon\Carbon::parse($item->delivery_date)->format('M d, y') : '' }}</td>
                        <td style="font-size: 10px; color:#1A1C21;">{{ $item->order->po ?? '' }}</td>
                        <td style="font-size: 10px; color:#1A1C21; text-align:center;">{{ $item->quantity }}</td>
                        <td style="font-size: 10px; color:#1A1C21;">{{ $item->description }}</td>
                        <td style="font-size: 10px; color:#1A1C21; text-align:center;">${{ number_format($item->unit_price, 2) }}</td>
                        <td style="font-size: 10px; color:#1A1C21; text-align:right;">${{ number_format($item->total_price, 2) }}</td>
                        <td style="font-size: 10px; color:#1A1C21; text-align:right;">${{ number_format($item->gst, 2) }}</td>
                        <td style="font-size: 10px; color:#1A1C21; text-align:right;">${{ number_format($item->pst, 2) }}</td>
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
                    <td style="font-size:10px;font-weight:700;color:#1A1C21;">Total Product Cost</td>
                    <td style="font-size:10px;font-weight:700;color:#1A1C21;text-align:right;">${{ number_format($subTotal, 2) }}</td>
                </tr>
                @foreach($flatCharges as $charge)
                    <tr>
                        <td style="font-size:10px;font-weight:700;color:#1A1C21;">
                            {{ $charge->label ?? \Illuminate\Support\Str::headline($charge->charge_key) }}
                        </td>
                        <td style="font-size:10px;font-weight:700;color:#1A1C21;text-align:right;">
                            {{ $charge->amount < 0 ? '-' : '' }}${{ number_format(abs($charge->amount), 2) }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td style="font-size:10px;font-weight:700;color:#1A1C21;">GST Tax</td>
                    <td style="font-size:10px;font-weight:700;color:#1A1C21;text-align:right;">${{ number_format($gstTotal, 2) }}</td>
                </tr>
                <tr>
                    <td style="font-size:10px;font-weight:700;color:#1A1C21;">PST Tax</td>
                    <td style="font-size:10px;font-weight:700;color:#1A1C21;text-align:right;">${{ number_format($pstTotal, 2) }}</td>
                </tr>
                <tr>
                    <td style="font-size:12px;font-weight:700;color:#1A1C21;border-top:1px solid #D7DAE0;padding-top:6px;">Invoice Total</td>
                    <td style="font-size:12px;font-weight:700;color:#1A1C21;text-align:right;border-top:1px solid #D7DAE0;padding-top:6px;">${{ number_format($totalAmount, 2) }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@if(!empty($invoice->notes))
<!-- Notes -->
<table align="center" class="fullTable" bgcolor="#ffffff">
    <tr>
        <td style="padding: 20px;">
            <p style="font-size:8px; font-weight:600; color:#5E6470;">Notes</p>
            <p style="border-top:1px solid #D7DAE0; margin:4px 0;"></p>
            <p style="font-size:10px;color:#5E6470;white-space:pre-line;">{{ $invoice->notes }}</p>
        </td>
    </tr>
</table>
@endif

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
