<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> Order Invoice </title>
    <meta name="robots" content="noindex,nofollow" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0;" />
    <style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=Inter:400,700);
        body { margin: 0; padding: 0; background: #e1e1e1; }
        div, p, a, li, td { -webkit-text-size-adjust: none; }
        .ReadMsgBody { width: 100%; background-color: #ffffff; }
        .ExternalClass { width: 100%; background-color: #ffffff; }
        body { width: 100%; height: 100%; background-color: #e1e1e1; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        html { width: 100%; }
        p { padding: 0 !important; margin-top: 0 !important; margin-right: 0 !important; margin-bottom: 0 !important; margin-left: 0 !important; }
        .visibleMobile { display: none; }
        .hiddenMobile { display: block; }

        @media only screen and (max-width: 600px) {
            body { width: auto !important; }
            table[class=fullTable] { width: 96% !important; clear: both; }
            table[class=fullPadding] { width: 85% !important; clear: both; }
            table[class=col] { width: 45% !important; }
            .erase { display: none; }
        }

        @media only screen and (max-width: 420px) {
            table[class=fullTable] { width: 100% !important; clear: both; }
            table[class=fullPadding] { width: 85% !important; clear: both; }
            table[class=col] { width: 100% !important; clear: both; }
            table[class=col] td { text-align: left !important; }
            .erase { display: none; font-size: 0; max-height: 0; line-height: 0; padding: 0; }
            .visibleMobile { display: block !important; }
            .hiddenMobile { display: none !important; }
        }
    </style>
</head>
<body>

<!-- Header -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">
    <tr>
        <td height="20"></td>
    </tr>
    <tr>
        <td>
            <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff" style="border-radius: 10px 10px 0 0;">
                <tr class="hiddenMobile">
                    <td height="40"></td>
                </tr>
                <tr class="visibleMobile">
                    <td height="30"></td>
                </tr>

                <tr>
                    <td>
                        <table width="80%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                            <tbody>
                            <tr>
                                <td>
                                    <table width="480">
                                        <tbody>
                                        <tr>
                                            <td align="left" style="width:55px;vertical-align: top;">
                                                <img src="http://www.supah.it/dribbble/017/logo.png" width="48" height="48" alt="logo" border="0" />
                                            </td>
                                            <td align="left">
                                                <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;">Panda, Inc</p>
                                                <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;">www.website.com</p>
                                                <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;">hello@email.com</p>
                                                <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;">+91 00000 00000</p>
                                            </td>
                                            <td>
                                                <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;text-align: right;">Business address</p>
                                                <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;text-align: right;">City, State, IN - 000 000</p>
                                                <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;text-align: right;">TAX ID 00XXXXX1234X0XX</p>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- /Header -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">
    <tbody>
    <tr>
        <td>
            <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff">
                <tbody>
                <tr class="hiddenMobile">
                    <td height="30"></td>
                </tr>
                <tr class="visibleMobile">
                    <td height="15"></td>
                </tr>
                <tr>
                    <td>
                        <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff">
                            <tbody>
                            <tr>
                                <td style="vertical-align: top;">
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">Billed to</p>
                                    <p style="font-family: Inter;font-weight: 600;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#1A1C21;">{{ $order->customer_name }}</p>
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">{{ $order->address }}</p>
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">{{ $order->city }}, {{ $order->country }} - {{ $order->postal_code }}</p>
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">{{ $order->phone }}</p>
                                </td>
                                <td style="vertical-align: top;">
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">Invoice number</p>
                                    <p style="font-family: Inter;font-weight: 600;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#1A1C21;">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;margin-top:15px !important;">Reference</p>
                                    <p style="font-family: Inter;font-weight: 600;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#1A1C21;">INV-{{ $order->id }}</p>
                                </td>
                                <td style="vertical-align: top;text-align:right;">
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">Invoice of (USD)</p>
                                    <p style="font-family: Inter;font-weight: 700;font-size: 20px;line-height: 28px;letter-spacing: 0%;color:#E87117;">${{ number_format($order->total_cost, 2) }}</p>
                                </td>
                            </tr>
                            <tr class="hiddenMobile">
                                <td height="20"></td>
                            </tr>
                            <tr class="visibleMobile">
                                <td height="10"></td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">Subject </p>
                                    <p style="font-family: Inter;font-weight: 600;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#1A1C21;">Ice Delivery Service</p>
                                </td>
                                <td style="vertical-align: top;">
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">Invoice date</p>
                                    <p style="font-family: Inter;font-weight: 600;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#1A1C21;">{{ date('d M, Y', strtotime($order->created_at)) }}</p>
                                </td>
                                <td style="vertical-align: top;text-align:right;">
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">Due date</p>
                                    <p style="font-family: Inter;font-weight: 600;font-size: 10px;line-height: 28px;letter-spacing: 0%;color:#1A1C21;">{{ date('d M, Y', strtotime($order->delivery_date)) }}</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff">
                <tbody>
                <tr>
                <tr class="hiddenMobile">
                    <td height="60"></td>
                </tr>
                <tr class="visibleMobile">
                    <td height="40"></td>
                </tr>
                <tr>
                    <td>
                        <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                            <tbody>
                            <tr>
                                <th style="font-size: 12px; font-family: Inter; color: #5b5b5b; font-weight: normal; line-height: 1; vertical-align: top; padding: 0 10px 7px 0;" width="52%" align="left">
                                    Item
                                </th>
                                <th style="font-size: 12px; font-family: Inter; color: #5b5b5b; font-weight: normal; line-height: 1; vertical-align: top; padding: 0 0 7px;" align="center">
                                    Quantity
                                </th>
                                <th style="font-size: 12px; font-family: Inter; color: #1e2b33; font-weight: normal; line-height: 1; vertical-align: top; padding: 0 0 7px;" align="right">
                                    Rate
                                </th>
                            </tr>
                            <tr>
                                <td height="1" style="background: #bebebe;" colspan="4"></td>
                            </tr>
                            <tr>
                                <td height="10" colspan="4"></td>
                            </tr>
                            @foreach($order->items ?? [] as $item)
                                <tr>
                                    <td style="font-size: 12px; font-family: Inter; color: #ff0000;  line-height: 18px;  vertical-align: top; padding:10px 0;" class="article">{{$item->product->product_name}}</td>
                                    <td style="font-size: 12px; font-family: Inter; color: #646a6e;  line-height: 18px;  vertical-align: top; padding:10px 0;" align="center">{{ $item->amount_of_items }} {{$item->product->unit}}</td>
                                    <td style="font-size: 12px; font-family: Inter; color: #1e2b33;  line-height: 18px;  vertical-align: top; padding:10px 0;" align="right">${{ number_format($item->unit_price,2) }}</td>
                                </tr>
                                <tr>
                                    <td height="1" colspan="4" style="border-bottom:1px solid #e4e4e4"></td>
                                </tr>
                            @endforeach
{{--                            <tr>--}}
{{--                                <td style="font-size: 12px; font-family: Inter; color: #ff0000;  line-height: 18px;  vertical-align: top; padding:10px 0;" class="article">Dry Ice</td>--}}
{{--                                <td style="font-size: 12px; font-family: Inter; color: #646a6e;  line-height: 18px;  vertical-align: top; padding:10px 0;" align="center">{{ $order->amount_of_ice }} lbs</td>--}}
{{--                                <td style="font-size: 12px; font-family: Inter; color: #1e2b33;  line-height: 18px;  vertical-align: top; padding:10px 0;" align="right">${{ number_format($order->amount_of_ice * 1.95, 2) }}</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td height="1" colspan="4" style="border-bottom:1px solid #e4e4e4"></td>--}}
{{--                            </tr>--}}
{{--                            @if($order->amount_of_boxes > 0)--}}
{{--                                <tr>--}}
{{--                                    <td style="font-size: 12px; font-family: Inter; color: #ff0000;  line-height: 18px;  vertical-align: top; padding:10px 0;" class="article">Styrofoam Box</td>--}}
{{--                                    <td style="font-size: 12px; font-family: Inter; color: #646a6e;  line-height: 18px;  vertical-align: top; padding:10px 0;" align="center">{{ $order->amount_of_boxes }}</td>--}}
{{--                                    <td style="font-size: 12px; font-family: Inter; color: #1e2b33;  line-height: 18px;  vertical-align: top; padding:10px 0;" align="right">${{ number_format($order->amount_of_boxes * 30, 2) }}</td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td height="1" colspan="4" style="border-bottom:1px solid #e4e4e4"></td>--}}
{{--                                </tr>--}}
{{--                            @endif--}}
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="20"></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">
                <tbody>
                <tr>
                    <td>
                        <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff">
                            <tbody>
                            <tr>
                                <td>

                                    <!-- Table Total -->
                                    <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                                        <tbody>
                                        <tr>
                                            <td style="font-size: 12px; font-family: Inter; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">
                                                Subtotal
                                            </td>
                                            <td style="font-size: 12px; font-family: Inter; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; white-space:nowrap;" width="80">
                                                ${{ number_format($order->sub_total,2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 12px; font-family: Inter; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">
                                                Tax (10%)
                                            </td>
                                            <td style="font-size: 12px; font-family: Inter; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">
                                                ${{ number_format($order->tax,2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 12px; font-family: Inter; color: #000; line-height: 22px; vertical-align: top; text-align:right; ">
                                                <strong>Total</strong>
                                            </td>
                                            <td style="font-size: 12px; font-family: Inter; color: #000; line-height: 22px; vertical-align: top; text-align:right; ">
                                                <strong>${{ number_format($order->total_cost, 2) }}</strong>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <!-- /Table Total -->

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">
    <tbody>
    <tr>
        <td>
            <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff" style="border-radius: 0 0 10px 10px;">
                <tr>
                    <td>
                        <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                            <tbody>
                            <tr>
                                <td style="font-size: 12px; color: #1A1C21; font-family: Inter; font-weight:600; line-height: 18px; vertical-align: top; text-align: left;">
                                    Thanks for the business.
                                </td>
                            </tr>
                            @if($order->notes)
                                <tr>
                                    <td style="font-size: 10px; color: #5E6470; font-family: Inter; font-weight:400; line-height: 14px; vertical-align: top; text-align: left; padding-top: 10px;">
                                        <strong>Notes:</strong> {{ $order->notes }}
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr class="spacer">
                    <td height="50"></td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">
    <tbody>
    <tr>
        <td>
            <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff" style="border-radius: 0 0 10px 10px;">
                <tr>
                    <td>
                        <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                            <tbody>
                            <tr>
                                <td>
                                    <p style="font-size: 10px; font-family: Inter; color: #5E6470;  line-height: 14px;  vertical-align: top; padding:10px 0;">Terms & Conditions</p>
                                    <p style="font-size: 10px; font-family: Inter; color: #1A1C21;  line-height: 14px;  vertical-align: top; padding:10px 0;">Please pay within 15 days of receiving this invoice.</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr class="spacer">
                    <td height="50"></td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>

</body>
</html>
