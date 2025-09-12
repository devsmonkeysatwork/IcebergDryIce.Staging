{{--@extends('website.layouts.main')--}}

{{--@section('content')--}}

{{--    <div class="container">--}}
{{--        <div class='info'>--}}
{{--            <h1 class="text-center">WelCome Registered Customer</h1>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

@extends(backpack_view('blank'))

@section('header')

<style>
        .pagination > nav > div:first-child {
            display : none !important;
        }

        h1 {
            font-weight: 800;
            font-size: 32px;
            line-height: 42px;
            letter-spacing: -0.11px;
        }

        .card {
            padding: 25px;
            background: white;
            border-radius: 20px;
            margin-top: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h3.form-group-heading {
            font-weight: 800;
            font-size: 24px;
            line-height: 36px;
            letter-spacing: -0.11px;
            color: #333;
        }

        .form-control {
            border-radius: 10px !important;
            border: 1px solid #e3e6f0;
            padding: 10px 15px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-submission {
            font-weight: 600;
            font-size: 16px;
            line-height: 20.8px;
            letter-spacing: 0px;
            text-align: center;
            border-radius: 25px;
            padding: 8px 35px;
            transition: all 0.3s ease;
        }

        .btn-submission:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .header-operation {
            padding: 20px 0;
            border-bottom: 1px solid #e3e6f0;
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .card{
            padding: 0px;
        }
        .card-header{
            background: #000;
            color: #fff;
            border-radius: 20px 20px 0px 0px !important;
        }
        .card-body{
            text-align: left;
        }


        @media (max-width: 768px) {
            .form-group {
                margin-bottom: 15px;
            }

            .btn-submission {
                width: 100%;
                margin-bottom: 10px;
            }

            .float-end {
                float: none !important;
            }
        }
        .invoice-container {
            font-family: 'Inter', sans-serif;
            padding: 2rem;
            color: #1A1C21;
        }

        .company-info {
            display: flex;
            gap: 1rem;
        }

        .company-logo {
            width: 54px;
            height: 54px;
        }

        .invoice-meta {
            text-align: right;
        }

        .payment-status {
            display: inline-block;
            padding: 0.3rem 1rem;
            border-radius: 999px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .payment-status.paid {
            background-color: #28A745;
            color: #000;
        }

        .payment-status.unpaid {
            background-color: #FFC107;
            color: #000;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .label {
            font-weight: 500;
            color: #5E6470;
            margin-bottom: 0.25rem;
        }

        .value {
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .amount-box {
            background-color: #0B75AF;
            color: white;
            padding: 1rem;
            border-radius: 4px;
            text-align: center;
            min-width: 120px;
            height: fit-content;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #D7DAE0;
            font-size: 0.9rem;
            text-align: left;
        }

        .invoice-table th {
            text-transform: uppercase;
            font-size: 0.8rem;
            color: #5E6470;
        }

        .invoice-table td:last-child,
        .invoice-table th:last-child {
            text-align: right;
        }

        .invoice-totals {
            margin-top: 2rem;
            max-width: 300px;
            margin-left: auto;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .totals-row.total {
            font-weight: 700;
        }

        .invoice-notes {
            margin-top: 2rem;
        }

        .invoice-notes h4 {
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .invoice-footer {
            margin-top: 3rem;
            border-top: 1px solid #D7DAE0;
            padding-top: 1rem;
        }

        .business-info {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
        }

        .business-info p {
            margin: 0;
        }

        .btn-add {
           padding: 8px 16px;
          font-weight: 500;
          font-size: 14px;
          background-color: var(--tblr-primary);
          border: none;
          color: var(--tblr-light);
          border-radius: 4px;
        }

        .btn-add:hover {
          background-color: #0246a5;
          color: var(--tblr-light);
        }
    </style>
@endsection

@section('content')

    <section class="container-fluid header">
        <h2 class="title text-capitalize fw-bolder mb-5 ">
            Dashboard<a href="{{route('order')}}" id="create-order-btn" class="btn btn-add btn-sm float-end"><i class="la la-plus mx-2"></i> New Order</a>
        </h2>
        <div class="row flex-row mb-3">
            <div class="col-6 col-lg-4">
                <h2 class="title">Recurring Orders</h2>
                @if($orderWithNextRecurringDue)
                <div class="card dashboard text-center">
                    <div class="card-header fw-bolder">
                        Next Order - #{{ $orderWithNextRecurringDue->id }}
                    </div>
                    <div class="card-body">
                        <h2 class="title fw-bolder">${{ number_format($orderWithNextRecurringDue->total_cost, 2) }}</h2>
                        <p style="color: rgba(141, 141, 141, 1);">
                            Renewal on {{ \Carbon\Carbon::parse($orderWithNextRecurringDue->recurringOrders->first()->scheduled_delivery_date)->format('F jS') }}
                        </p>
                        <div class="flex">
                            <button class="btn btn-primary btn-view btn-submission" data-recurring="1" data-order-id="{{ $orderWithNextRecurringDue->recurringOrders->first()->order_id }}">View</button>
                            @if(!$orderWithNextRecurringDue->recurringOrders->first()->novex_order_id)
                            <button class="btn bg-transparent border-0 text-danger cancel-renewal-btn" id="cancel-btn-{{ $orderWithNextRecurringDue->recurringOrders->first()->id }}"
                                    onclick="cancelRecurring({{ $orderWithNextRecurringDue->recurringOrders->first()->id }})">
                                Cancel Renewal
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @else
                <div class="card dashboard text-center">
                    <div class="card-body">
                        <p class="text-muted">No upcoming recurring orders</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div id="orders-list-container">
            <div class="row">
                <div class="col-12">
{{--                    --}}{{-- Page Header --}}
{{--                    <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none">--}}
{{--                        <h1 class="text-capitalize mb-0">My Orders</h1>--}}
{{--                        <p class="ms-2 ml-2 mb-0">View and manage your order history.</p>--}}
{{--                    </section>--}}

                    <div class="card">
                        <div class="row">
                            <div class="col-md-12 p-4">
                                <h3 class="form-group-heading m-0 mb-4">
                                    <i class="la la-shopping-cart me-2"></i> Last orders
                                </h3>

                                @if($orders->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Order Date</th>
                                                <th>Delivery</th>
                                                <th>Order Status</th>
                                                <th>Total</th>
                                                <th>Payment Status</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($orders as $order)
                                                <tr>
                                                    <td>{{ $order->id }}</td>
                                                    <td>{{ $order->created_at }}</td>
                                                    <td>{{ $order->pickup_delivery }}</td>
                                                    <td>
                                                         <span class="text-uppercase badge
                                                            @if($order->status == \App\Models\Order::COMPLETED) bg-success @elseif($order->status == \App\Models\Order::VALID) bg-primary @else badge-secondary @endif">
                                                            {{ $order->status}}
                                                        </span>

                                                    </td>
                                                    <td>${{ number_format($order->total_cost, 2) }}</td>
                                                    <td>
                                                        <span class="text-uppercase badge
                                                            @if($order->payment_status === 'paid' ) bg-success @elseif($order->payment_status == Null) bg-danger @else badge-secondary @endif">
                                                            {{ $order->payment_status? 'PAID' : 'UNPAID' }}
                                                        </span>

                                                    </td>

                                                    <td>
                                                        <button class="btn btn-sm btn-primary btn-view las la-eye fs-2" data-order-id="{{ $order->id }}">
                                                        </button>
                                                        <button class="btn btn-sm btn-primary  view-invoice mx-2" data-id="{{ $order->id }}">
                                                            <i class="las la-file-invoice fs-2"></i>
                                                        </button>
                                                        @if($order->payment_status == Null )
                                                        <button class="btn btn-sm btn-success pay-your-order fs-4" data-id="{{ $order->id }}">
                                                            <i class="las la-credit-card mx-2"></i>Pay Now
                                                        </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Pagination section --}}
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <form action="{{ route('customer.orders') }}" method="GET" class="mb-3">
                                                {{-- Preserve any existing filter parameters --}}
                                                @foreach(request()->except(['page', 'per_page']) as $key => $value)
                                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                                @endforeach

                                                <div class="form-group d-flex align-items-center">
                                                    <label for="per_page" class="mb-0 me-2">Entries per page</label>
                                                    <select name="per_page" id="per_page" class="form-control" style="width: auto;" onchange="this.form.submit()">
                                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                                    </select>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="float-end pagination">
                                                {{-- Pagination links --}}
                                                @if($isPaginated)
                                                    {{ $orders->appends(request()->except('page'))->links() }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <h5>No orders found</h5>
                                        <p class="text-muted">You haven't placed any orders yet.</p>
                                        <a href="{{ route('home') }}" class="btn btn-primary btn-submission">
                                            <i class="la la-shopping-cart"></i> Buy Now
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Details Container --}}
        <div id="order-details-container" style="display: none;">
            <div id="order-details-content">
                <!-- Order details will be loaded here -->
            </div>
        </div>

    </section>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Page loaded. Binding click events...');

            const detailsContainer = document.getElementById('order-details-container');
            const contentDiv = document.getElementById('order-details-content');
            const ordersContainer = document.getElementById('orders-list-container');

            // View button click handler
            document.querySelectorAll('.btn-view').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const orderId = this.dataset.orderId;
                    const recurring = this.dataset.recurring??0;
                    console.log('Clicked View for Order ID:', orderId);

                    // Hide orders list and show details container
                    ordersContainer.style.display = 'none';
                    detailsContainer.style.display = 'block';

                    // Add loading state
                    contentDiv.innerHTML = '<div class="container"><div class="text-center py-4"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div><p class="mt-2">Loading Order #' + orderId + '...</p></div></div>';

                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    // Fetch order details
                    fetch(`/customer/order-details/${orderId}?recurring=${recurring}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.text();
                        })
                        .then(html => {
                            contentDiv.innerHTML = html;
                        })
                        .catch(error => {
                            console.error('Error fetching order details:', error);
                            contentDiv.innerHTML = '<div class="container"><div class="alert alert-danger"><i class="la la-exclamation-triangle"></i> Failed to load order details. Please try again.</div></div>';
                        });
                });
            });

            // Global function to hide order details (called from the details view)
            window.hideOrderDetails = function() {
                detailsContainer.style.display = 'none';
                ordersContainer.style.display = 'block';
                contentDiv.innerHTML = '';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };
        });

        function cancelRecurring(recurringOrderId) {
            Swal.fire({
                title: 'Manage Recurring Order',
                text: 'Would you like to cancel just this instance or discontinue the entire recurring order?',
                icon: 'warning',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: 'Cancel This Instance',
                denyButtonText: 'Discontinue Entire Order',
                cancelButtonText: 'Close'
            }).then((result) => {
                if (result.isConfirmed || result.isDenied) {
                    const action = result.isConfirmed ? 'cancel_instance' : 'discontinue';
                    const button = document.getElementById(`cancel-btn-${recurringOrderId}`);
                    button.disabled = true;
                    button.innerHTML = 'Processing...';

                    $.ajax({
                        url: `/customer/recurring-orders/${recurringOrderId}/cancel`,
                        type: 'POST',
                        data: { action: action },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire('Success', response.message || 'Action completed successfully.', 'success')
                                .then(() => location.reload());
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'There was a problem processing your request. Please try again.', 'error');
                            button.disabled = false;
                            button.innerHTML = 'Cancel Renewal';
                        }
                    });
                }
            });
        }


        $(document).on('click', '.view-invoice', function () {
                const orderId = $(this).data('id');

                $.ajax({
                    url: `/customer/invoice/${orderId}`,
                    type: 'GET',
                    success: function (response) {
                        $('#invoice-modal-body').html(response.html);
                        $('#invoiceModal').modal('show');
                    },
                    error: function (xhr) {
                        alert('Failed to load invoice. Please try again.');
                    }
                });
            });



        $(document).ready(function() {
            // Handle Pay Now button click
            $('.pay-your-order').click(function(e) {
                e.preventDefault();

                const orderId = $(this).data('id');
                const button = $(this);

                // Disable button to prevent double clicks
                button.prop('disabled', true);

                // Get order details via AJAX first
                $.ajax({
                    url: '{{ route("orders.get-payment-details") }}',
                    method: 'GET',
                    data: { order_id: orderId },
                    success: function(response) {
                        if (response.success) {
                            const orderData = response.data;

                            Swal.fire({
                                title: 'Pay Order',
                                html: `Do you want to pay <strong>$${orderData.amount}</strong> for Invoice #${orderData.invoice_number}?`,
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#28a745',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: '<i class="las la-credit-card"></i> Yes, Pay Now',
                                cancelButtonText: 'Cancel',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Show loading
                                    Swal.fire({
                                        title: 'Processing...',
                                        text: 'Redirecting to payment gateway',
                                        icon: 'info',
                                        showConfirmButton: false,
                                        allowOutsideClick: false,
                                        didOpen: () => {
                                            Swal.showLoading();
                                        }
                                    });

                                    // Submit to controller for payment processing
                                    {{--window.location.href = `{{ route("orders.process-payment") }}?order_id=${orderId}`;--}}
                                } else {
                                    // Re-enable button if cancelled
                                    button.prop('disabled', false);
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message || 'Unable to fetch order details',
                                icon: 'error'
                            });
                            button.prop('disabled', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to load order details. Please try again.',
                            icon: 'error'
                        });
                        button.prop('disabled', false);
                    }
                });
            });
        });


    </script>

@endsection
<div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="invoice-modal-body">
                    <!-- Invoice content will be injected here -->
                </div>
            </div>
        </div>
    </div>
