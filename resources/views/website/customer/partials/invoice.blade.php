{{-- Order Details Partial View (order_details.blade.php) --}}
<div class="invoice-container">
    <!-- Header -->
    <div class="d-flex invoice-header justify-content-between">
        <div class="company-info">
            <img src="{{ asset('idi_logo.svg') }}" alt="Company Logo" class="company-logo">
            <div>
                <h1 class="company-name">Iceberg Dry Ice</h1>
                <p>329 Churchill Avenue</p>
                <p>New Westminster BC V3L 4P5</p>
                <p>Orders: 604-524-0609</p>
                <p>Office: 604-524-0601</p>
            </div>
        </div>
        <div class="invoice-meta">
            @php
                $paymentStatus = ($order->is_recurring_instance ?? false)
                    ? $order->recurring_payment_status
                    : optional($order->invoice_display)->payment_status;
            @endphp
            <span class="payment-status {{ $paymentStatus === 'paid' ? 'paid' : 'unpaid' }}">
                {{ $paymentStatus === 'paid' ? 'PAID' : 'UNPAID' }}
            </span>
            <h2>Invoice <span class="fw-bold">#{{ ($order->is_recurring_instance ?? false) ? $order->recurring_invoice_number : (optional($order->invoice_display)->invoice_number ?? 'N/A') }}</span></h2>
            @if($order->is_recurring_instance ?? false)
                <span class="badge badge-info mt-2">Recurring Order</span>
            @endif
        </div>
    </div>

    <!-- Billing & Dates -->
    <div class="invoice-details">
        <div>
            <p class="label">Billed to</p>
            <p class="value">{{ $order->customer_name }}</p>
            <p>{{ $order->address }}{{ $order->unit ? ', ' . $order->unit : '' }}</p>
            <p>{{ $order->city }}, {{ $order->country }} - {{ $order->postal_code }}</p>
            <p>{{ $order->phone }}</p>
        </div>
        <div>
            <p class="label">Invoice Date</p>
            <p class="value">
                @if($order->is_recurring_instance ?? false)
                    {{ $order->recurring_created_at->format('d.m.Y') }}
                @else
                    {{ $order->created_at->format('d.m.Y') }}
                @endif
            </p>

            <p class="label">{{ ($order->is_recurring_instance ?? false) ? 'Scheduled Delivery' : 'Due Date' }}</p>
            <p class="value">
                @if($order->is_recurring_instance ?? false)
                    {{ \Carbon\Carbon::parse($order->recurring_delivery_date)->format('d.m.Y') }}
                @else
                    {{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d.m.Y') : 'N/A' }}
                @endif
            </p>
        </div>
        <div class="amount-box">
            <span>Amount</span>
            <strong>${{ number_format($order->total_cost, 2) }}</strong>
        </div>
    </div>

    <!-- Items Table -->
    <table class="invoice-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Item</th>
            <th>Unit Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->product_name ?? 'Product' }}</td>
                <td>${{ number_format($item->unit_price, 2) }}</td>
                <td>{{ $item->amount_of_items }}</td>
                <td>${{ number_format($item->total_price, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="invoice-totals">
        <div class="totals-row">
            <span>Subtotal</span>
            <span>${{ number_format($order->sub_total, 2) }}</span>
        </div>
        @if($order->delivery_cost > 0)
            <div class="totals-row">
                <span>Delivery Cost</span>
                <span>${{ number_format($order->delivery_cost, 2) }}</span>
            </div>
        @endif
        <div class="totals-row">
            <span>Tax</span>
            <span>${{ number_format($order->tax, 2) }}</span>
        </div>
        <div class="totals-row total">
            <span>Total</span>
            <span>${{ number_format($order->total_cost, 2) }}</span>
        </div>
    </div>

    <!-- Terms & Notes -->
    <div class="invoice-notes">
        <h4>Terms</h4>
        <p>Net 15 days, interest of 2% per month (24% per annum) charged on all overdue accounts.</p>

        @if($order->notes)
            <h4>Notes</h4>
            <p>{{ $order->notes }}</p>
        @endif

        @if($order->is_recurring_instance ?? false)
            <div class="alert alert-info mt-3">
                <strong>Recurring Order:</strong> This is an automatically generated recurring order instance.
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="invoice-footer">
        <p>Thank you for the business!</p>

        <div class="business-info">
            <div>
                <strong>GST #</strong>
                <p>82379 8541</p>
            </div>
            <div>
                <strong>Contact</strong>
                <p><a href="mailto:admin@icebergdryice.com">admin@icebergdryice.com</a></p>
            </div>
        </div>
    </div>
</div>

<style>
    .order-details-card {
        padding: 25px;
        background: white;
        border-radius: 20px;
        margin-top: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .order-items-table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .order-items-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .order-items-table td {
        vertical-align: middle;
    }

    .total-cost {
        font-weight: bold;
        background-color: #e8f5e8 !important;
        color: #155724;
    }

    .badge {
        font-size: 0.875em;
        padding: 0.375rem 0.75rem;
    }

    .form-control[readonly] {
        background-color: #f8f9fa;
        opacity: 1;
    }

    @media print {
        .btn-submission {
            display: none;
        }

        .order-details-card {
            box-shadow: none;
            border: 1px solid #dee2e6;
        }
    }
</style>

<script>
    function hideOrderDetails() {
        const container = document.getElementById('order-details-container');
        const ordersContainer = document.getElementById('orders-list-container');

        container.style.display = 'none';
        ordersContainer.style.display = 'block';
    }
</script>
