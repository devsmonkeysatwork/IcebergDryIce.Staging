

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dry Ice Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
        }
        .info-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
            margin-bottom: 20px;
        }
        .order-item {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
        }
        .order-item h4 {
            margin: 0 0 10px 0;
            color: #495057;
        }
        .order-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 10px;
        }
        .order-detail {
            padding: 5px 0;
        }
        .order-detail strong {
            color: #495057;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
        }
        .summary {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🧊 Dry Ice Orders</h1>
        <p>{{ $formatted_date }}</p>
    </div>

    <div class="info-box">
        <strong>Location:</strong> {{ $location }}<br>
        <strong>Delivery Date:</strong> {{ $formatted_date }}<br>
        <strong>Recipient:</strong> {{ $recipient_email }}
    </div>

    @if($orders && $orders->count() > 0)
        <div class="summary">
            <h3>📋 Order Summary</h3>
            <p><strong>Total Orders:</strong> {{ $orders->count() }}</p>
        </div>

        <h3>📦 Order Details</h3>
        @foreach($orders as $index => $order)
            <div class="order-item">
                <h4>Order #{{ $index + 1 }}</h4>
                <div class="order-details">
                    <div class="order-detail">
                        <strong>Customer:</strong> {{ $order->customer_name }}
                    </div>
                    <div class="order-detail">
                        <strong>Quantity:</strong> {{ $order->amount_of_ice }} <span>lbs</span>
                    </div>
                    <div class="order-detail">
                        <strong>Contact:</strong> {{ $order->phone ?? 'N/A' }}
                    </div>

                </div>
            </div>
        @endforeach
    @else
        <div class="info-box">
            <h3>📭 No Orders Found</h3>
            <p>No orders were found for {{ $location }} on {{ $formatted_date }}.</p>
        </div>
    @endif



    <div class="footer">
        <p>This email was sent automatically by the Dry Ice Management System.</p>
        <p><small>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</small></p>
    </div>
</div>
</body>
</html>
