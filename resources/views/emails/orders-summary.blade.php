<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #000;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #5c5c5c;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
        }
        th {
            background-color: #5c5c5c;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        ul {
            margin: 0;
            padding-left: 20px;
        }
        ul li {
            margin: 5px 0;
        }
        .section-header {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 16px;
            padding: 15px 12px !important;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Daily Order Summary - {{ $date->format('F d, Y') }}</h1>

    <p>Total Orders: <strong>{{ ($todayOrders->count() + $nextRecurringOrders->count()) }}</strong></p>

    <table>
        <thead>
        <tr>
            <th>Customer</th>
            <th>Products & Quantities</th>
            <th>Contact</th>
        </tr>
        </thead>
        <tbody>
        @forelse($todayOrders as $order)
            <tr>
                <td>
                    <strong>{{ $order->customer->name }}</strong><br>
                </td>
                <td>
                    <ul>
                        @foreach($order->items as $item)
                            <li>{{ $item->product->product_name ?? 'Product' }} - {{ $item->amount_of_items }} units</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    <strong>Phone:</strong> {{ $order->customer->phone }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" style="text-align: center; color: #666;">No orders for today</td>
            </tr>
        @endforelse

        @if($nextRecurringOrders->count() > 0)
            <tr>
                <td colspan="3" class="section-header">
                    Future Recurring Orders due {{ $nextWeek }}
                </td>
            </tr>
            @foreach($nextRecurringOrders as $order)
                <tr>
                    <td>
                        <strong>{{ $order->order?->customer?->name }}</strong><br>
                    </td>
                    <td>
                        <ul>
                            @foreach($order->order?->items as $item)
                                <li>{{ $item->product->product_name ?? 'Product' }} - {{ $item->amount_of_items }} units</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <strong>Phone:</strong> {{ $order->order?->customer->phone }}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
</body>
</html>
