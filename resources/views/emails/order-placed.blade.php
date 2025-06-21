<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Invoice</title>
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
                                                <img src="{{asset('idi_logo.svg')}}" width="54" height="54" alt="logo" border="0" />
                                            </td>
                                            <td align="left">
                                                <p style="font-family: Inter;font-weight: 700;font-size: 18px;line-height: 24px;letter-spacing: 0%;color:#1A1C21;">Iceberg Dry Ice</p>
                                                <p style="font-family: Inter;font-weight: 500;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">329 Churchill Avenue</p>
                                                <p style="font-family: Inter;font-weight: 500;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">New Westminster BC V3L 4P5</p>
                                                <p style="font-family: Inter;font-weight: 500;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">Orders 604-524-0609</p>
                                                <p style="font-family: Inter;font-weight: 500;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">OFFICE 604-524-0601</p>
                                            </td>
                                            <td style="vertical-align: top;padding-top: 10px;">
                                                @if($order->payment_status)
                                                    <p style="background-color: #28A745;width: 89px;height: 25px;border-radius: 25px;font-family: Inter;font-weight: 600;font-size: 16px;line-height: 12px;letter-spacing: 0%;text-transform: uppercase;color:#000000;padding: 5px 0px 0px 0px !important;text-align: center;box-sizing: border-box;">PAID</p>
                                                @else
                                                    <p style="background-color: #FFC107;width: 89px;height: 25px;border-radius: 25px;font-family: Inter;font-weight: 600;font-size: 16px;line-height: 12px;letter-spacing: 0%;text-transform: uppercase;color:#000000;padding: 5px 0px 0px 0px !important;text-align: center;box-sizing: border-box;">UNPAID</p>
                                                @endif

                                            </td>
                                            <td>
                                                <p style="font-family: Inter;font-weight: 600;font-size: 36px;leading-trim: Cap height;line-height: 40px;letter-spacing: -3%;text-align: right;color:#B2B7C2;">Invoice</p>
                                                <p style="font-family: Inter;font-weight: 500;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;text-align: right;">#{{ $order->id }}</p>
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
                    <td style="position: relative;">
                        <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff">
                            <tbody>
                            <tr>
                                <td style="vertical-align: top;">
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">Billed to</p>
                                    <p style="font-family: Inter;font-weight: 600;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#1A1C21;">{{ $order->customer_name }}</p>
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">{{ $order->address }}{{ $order->unit ? ', ' . $order->unit : '' }}</p>
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">{{ $order->city }}, {{ $order->country }} - {{ $order->postal_code }}</p>
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">{{ $order->phone }}</p>
                                </td>
                                <td style="vertical-align: top;">
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">Invoice date</p>
                                    <p style="font-family: Inter;font-weight: 600;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#1A1C21;">{{ $order->created_at->format('d.m.Y') }}</p>
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;margin-top:15px !important;">Due date</p>
                                    <p style="font-family: Inter;font-weight: 600;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#1A1C21;">{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d.m.Y') : 'N/A' }}</p>
                                </td>
                                <td style="vertical-align: top;text-align:right;">
                                    <p style="font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;color:#5E6470;">Amount</p>
                                    <p style="font-family: Inter;font-weight: 700;font-size: 20px;line-height: 28px;letter-spacing: 0%;color:#fff;background-color: #0B75AF;width: 128px;height: 32px;position:absolute;right: 0px;top:20px;gap: 10px;padding-top: 6px;padding-right: 40px;padding-bottom: 6px;padding-left: 12px;">
                                        ${{ number_format($order->total_cost, 2) }}
                                    </p>
                                </td>
                            </tr>
                            <tr class="hiddenMobile">
                                <td height="20"></td>
                            </tr>
                            <tr class="visibleMobile">
                                <td height="10"></td>
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
                                <th style="font-family: Inter;font-weight: 600;font-size: 8px;line-height: 12px;letter-spacing: 4%;text-transform: uppercase;color:#5E6470;" width="5%" align="left">
                                    #
                                </th>
                                <th style="font-family: Inter;font-weight: 600;font-size: 8px;line-height: 12px;letter-spacing: 4%;text-transform: uppercase;color:#5E6470;" width="45%" align="left">
                                    Item
                                </th>
                                <th style="font-family: Inter;font-weight: 600;font-size: 8px;line-height: 12px;letter-spacing: 4%;text-transform: uppercase;color:#5E6470;" align="left">
                                    Unit Price
                                </th>
                                <th style="font-family: Inter;font-weight: 600;font-size: 8px;line-height: 12px;letter-spacing: 4%;text-transform: uppercase;color:#5E6470;" align="center">
                                    Quantity
                                </th>
                                <th style="font-family: Inter;font-weight: 600;font-size: 8px;line-height: 12px;letter-spacing: 4%;text-transform: uppercase;color:#5E6470;" width="15%" align="right">
                                    Subtotal
                                </th>
                            </tr>
                            <tr>
                                <td height="1" style="background: #bebebe;" colspan="5"></td>
                            </tr>
                            <tr>
                                <td height="10" colspan="5"></td>
                            </tr>
                            @foreach($order->items as $index => $item)
                                <tr>
                                    <td style="font-family: Inter;font-weight: 500;font-size: 10px;line-height: 14px;letter-spacing: 0%;color: #1A1C21;" class="article">
                                        {{ $index + 1 }}
                                    </td>
                                    <td style="font-family: Inter;font-weight: 600;font-size: 10px;line-height: 14px;letter-spacing: 0%;color: #1A1C21;">
                                        {{ $item->product->product_name ?? 'Product' }}
                                    </td>
                                    <td style="font-family: Inter;font-weight: 500;font-size: 10px;line-height: 14px;letter-spacing: 0%;color: #1A1C21;" align="center">${{ number_format($item->unit_price, 2) }}</td>
                                    <td style="font-family: Inter;font-weight: 500;font-size: 10px;line-height: 14px;letter-spacing: 0%;color: #1A1C21;" align="center">{{ $item->amount_of_items }}</td>
                                    <td style="font-family: Inter;font-weight: 500;font-size: 10px;line-height: 14px;letter-spacing: 0%;color: #5E6470;" align="right">${{ number_format($item->total_price, 2) }}</td>
                                </tr>
                                @if(!$loop->last)
                                    <tr>
                                        <td height="1" colspan="5" style="background: #D7DAE0;border-top: 15px solid #fff;border-bottom: 10px solid #fff;"></td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="5"></td>
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
                                <td><!-- Table Total -->
                                    <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                                        <tbody>
                                        <tr>
                                            <td style="font-family: Inter;font-weight: 700;font-size: 10px;line-height: 18px;letter-spacing: 0%;color: #1A1C21;">
                                                Subtotal
                                            </td>
                                            <td style="font-family: Inter;font-weight: 700;font-size: 10px;line-height: 18px;letter-spacing: 0%;color: #1A1C21;text-align: right;" width="80">
                                                ${{ number_format($order->sub_total, 2) }}
                                            </td>
                                        </tr>
                                        @if($order->delivery_cost > 0)
                                            <tr>
                                                <td style="font-family: Inter;font-weight: 700;font-size: 10px;line-height: 18px;letter-spacing: 0%;color: #1A1C21;">
                                                    Delivery Cost
                                                </td>
                                                <td style="font-family: Inter;font-weight: 700;font-size: 10px;line-height: 18px;letter-spacing: 0%;color: #1A1C21;text-align: right;">
                                                    ${{ number_format($order->delivery_cost, 2) }}
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td style="font-family: Inter;font-weight: 700;font-size: 10px;line-height: 18px;letter-spacing: 0%;color: #1A1C21;">
                                                Tax
                                            </td>
                                            <td style="font-family: Inter;font-weight: 700;font-size: 10px;line-height: 18px;letter-spacing: 0%;color: #1A1C21;text-align: right;">
                                                ${{ number_format($order->tax, 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-family: Inter;font-weight: 700;font-size: 10px;line-height: 18px;letter-spacing: 0%;color: #1A1C21;">
                                                Total
                                            </td>
                                            <td style="font-family: Inter;font-weight: 700;font-size: 10px;line-height: 18px;letter-spacing: 0%;color: #1A1C21;text-align: right;">
                                                ${{ number_format($order->total_cost, 2) }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="25"></td>
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
            <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff">
                <tr>
                    <td>
                        <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                            <tbody>
                            <tr>
                                <td style="width:40px;font-size: 8px; color: #5E6470; font-family: Inter; font-weight:600; line-height: 12px; vertical-align: top; text-align: left;">Terms</td>
                                <td><p style="background: #D7DAE0;height: 1px"></p></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <p style="color:#5E6470;font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;">Net 15 days, interest of 2% per month (24% per annum) charged on all overdue accounts</p>
                                </td>
                            </tr>
                            <tr>
                                <td height="20" colspan="2"></td>
                            </tr>
                            @if($order->notes)
                                <tr>
                                    <td style="width:40px;font-size: 8px; color: #5E6470; font-family: Inter; font-weight:600; line-height: 12px; vertical-align: top; text-align: left;">Notes</td>
                                    <td><p style="background: #D7DAE0;height: 1px"></p></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p style="color:#5E6470;font-family: Inter;font-weight: 400;font-size: 10px;line-height: 14px;letter-spacing: 0%;">{{ $order->notes }}</p>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                            <tbody>
                            <tr class="spacer">
                                <td height="60"></td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="font-size: 10px; font-family: Inter; color: #5E6470;  line-height: 14px;  vertical-align: top; padding:10px 0;">Thank you for the business!</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr class="spacer">
                    <td height="10"></td>
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
            <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff">
                <tr>
                    <td>
                        <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                            <tbody>
                            <tr>
                                <td style="width:110px;font-size: 8px; color: #5E6470; font-family: Inter; font-weight:600; line-height: 12px; vertical-align: top; text-align: left;">BUSINESS INFORMATION</td>
                                <td><p style="background: #D7DAE0;height: 1px"></p></td>
                            </tr>
                            <tr class="spacer">
                                <td height="10"></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff" style="border-radius: 0 0 10px 10px;">
                <tr>
                    <td>
                        <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                            <tbody>
                            <tr>
                                <td style="width: 50%;">
                                    <p style="color:#1A1C21;font-family: Inter;font-weight: 600;font-size: 8px;line-height: 14px;letter-spacing: 0%;">GST #</p>
                                    <p style="color:#5E6470;font-family: Inter;font-weight: 500;font-size: 8px;line-height: 14px;letter-spacing: 0%;">82379 8541</p>
                                </td>
                                <td>
                                    <p style="color:#1A1C21;font-family: Inter;font-weight: 600;font-size: 8px;line-height: 14px;letter-spacing: 0%;">Contact</p>
                                    <p style="color:#5E6470;font-family: Inter;font-weight: 500;font-size: 8px;line-height: 14px;letter-spacing: 0%;text-decoration: underline;">admin@icebergdryice.com</p>
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
