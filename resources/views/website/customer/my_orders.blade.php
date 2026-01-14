@extends(backpack_view('blank'))

@section('header')
    {{--    <section class="container-fluid header">--}}
    {{--        <h2 class="title text-capitalize">--}}
    {{--            Dashboard--}}
    {{--        </h2>--}}
    {{--        <small>--}}
    {{--            <a href="{{ url('admin/manual-payments/create') }}" class="btn btn-add btn-sm mx-3 btn-manual"><i class="la la-wallet mx-2"></i> Manual Payment</a>--}}

    {{--            <button id="create-order-btn" data-bs-toggle="modal" data-bs-target="#orderSummaryModal" class="btn btn-add btn-sm"><i class="la la-plus mx-2"></i> New Order</button>--}}
    {{--        </small>--}}
    {{--    </section>--}}
@endsection

@section('content')
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
    </style>

    <div class="container">
        {{-- Orders List Container --}}
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
                            <div class="col-md-12 px-4">
                                <h3 class="form-group-heading m-0 mb-4">
                                    <i class="la la-shopping-cart me-2"></i> Order History
                                </h3>

                                @if($orders->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Invoice #</th>
                                                <th>Date</th>
                                                <th>Delivery Date</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Payment Status</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($orders as $order)
                                                <tr class="{{ $order instanceof \App\Models\RecurringOrder ? 'table-secondary' : '' }}">
                                                    <td>
                                                        @if($order instanceof \App\Models\RecurringOrder)
                                                            <i class="la la-repeat me-1 text-info"></i>
                                                        @endif
                                                        {{ str_pad($order->invoice_id, 4, '0', STR_PAD_LEFT) }}
                                                        @if($order instanceof \App\Models\RecurringOrder)
                                                            <small class="badge badge-info ms-1">Recurring</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                                    <td>
                                                        @if($order instanceof \App\Models\RecurringOrder)
                                                            {{ \Carbon\Carbon::parse($order->scheduled_delivery_date)->format('Y-m-d') }}
                                                        @else
                                                            {{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') : 'N/A' }}
                                                        @endif
                                                    </td>
                                                    <td>
                    <span class="text-uppercase badge
                        @if($order->status == \App\Models\Order::COMPLETED || $order->status == 'completed') bg-success
                        @elseif($order->status == \App\Models\Order::VALID || $order->status == 'open') bg-primary
                        @else badge-secondary @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                                                    </td>
                                                    <td>
                                                        @if($order instanceof \App\Models\RecurringOrder)
                                                            ${{ number_format($order->order->total_cost, 2) }}
                                                        @else
                                                            ${{ number_format($order->total_cost, 2) }}
                                                        @endif
                                                    </td>
                                                    <td>
                    <span class="text-uppercase badge
                       @if(optional($order->invoice)->payment_status === 'paid')
                            bg-success
                        @elseif(optional($order->invoice)->payment_status === 'pending')
                            bg-danger
                        @else
                            badge-secondary
                        @endif">
                        {{ optional($order->invoice)->payment_status ?: 'no invoice' }}
                    </span>
                                                    </td>
                                                    <td>
                                                        @if($order instanceof \App\Models\RecurringOrder)
                                                            {{-- Recurring Order Actions --}}
                                                            <button class="btn btn-sm btn-primary btn-view la la-eye fs-2"
                                                                    data-order-id="{{ $order->order_id }}"
                                                                    data-recurring="1"
                                                                    data-recurring-id="{{ $order->id }}">
                                                            </button>
{{--                                                            <button class="btn btn-sm btn-primary view-invoice mx-2"--}}
{{--                                                                    data-id="{{ $order->id }}"--}}
{{--                                                                    data-is-recurring="1">--}}
{{--                                                                <i class="las la-file-invoice fs-2"></i>--}}
{{--                                                            </button>--}}
                                                            @if(optional($order->invoice)->payment_status === 'pending')
                                                                <button class="btn btn-sm btn-success pay-your-order fs-4" data-id="{{ $order->invoice->id }}">
                                                                    <i class="las la-credit-card mx-2"></i>Pay Now
                                                                </button>
                                                            @elseif(optional($order->invoice)->payment_status === \App\Models\Invoice::FAILED)
                                                                <button class="btn btn-sm btn-secondary pay-your-order fs-4" data-id="{{ $order->invoice->id }}">
                                                                    <i class="las la-credit-card mx-2"></i>Try again
                                                                </button>
                                                            @endif
                                                        @else
                                                            {{-- Regular Order Actions --}}
                                                            <button class="btn btn-sm btn-primary btn-view la la-eye fs-2"
                                                                    data-order-id="{{ $order->id }}"
                                                                    data-recurring="0"
                                                                    data-recurring-id="0">
                                                            </button>
{{--                                                            <button class="btn btn-sm btn-primary view-invoice mx-2"--}}
{{--                                                                    data-id="{{ $order->id }}"--}}
{{--                                                                    data-is-recurring="0">--}}
{{--                                                                <i class="las la-file-invoice fs-2"></i>--}}
{{--                                                            </button>--}}
                                                            @if(optional($order->invoice)->payment_status === 'pending')
                                                                <button class="btn btn-sm btn-success pay-your-order fs-4" data-id="{{ $order->invoice_id }}">
                                                                    <i class="las la-credit-card mx-2"></i>Pay Now
                                                                </button>
                                                            @elseif(optional($order->invoice)->payment_status === \App\Models\Invoice::FAILED)
                                                                <button class="btn btn-sm btn-secondary pay-your-order fs-4" data-id="{{ $order->invoice_id }}">
                                                                    <i class="las la-credit-card mx-2"></i>Try again
                                                                </button>
                                                            @endif
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
{{--                                                --}}{{-- Pagination links --}}
{{--                                                @if($isPaginated)--}}
{{--                                                    {{ $orders->appends(request()->except('page'))->links() }}--}}
{{--                                                @endif--}}
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
    </div>

    {{-- JavaScript --}}
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
                    const recurring = this.dataset.recurring ?? 0;
                    const recurringId = this.dataset.recurringId ?? 0;
                    console.log('Clicked View for Order ID:', orderId, 'Recurring:', recurring, 'Recurring ID:', recurringId);

                    // Hide orders list and show details container
                    ordersContainer.style.display = 'none';
                    detailsContainer.style.display = 'block';

                    // Add loading state
                    contentDiv.innerHTML = '<div class="container"><div class="text-center py-4"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div><p class="mt-2">Loading Order...</p></div></div>';

                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    // Fetch order details
                    fetch(`/customer/order-details/${orderId}?recurring=${recurring}&recurring_id=${recurringId}`)
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

            // Invoice button click handler
            $(document).on('click', '.view-invoice', function () {
                const orderId = $(this).data('id');
                const isRecurring = $(this).data('is-recurring') ?? 0;

                $.ajax({
                    url: `/customer/invoice/${orderId}?is_recurring=${isRecurring}`,
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

            // Global function to hide order details
            window.hideOrderDetails = function() {
                detailsContainer.style.display = 'none';
                ordersContainer.style.display = 'block';
                contentDiv.innerHTML = '';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };
        });

        $(document).ready(function() {
            // Handle Pay Now button click
            $('.pay-your-order').click(function(e) {
                e.preventDefault();

                const invoiceId = $(this).data('id');
                const button = $(this);

                // Disable button to prevent double clicks
                button.prop('disabled', true);

                // Get order details via AJAX first
                $.ajax({
                    url: '{{ route("orders.get-payment-details") }}',
                    method: 'GET',
                    data: { invoice_id: invoiceId },
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
                                    window.location.href = `{{ route("payments.initiate") }}?invoice_id=${invoiceId}`;
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
