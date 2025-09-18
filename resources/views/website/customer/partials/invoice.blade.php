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
            <span class="payment-status {{ $order->payment_status ? 'paid' : 'unpaid' }}">
                {{ $order->payment_status ? 'PAID' : 'UNPAID' }}
            </span>
            <h2>Invoice <span class="fw-bold">#{{ $order->invoice->invoice_number ?? 'N/A' }}</span></h2>
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
            <p class="value">{{ $order->created_at->format('d.m.Y') }}</p>

            <p class="label">Due Date</p>
            <p class="value">{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d.m.Y') : 'N/A' }}</p>
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
