{{-- Order Details Partial View (order_details.blade.php) --}}
<div class="container">
    <div class="row">
        <div class="col-12">
            {{-- Page Header --}}
            <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-center justify-content-between d-print-none">
                <div class="d-flex mb-2 align-items-baseline">
                    <h1 class="text-capitalize mb-0">Order #{{ $order->id }}</h1>
                    <p class="ms-2 ml-2 mb-0">View your order details and information.</p>
                </div>
                <button class="btn btn-secondary btn-submission" onclick="hideOrderDetails()">
                    <i class="la la-arrow-left me-2"></i> Back to Orders
                </button>
            </section>


            <div class="card order-details-card">
                <div class="row">
                    <div class="col-md-12 px-4">
{{--                        <div class="d-flex justify-content-between align-items-center mb-4">--}}
{{--                            <h3 class="form-group-heading m-0">--}}
{{--                                <i class="la la-shopping-cart me-2"></i> Order Information--}}
{{--                            </h3>--}}
{{--                            <button class="btn btn-secondary btn-submission" onclick="hideOrderDetails()">--}}
{{--                                <i class="la la-arrow-left me-2"></i> Back to Orders--}}
{{--                            </button>--}}
{{--                        </div>--}}

                        {{-- Customer & Order Info --}}
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Order Number</label>
                                <input type="text" class="form-control" value="{{ $order->id }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Customer Name</label>
                                <input type="text" class="form-control" value="{{ $order->customer_name }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Email</label>
                                <input type="text" class="form-control" value="{{ $order->email }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Phone</label>
                                <input type="text" class="form-control" value="{{ $order->phone }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Delivery Date</label>
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Status</label>
                                <div class="form-control d-flex align-items-center" style="background-color: #f8f9fa;">
                                    <span class="badge
                                        @if($order->status == 'completed') badge-success
                                        @elseif($order->status == 'pending') badge-warning
                                        @elseif($order->status == 'cancelled') badge-danger
                                        @else badge-secondary
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Delivery Information --}}
                        <h3 class="form-group-heading m-0 mb-4 mt-4">
                            <i class="la la-map-marker me-2"></i> Delivery Information
                        </h3>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Location Name</label>
                                <input type="text" class="form-control" value="{{ $order->location_name ?? 'N/A' }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Address</label>
                                <input type="text" class="form-control" value="{{ $order->address }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Unit</label>
                                <input type="text" class="form-control" value="{{ $order->unit ?? 'N/A' }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>City</label>
                                <input type="text" class="form-control" value="{{ $order->city }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Province</label>
                                <input type="text" class="form-control" value="{{ $order->province }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Postal Code</label>
                                <input type="text" class="form-control" value="{{ $order->postal_code }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Country</label>
                                <input type="text" class="form-control" value="{{ $order->country }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Pickup/Delivery</label>
                                <input type="text" class="form-control" value="{{ ucfirst($order->pickup_delivery) }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Origin</label>
                                <input type="text" class="form-control" value="{{ $order->origin }}" readonly>
                            </div>
                        </div>

                        {{-- Order Details --}}
                        <h3 class="form-group-heading m-0 mb-4 mt-4">
                            <i class="la la-cube me-2"></i> Order Details
                        </h3>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Amount of Ice</label>
                                <input type="text" class="form-control" value="{{ $order->amount_of_ice }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Amount of Boxes</label>
                                <input type="text" class="form-control" value="{{ $order->amount_of_boxes }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Recurring</label>
                                <div class="form-control d-flex align-items-center" style="background-color: #f8f9fa;">
                                    @if($order->recurring)
                                        <span class="badge badge-info">Yes</span>
                                    @else
                                        <span class="badge badge-secondary">No</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Hazmat</label>
                                <div class="form-control d-flex align-items-center" style="background-color: #f8f9fa;">
                                    @if($order->hazmat)
                                        <span class="badge badge-warning">Yes</span>
                                    @else
                                        <span class="badge badge-success">No</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Payment Status</label>
                                <div class="form-control d-flex align-items-center" style="background-color: #f8f9fa;">
                                    <span class="badge
                                        @if($order->payment_status == 'paid') badge-success
                                        @elseif($order->payment_status == 'pending') badge-warning
                                        @elseif($order->payment_status == 'failed') badge-danger
                                        @else badge-secondary
                                        @endif">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                            </div>

                            @if($order->notes)
                                <div class="form-group col-md-12">
                                    <label>Notes</label>
                                    <textarea class="form-control" rows="3" readonly>{{ $order->notes }}</textarea>
                                </div>
                            @endif
                        </div>



                        {{-- Cost Breakdown --}}
                        <h3 class="form-group-heading m-0 mb-4 mt-4">
                            <i class="la la-calculator me-2"></i> Cost Breakdown
                        </h3>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Sub Total</label>
                                <input type="text" class="form-control" value="${{ number_format($order->sub_total, 2) }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Delivery Cost</label>
                                <input type="text" class="form-control" value="${{ number_format($order->delivery_cost, 2) }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Tax</label>
                                <input type="text" class="form-control" value="${{ number_format($order->tax, 2) }}" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label><strong>Total Cost</strong></label>
                                <input type="text" class="form-control total-cost" value="${{ number_format($order->total_cost, 2) }}" readonly>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-4">
                            <button class="btn btn-secondary btn-submission" onclick="hideOrderDetails()">
                                <i class="la la-arrow-left me-2"></i> Back to Orders
                            </button>
{{--                            <button class="btn btn-primary btn-submission mx-2" onclick="window.print()">--}}
{{--                                <i class="la la-print me-2"></i> Print Order--}}
{{--                            </button>--}}
                        </div>
                    </div>
                </div>
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
