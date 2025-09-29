@extends(backpack_view('blank'))

@section('header')
<section class="container-fluid header">
  <h2 class="title text-capitalize">
    Dashboard
  </h2>
    <small>
        <a href="{{ url('admin/manual-payments/create') }}" class="btn btn-add btn-sm mx-3 btn-manual"><i class="la la-wallet mx-2"></i> Manual Payment</a>

        <button id="create-order-btn" data-bs-toggle="modal" data-bs-target="#orderSummaryModal" class="btn btn-add btn-sm"><i class="la la-plus mx-2"></i> New Order</button>
  </small>
</section>
@endsection

@section('content')
<div class="container-fluid">

    <div class="row">
{{--        <div class="col-md-6">--}}
{{--            <div class="table">--}}
{{--                <div class="table-header">--}}
{{--                    Online Orders--}}
{{--                </div>--}}
{{--                <div class="table-responsive-wrapper">--}}
{{--                    <table>--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th>#</th>--}}
{{--                            <th>Customer</th>--}}
{{--                            <th>Delivery Date</th>--}}
{{--                            <th>Status</th>--}}
{{--                            <th>Total</th>--}}
{{--                            <th>Action</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        @foreach($onlineOrders as $order)--}}
{{--                            <tr>--}}
{{--                                <td>{{ $order['id'] }}</td>--}}
{{--                                <td>{{ $order['customer_name'] }}</td>--}}
{{--                                <td>{{ \Illuminate\Support\Carbon::parse($order['delivery_date'])->format("Y-m-d") }}</td>--}}
{{--                                <td>--}}
{{--                                    <span class="badge @if($order['status'] == 'COMPLETED') bg-success--}}
{{--                                                    @elseif($order['status'] == 'VALID') bg-success--}}
{{--                                                    @elseif($order['status'] == 'CANCELLED') bg-warning--}}
{{--                                                    @elseif($order['status'] == 'SKIP') bg-warning--}}
{{--                                                    @endif">{{ $order['status'] }}</span>--}}
{{--                                </td>--}}
{{--                                <td>${{ $order['total_cost'] }}</td>--}}
{{--                                <td>--}}
{{--                                    @php--}}
{{--                                        $dateTime = \Carbon\Carbon::parse($order['delivery_date']);--}}
{{--                                    @endphp--}}
{{--                                    <button class="btn btn-primary btn-view" data-order-id="{{ $order['id'] }}">--}}
{{--                                        View--}}
{{--                                    </button>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="col-md-6">--}}
{{--            <div class="table mb-3">--}}
{{--                <div class="table-header">--}}
{{--                    Manual Orders--}}
{{--                </div>--}}
{{--                <div class="table-responsive-wrapper">--}}
{{--                    <table>--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th>#</th>--}}
{{--                            <th>Customer</th>--}}
{{--                            <th>Delivery</th>--}}
{{--                            <th>Status</th>--}}
{{--                            <th>Total</th>--}}
{{--                            <th>Action</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        @foreach($manualOrders as $order)--}}
{{--                            <tr>--}}
{{--                                <td>{{ $order['id'] }}</td>--}}
{{--                                <td>{{ $order['customer_name'] }}</td>--}}
{{--                                <td>{{ \Illuminate\Support\Carbon::parse($order['delivery_date'])->format("Y-m-d") }}</td>--}}
{{--                                <td>--}}
{{--                                    <span class="badge @if($order['status'] == 'COMPLETED') bg-success--}}
{{--                                                    @elseif($order['status'] == 'VALID') bg-success--}}
{{--                                                    @elseif($order['status'] == 'CANCELLED') bg-warning--}}
{{--                                                    @elseif($order['status'] == 'SKIP') bg-warning--}}
{{--                                                    @endif">{{ $order['status'] }}</span>--}}
{{--                                </td>--}}
{{--                                <td>${{ $order['total_cost'] }}</td>--}}
{{--                                <td>--}}
{{--                                    @php--}}
{{--                                        $dateTime = \Carbon\Carbon::parse($order['delivery_date']);--}}
{{--                                    @endphp--}}
{{--                                    <button class="btn btn-primary btn-view" data-order-id="{{ $order['id'] }}">--}}
{{--                                        View--}}
{{--                                    </button>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

            <div class="col-md-6">
                <div class="table">
                    <div class="table-header">
                        One Time Orders
                    </div>
                    <div class="table-responsive-wrapper">
                        <table>
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Delivery Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>View</th>
                                <th>Payment</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($oneTimeOrders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge {{ $order['status'] == 'completed' ? 'bg-success' : 'bg-secondary'  }}">{{ $order['status'] }}</span>
                                    </td>
                                    <td>${{ $order->total_cost }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-view la la-eye la-2x" title="View Order Details" data-order-id="{{ $order['id'] }})"><i class=""></i></button>
                                    </td>
                                    <td>
                                        <span class="badge {{$order['payment_status'] == 'paid' ? 'bg-success' : 'bg-danger'}}">
                                            {{ $order['payment_status'] == 'paid' ? 'PAID' : 'PENDING' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="col-md-6">
                <div class="table">
                    <div class="table-header">
                        Recurring Orders
                    </div>
                    <div class="table-responsive-wrapper">
                        <table>
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Delivery Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>View</th>
                                <th>Payment</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($allRecurringOrders as $order)
                                <tr>
                                    <td>
                                        {{-- Handle both Order and RecurringOrder --}}
                                        {{ $order instanceof \App\Models\RecurringOrder ? $order->order_id . ' - ' . $order->id : $order->id }}                                    </td>
                                    <td>
                                        {{-- Handle customer name from both sources --}}
                                        {{ $order instanceof \App\Models\RecurringOrder ? $order->order->customer_name : $order->customer_name }}
                                    </td>
                                    <td>
                                        {{-- Handle delivery date from both sources --}}
                                        {{ $order instanceof \App\Models\RecurringOrder
                                            ? \Carbon\Carbon::parse($order->scheduled_delivery_date)->format('Y-m-d')
                                            : \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $order['status'] == 'completed' ? 'bg-success' : 'bg-secondary'  }}">{{ $order['status'] }}</span>
                                    </td>
                                    <td>
                                        {{-- Handle total cost from both sources --}}
                                        ${{ $order instanceof \App\Models\RecurringOrder ? $order->order->total_cost : $order->total_cost }}
                                    </td>
                                    <td>
                                        @if($order instanceof \App\Models\RecurringOrder)
                                            <button class="btn btn-primary rounded-5 la la-eye la-2x"
                                                    onclick="loadRecurringOrderDetails(1,'{{ $order->order_id }}','{{ $order->id }}')">
                                            </button>
                                        @else
                                            <button class="btn btn-primary btn-view la la-eye la-2x" data-order-id="{{ $order->id }}"></button>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order instanceof \App\Models\RecurringOrder)
                                            <span class="badge {{$order['rucurring_payment_status'] == 'paid' ? 'bg-success' : 'bg-danger'}}">
                                            {{ $order['payment_status'] == 'paid' ? 'PAID' : 'PENDING' }}
                                        </span>
                                        @else
                                            <span class="badge {{$order['payment_status'] == 'paid' ? 'bg-success' : 'bg-danger'}}">
                                            {{ $order['payment_status'] == 'paid' ? 'PAID' : 'PENDING' }}
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="orderSummaryModal" tabindex="-1" aria-labelledby="orderSummaryLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="modal-content-container">


        </div>
    </div>
</div><!-- Bootstrap Modal -->


@endsection

@section('after_styles')
@vite(['resources/scss/app.scss', 'resources/css/custom.css'])
<style>



    aside.navbar-vertical.navbar-expand-lg {
        margin-top: 110px;
    }
    nav.navbar.navbar-expand-lg {
        background: rgba(2, 86, 197, 1);
    }
    .navbar-nav svg{
        width: 15px;
    }
    .navbar-expand-lg.navbar-vertical~.navbar, .navbar-expand-lg.navbar-vertical~.page-wrapper {
        max-width: 100vw;
        max-height: 100vh;
        overflow-y: scroll;
    }
    h4.text-white {
        font-size: 15px;
    }
    a {
        text-decoration: none;
    }

    .navbar .nav-link {
      display: flex;
    }

    .container-fluid h2.title {
      font-weight: 900;
        font-size: 36px;
        letter-spacing: -0.11px;
        margin: 0px;
    }

    .container-fluid.header {
      display: flex;
      justify-content: space-between;
      padding: 0 24px 20px;
        align-items: center;
    }

    .container-fluid .btn-add {
      padding: 12px 20px;
      font-weight: 700;
      font-size: 18px;
      font-family: Nunito Sans;

    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -15px;
    }

    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0 15px;
    }

    .table {
        margin-bottom: 30px;
        border-radius: 8px;

    }

    .table-header {
        color: #221e26;
        padding: 10px 0px;
        font-weight: 700;
        font-size: 24px;
        line-height: 36px;
        letter-spacing: -0.11px;

    }

    .table-header i {
        color: #0256c5;
        font-size: 26px;
        margin-right: 8px;
    }

    /* Table wrapper for horizontal scrolling */
    .table-responsive-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 0 0 8px 8px;
        background: transparent;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        padding: 0px 5px;
        height: 100%;
    }

    .table table {
        width: 100%;
        min-width: 520px;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .table thead {
        background-color: #221e26;
    }

    .table thead th {
        color: white;
        font-weight: 500;
        font-size: 14px;
        padding: 15px 12px;
        border: none;
        text-transform: capitalize;
        white-space: nowrap;
        vertical-align: middle;
    }

    .table tbody tr {
        background-color: white;
        border-bottom: 1px solid #f1f3f4;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table tbody td {
        padding: 5px !important;
        font-size: 14px;
        vertical-align: middle;
        border: none;
        white-space: nowrap;
    }

    /* Badge/Status styling */
    .status, .badge {
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        margin: 0;
    }



    /* Button styling */
    .btn-view, .btn-primary {
        font-weight: 600;
        font-size: 14px;
        line-height: 20.8px;
        letter-spacing: 0px;
        text-align: center;
        border-radius: 20px;
        padding: 6px 18px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #0256c5;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0246a5;
    }

    .btn-add {
        background-color: var(--tblr-primary);
        border: 1px solid var(--tblr-primary);
        color: var(--tblr-light);
        border-radius: 10px;
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
    }

    .btn-manual {
        background: rgba(232, 232, 232, 1);
        border: 1px solid black;
        letter-spacing: -0.11px;
        color: black;
        border-radius: 10px;
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
    }

    .btn-add:hover {
        background-color: #0246a5;
        color: var(--tblr-light);
    }

    .form-group.entries {
      display: flex;
      flex-direction: row-reverse;
      float: inline-start;
      gap: 5px;
      font-size: 14px;
    }

    .form-group .form-control {
      width: 62px;
      height: 23.14px;
      font-size: 14px;
      padding: 0;
    }

    /* Status Colors */


    .btn-view , .modal .btn {
        font-weight: 600;
        font-size: 14px;
        line-height: 20.8px;
        letter-spacing: 0px;
        text-align: center;
        border-radius: 20px;
        padding: 6px 18px;
    }
    .modal {
        z-index: 1050 !important;
        background: rgba(0, 0, 0, 0.6);
    }
    .modal-backdrop {
        z-index: 1040 !important;
        width: unset !important;
    }
    .modal-dialog {
        max-width: 65%;
        margin-top: 5%;
    }
    .modal .modal-title {
        font-size: 24px;
        font-weight: 800 !important;
    }
    .modal h5 {
        font-size: 18px;
        font-weight: 700;
    }

    .modal label {
        margin: 0px;
    }
    .modal input , .modal select , .modal textarea {
        border-radius: 9px !important;
        padding: 4px;
    }

    .modal input{
        color : rgba(91, 98, 107, 0.5);
    }
    .select2-container {
        width: 250px;
        border-radius: 8px;
        margin: 0px 10px;
        border: 1px solid rgba(213, 213, 213, 1);
        background: white;
    }
    span.select2-selection.select2-selection--single, .select2-selection.select2-selection--multiple ul.select2-selection__rendered {
        border: none;
        background: transparent;
    }




    @media (min-width: 1400px) {
      .container-xxl,
      .container-xl,
      .container-lg,
      .container-md,
      .container-sm,
      .container {
        max-width: 1328px;
      }
    }



    footer {
        display: none;
    }


    .order-details-card .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    .order-details-card .form-group .form-control{
        width: 100% !important;
        height: 40px !important;
        padding-left: 10px;
    }
    .header-operation{
        padding-top: 15px;
    }
    .order-details-card{
        box-shadow: none !important;
    }
    @media (max-width: 992px) {
        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

        .table table {
            min-width: 700px;
        }

        .table thead th,
        .table tbody td {
            padding: 12px 8px;
            font-size: 13px;
        }
    }

    /* Mobile Styles */
    @media (max-width: 768px) {
        .row {
            margin: 0 -10px;
        }

        .col-md-6 {
            padding: 0 10px;
        }

        .table-header {
            padding: 12px 15px;
            font-size: 20px;
            text-align: center;
        }

        .table table {
            min-width: 600px;
            font-size: 13px;
        }

        .table thead th,
        .table tbody td {
            padding: 12px 8px;
            font-size: 13px;
        }

        .status, .badge {
            padding: 4px 8px;
            font-size: 11px;
        }

        .btn-view, .btn-primary {
            padding: 6px 12px;
            font-size: 11px;
        }

        /* Mobile scrollbar styling */
        .table-responsive-wrapper::-webkit-scrollbar {
            height: 4px;
        }

        .table-responsive-wrapper::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 2px;
        }

        .table-responsive-wrapper::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 2px;
        }
    }

    /* Extra Small Mobile */
    @media (max-width: 480px) {
        .table-header {
            padding: 10px 12px;
            font-size: 18px;
        }

        .table table {
            min-width: 500px;
        }

        .table thead th,
        .table tbody td {
            padding: 10px 6px;
            font-size: 12px;
        }

        .status, .badge {
            padding: 3px 6px;
            font-size: 10px;
        }

        .btn-view, .btn-primary {
            padding: 5px 10px;
            font-size: 10px;
        }
    }

    /* Ensure tables maintain your existing spacing */
    .table.recurring {
        margin-top: 38px;
    }

    .table.mb-3 {
        margin-bottom: 1rem;
    }


    /* Extra Small Mobile */

    /* Ensure tables maintain your existing spacing */


    .table.mb-3 {
        margin-bottom: 1rem;
    }
    .select2-container .select2-selection--single{
        height : 34px !important;
        padding-top: 7px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow{
        top: 4px !important;
    }

</style>

@endsection

@push('after_scripts')
@vite(['resources/js/app.js'])

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{config('services.google.api_key')}}&libraries=places"></script>
<script>

    document.addEventListener('DOMContentLoaded', function() {
        let summaryModal = document.getElementById("orderSummaryModal");
        let sidebar = document.querySelector("aside.navbar-vertical");
        let isEditMode = false;
        let customerData = {}; // Store customer data for quick lookup

        // Initialize Select2 for email field
        function initializeEmailSelect2() {
            $('#modal-customer-email').select2({
                dropdownParent: $('#orderSummaryModal'),
                placeholder: 'Search or enter email address',
                allowClear: true,
                tags: true,
                ajax: {
                    url: '{{ route("admin.customers.search") }}', // You'll need to create this route
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        return {
                            results: data.customers.map(customer => ({
                                id: customer.email,
                                text: `${customer.email} (${customer.name})`,
                                customer: customer
                            })),
                            pagination: {
                                more: data.has_more
                            }
                        };
                    },
                    cache: true
                },
                templateResult: function(customer) {
                    if (customer.loading) return customer.text;

                    if (customer.customer) {
                        return $(`
                    <div class="select2-customer-result">
                        <div class="customer-email">${customer.customer.email}</div>
                        <div class="customer-details text-muted small">
                            ${customer.customer.name} • ${customer.customer.city || 'N/A'}, ${customer.customer.province || 'N/A'}
                        </div>
                    </div>
                `);
                    }

                    return $(`<div class="select2-new-email">New: ${customer.text}</div>`);
                },
                templateSelection: function(customer) {
                    return customer.customer ? customer.customer.email : customer.text;
                }
            });

            // Handle email selection
            $('#modal-customer-email').on('select2:select', function (e) {
                const data = e.params.data;

                if (data.customer) {
                    // Existing customer selected - populate fields
                    populateCustomerFields(data.customer);
                    customerData[data.customer.email] = data.customer;
                } else {
                    // New email entered - clear customer fields but keep email
                    clearCustomerFields(data.text);
                }
            });

            // Handle clearing selection
            $('#modal-customer-email').on('select2:clear', function (e) {
                clearCustomerFields('');
            });
        }

        // Populate customer fields with existing data
        function populateCustomerFields(customer) {
            document.getElementById('modal-customer-name').value = customer.name || '';
            document.getElementById('modal-customer-phone').value = customer.phone || '';
            document.getElementById('modal-address').value = customer.address || '';
            document.getElementById('modal-city').value = customer.city || '';
            document.getElementById('modal-postal').value = customer.postal_code || '';
            document.getElementById('modal-province').value = customer.province || '';

            // Add visual indication that fields are pre-filled
            const prefilledFields = [
                'modal-customer-name',
                'modal-customer-phone',
                'modal-address',
                'modal-city',
                'modal-postal',
                'modal-province'
            ];

            prefilledFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field.value) {
                    field.classList.add('prefilled-field');
                    // Remove the class after animation
                    setTimeout(() => field.classList.remove('prefilled-field'), 2000);
                }
            });
        }

        // Clear customer fields
        function clearCustomerFields(emailValue = '') {
            document.getElementById('modal-customer-name').value = '';
            document.getElementById('modal-customer-phone').value = '';
            document.getElementById('modal-address').value = '';
            document.getElementById('modal-city').value = '';
            document.getElementById('modal-postal').value = '';
            document.getElementById('modal-province').value = '';
        }

        // Handle sidebar z-index for Backpack compatibility
        summaryModal.addEventListener("show.bs.modal", function() {
            if (sidebar) {
                sidebar.style.zIndex = "-1";
            }
            // Initialize Select2 when modal opens
            setTimeout(() => {
                initializeEmailSelect2();
            }, 100);
        });

        summaryModal.addEventListener("hidden.bs.modal", function() {
            if (sidebar) {
                sidebar.style.zIndex = "1030";
            }
            // Destroy Select2 when modal closes
            if ($('#modal-customer-email').hasClass('select2-hidden-accessible')) {
                $('#modal-customer-email').select2('destroy');
            }
        });

        // Create New Order button
        document.getElementById('create-order-btn').addEventListener('click', function() {
            loadModalContent('create');
        });

        // Edit Order buttons (existing functionality)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-view')) {
                const orderId = e.target.dataset.orderId;
                loadModalContent('edit', orderId);
            }
        });
// Replace loadModalContent function with this optimized version:
        function loadModalContent(action, orderId = null) {
            const url = action === 'edit'
                ? `/admin/orders/modal/${orderId}/edit`
                : '/admin/orders/modal/create';

            // Show loading state
            document.getElementById('modal-content-container').innerHTML = `
                <div class="modal-body text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading...</p>
                </div>`;

            // Show the modal immediately with loading state
            const modal = new bootstrap.Modal(summaryModal);
            modal.show();

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.text();
                })
                .then(html => {
                    document.getElementById('modal-content-container').innerHTML = html;
                    isEditMode = action === 'edit';

                    // Initialize components after content is loaded
                    setTimeout(() => {
                        initializeEmailSelect2();
                        addCostCalculationListeners();
                        setupDeliveryCalculation();

                        // Calculate costs for edit mode
                        // if (isEditMode) {
                        //     updateCostSummary();
                        // }
                    }, 100);
                })
                .catch(error => {
                    console.error('Error loading modal content:', error);
                    document.getElementById('modal-content-container').innerHTML = `
                    <div class="modal-body text-center">
                        <i class="la la-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Error Loading Content</h5>
                        <p>Unable to load modal content. Please try again.</p>
                        <button class="btn btn-primary" onclick="loadModalContent('${action}', ${orderId})">Retry</button>
                    </div>`;
                });
        }

        function setupDeliveryCalculation() {
            const addressFields = [
                'modal-address',
                'modal-city',
                'modal-province',
                'modal-postal',
                'modal-ice-amount'
            ];

            let btnHtml = `<button
                        id="recalculate-delivery-btn"
                        type="button"
                        class="btn btn-xs btn-outline-primary"
                        title="Click to recalculate delivery charges">
                        Recalculate
                    </button>`;

            addressFields.forEach(id => {
                const field = document.getElementById(id);
                if (field) {
                    field.removeEventListener('input', field._deliveryListener);

                    // Create new listener
                    field._deliveryListener = () => {
                        const deliveryOption = document.getElementById('modal-pickup-or-delivery');
                        const recalculateButton = document.getElementById('recalculate-delivery-btn');
                        const saveOrderBtn = document.getElementById('save-order-btn');

                        if (deliveryOption && deliveryOption.value === 'delivery') {
                            if (!recalculateButton) {
                                $('.cost-summary-delivery').append(btnHtml);

                                // Disable save order button when recalc button is shown
                                if (saveOrderBtn) {
                                    saveOrderBtn.disabled = true;
                                }
                            }
                        }

                        // If button already exists but user clears/change fields
                        setTimeout(() => {
                            const btnExists = document.getElementById('recalculate-delivery-btn');
                            if (!btnExists && saveOrderBtn) {
                                saveOrderBtn.disabled = false; // Enable back if button gone
                            }
                        }, 100);
                    };

                    field.addEventListener('input', field._deliveryListener);
                }
            });

            // Handle recalc button click (remove it and enable save order button)
            $(document).on('click', '#recalculate-delivery-btn', function () {
                $(this).remove();
                const saveOrderBtn = document.getElementById('save-order-btn');
                if (saveOrderBtn) {
                    saveOrderBtn.disabled = false;
                }
            });
        }

        $(document).on('change','#modal-pickup-or-delivery',function () {
            if (this.value === 'delivery') {
                // Setup auto listener to check fields and trigger quote
                setupDeliveryCalculation();

                // Initial trigger in case fields are already filled
                tryGetDeliveryQuote();
            } else if (this.value === 'pickup') {
                // Set delivery cost to 0 for pickup
                updateDeliveryCostSummary(0);
            } else {
                // Clear delivery cost for other options
                updateDeliveryCostSummary(null);
            }
        });

        $(document).on('click','#recalculate-delivery-btn',function () {
            $('#recalculate-delivery-btn').remove();
            tryGetDeliveryQuote();
        });



        // Dynamic cost calculation
        function updateCostSummary() {
            let productTotal = 0;
            let productSummaryHtml = '';

            // Calculate product costs
            document.querySelectorAll('.product-amount').forEach(input => {
                const amount = parseFloat(input.value) || 0;
                const unitPrice = parseFloat(input.dataset.unitPrice) || 0;
                const productCost = amount * unitPrice;
                productTotal += productCost;

                if (amount > 0) {
                    const productId = input.dataset.productId;
                    const productName = document.querySelector('.label-'+productId).textContent;
                    productSummaryHtml += `
                <p class="m-0 d-flex justify-content-between align-items-center">
                    ${productName} (${amount} @ $${unitPrice.toFixed(2)}):
                    <strong>$${productCost.toFixed(2)}</strong>
                </p>
            `;
                }
            });

            const pickupDelivery = document.getElementById('modal-pickup-or-delivery').value;
            const deliveryCost = parseFloat(document.getElementById('modal-delivery-cost').value) || 0;
            const hazmatCost = parseFloat(document.getElementById('modal-hazmat-cost').value) || 0;

            const deliveryFee = pickupDelivery === 'delivery' ? deliveryCost : 0.00;
            const subTotal = productTotal;
            const taxRate = 0.15;
            const tax = (subTotal + deliveryFee) * taxRate;
            const total = subTotal + tax + deliveryFee + hazmatCost;

            // Update the cost summary section
            document.querySelector('.cost-summary-products').innerHTML = productSummaryHtml;

            document.querySelector('.cost-summary-delivery').innerHTML =
                `Pickup/Delivery:<strong>$${deliveryFee.toFixed(2)}</strong>`;

            document.querySelector('.cost-summary-hazmat').innerHTML =
                `Hazmat:<strong>$${hazmatCost.toFixed(2)}</strong>`;

            document.querySelector('.cost-summary-subtotal').innerHTML =
                `Sub-Total:<strong>$${subTotal.toFixed(2)}</strong>`;
            document.querySelector('#modal-sub-total').value = subTotal.toFixed(2);
            document.querySelector('.cost-summary-tax').innerHTML =
                `Tax (${(taxRate * 100).toFixed(0)}%):<strong>$${tax.toFixed(2)}</strong>`;
            document.querySelector('#modal-tax').value = tax.toFixed(2);
            document.querySelector('.cost-summary-total').innerHTML =
                `TOTAL:<strong>$${total.toFixed(2)}</strong>`;
            document.querySelector('#modal-total-cost').value = total.toFixed(2);
        }


        function addCostCalculationListeners() {
            document.querySelectorAll('.product-amount').forEach(input => {
                input.addEventListener('input', updateCostSummary);
            });

            const deliveryField = document.getElementById('modal-pickup-or-delivery');
            if (deliveryField) deliveryField.addEventListener('change', updateCostSummary);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const dateInput = document.getElementById('modal-delivery-date');
            if (dateInput) {
                const today = new Date().toISOString().split('T')[0];
                dateInput.min = today;

                // Optional fallback: listen for manual changes
                dateInput.addEventListener('change', function () {
                    if (this.value < today) {
                        alert('You cannot select a past date.');
                        this.value = today;
                    }
                });
            }
        });

        // Form validation
        function validateForm() {
            const pickupOrDelivery = document.getElementById('modal-pickup-or-delivery').value;

            const requiredFields = [
                {id: 'modal-customer-name', label: 'Customer Name'},
                {id: 'modal-customer-email', label: 'Customer Email'},
                {id: 'modal-customer-phone', label: 'Customer Phone'},
                {id: 'modal-recurring', label: 'Recurring Option'},
                {id: 'modal-address', label: 'Address', dependsOnDelivery: true},
                {id: 'modal-city', label: 'City', dependsOnDelivery: true},
                {id: 'modal-postal', label: 'Postal Code', dependsOnDelivery: true},
                {id: 'modal-province', label: 'Province', dependsOnDelivery: true},
                {id: 'modal-delivery-date', label: 'Delivery Date'},
                {id: 'modal-pickup-or-delivery', label: 'Pickup or Delivery'}
            ];

            let isValid = true;
            let firstInvalidField = null;
            let missingFields = [];

            // Check required fields
            requiredFields.forEach(({id, label, dependsOnDelivery}) => {
                if (dependsOnDelivery && pickupOrDelivery === 'pickup') return;

                const field = document.getElementById(id);
                field.classList.remove('is-invalid');

                let fieldValue = field.value;

                if (id === 'modal-customer-email' && $('#modal-customer-email').hasClass('select2-hidden-accessible')) {
                    fieldValue = $('#modal-customer-email').val();
                }

                if (!fieldValue || !fieldValue.toString().trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = field;
                    missingFields.push(label);
                }
            });

            // Check if at least one product has amount > 0
            const productAmounts = document.querySelectorAll('.product-amount');
            let hasProducts = false;

            productAmounts.forEach(input => {
                if (parseFloat(input.value) > 0) {
                    hasProducts = true;
                }
            });

            if (!hasProducts) {
                isValid = false;
                missingFields.push('At least one product with amount > 0');
            }

            // Email format validation
            const emailField = document.getElementById('modal-customer-email');
            let emailValue = emailField.value;
            if ($('#modal-customer-email').hasClass('select2-hidden-accessible')) {
                emailValue = $('#modal-customer-email').val();
            }

            if (emailValue && !isValidEmail(emailValue)) {
                emailField.classList.add('is-invalid');
                isValid = false;
                if (!firstInvalidField) firstInvalidField = emailField;
                if (!missingFields.includes("Customer Email")) {
                    missingFields.push("Customer Email (Invalid Format)");
                }
            }

            if (!isValid && firstInvalidField) {
                firstInvalidField.focus();

                Swal.fire({
                    title: 'Validation Error',
                    html: 'Please fill in the following required field(s):<br><ul style="text-align: left;">' +
                        missingFields.map(field => `<li>${field}</li>`).join('') +
                        '</ul>',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            }

            return isValid;
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Handle Save/Update Button Click

        $(document).on('click','#save-order-btn',function (e) {
            e.preventDefault();

            if (!validateForm()) return;

            const saveBtn = this;
            const mode = this.dataset.mode;
            const orderId = this.dataset.orderId;
            const form = document.getElementById('order-form');

            // Store original text for reset
            const originalText = saveBtn.innerHTML;

            // Show loading state
            saveBtn.innerHTML = mode === 'edit' ?
                '<i class="la la-spinner la-spin"></i> Updating...' :
                '<i class="la la-spinner la-spin"></i> Creating...';
            saveBtn.disabled = true;

            // Set form action and method based on mode
            if (mode === 'edit') {
                form.action = `/admin/orders/${orderId}/ajax-update`;
            } else {
                form.action = '/admin/orders/ajax-create';
            }

            // Get form data
            const formData = new FormData(form);

            // Handle delivery date/time combination
            const date = document.getElementById('modal-delivery-date').value;
            const time = document.getElementById('modal-delivery-time').value;
            if (date && time) {
                formData.set('delivery_date', `${date} ${time}:00`);
            } else if (date) {
                formData.set('delivery_date', `${date} 00:00:00`);
            }

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                resetButton();
                return;
            }

            // Send AJAX request
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    resetButton();

                    if (data.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(summaryModal);
                        modal.hide();

                        // Show success notification
                        Swal.fire({
                            title: 'Success!',
                            text: mode === 'edit' ?
                                'Order has been updated successfully' :
                                'Order has been created successfully',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Operation failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    resetButton();

                    Swal.fire({
                        title: 'Error!',
                        text: error.message || 'Operation failed. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });

            function resetButton() {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            }
        });
        // Handle Delete Button Click

        $(document).on('click', '#delete-pushed-order-btn', function() {


            Swal.fire({
                title: 'Pushed!',
                text: 'This order has already been pushed to delivery service and cannot be deleted.',
                icon: 'warning',
                confirmButtonText: 'OK'
            }).then(() => {

            });

        });


        $(document).on('click', '#delete-order-btn', function() {
            const orderId = this.dataset.orderId;

            if (!orderId) {
                alert('Order ID not found');
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        return;
                    }

                    fetch(`order/${orderId}/delete/`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const modal = bootstrap.Modal.getInstance(summaryModal);
                                modal.hide();

                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Order has been deleted successfully',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Delete failed');
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting order:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: error.message || 'Failed to delete order. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                }
            });
        });
        let closestSupplier = null;

        // const deliveryOption = document.getElementById('modal-pickup-or-delivery');

        function getInput(id) {
            return document.getElementById(id)?.value?.trim();
        }

        function updateDeliveryCostSummary(amount) {
            // Update the form field instead of just display
            const deliveryCostField = document.getElementById('modal-delivery-cost');
            if (deliveryCostField) {
                deliveryCostField.value = amount !== null ? amount.toFixed(2) : 0 ;
            }

            // Update display
            const displayElement = document.querySelector('.cost-summary-delivery strong');
            if (displayElement) {
                displayElement.textContent = amount !== null ? `${amount.toFixed(2)}` : 'Not found';
            }

            // Update TOTAL only if amount is not null
            if (amount !== null) {
                let productTotal = 0;
                document.querySelectorAll('.product-amount').forEach(input => {
                    const inputAmount = parseFloat(input.value) || 0;
                    const unitPrice = parseFloat(input.dataset.unitPrice) || 0;
                    productTotal += inputAmount * unitPrice;
                });

                const hazmatText = parseFloat(document.getElementById('modal-hazmat-cost').value) || 0;
                const delivery = amount;

                const subtotal = productTotal;
                const tax = (subtotal + delivery) * 0.15;
                const total = subtotal + tax + delivery + hazmatText;

                document.querySelector('.cost-summary-subtotal strong').textContent = `${subtotal.toFixed(2)}`;
                document.querySelector('#modal-sub-total').value = subtotal.toFixed(2);
                document.querySelector('.cost-summary-tax strong').textContent = `${tax.toFixed(2)}`;
                document.querySelector('#modal-tax').value = tax.toFixed(2);
                document.querySelector('.cost-summary-total strong').textContent = `${total.toFixed(2)}`;
                document.querySelector('#modal-total-cost').value = total.toFixed(2);
            } else {
                // Reset totals when delivery cost can't be calculated
                document.querySelector('.cost-summary-subtotal strong').textContent = '$0.00';
                document.querySelector('#modal-sub-total').value = 0;
                document.querySelector('.cost-summary-tax strong').textContent = '$0.00';
                document.querySelector('#modal-tax').value = 0;
                document.querySelector('.cost-summary-total strong').textContent = '$0.00';
                document.querySelector('#modal-total-cost').value = 0;
            }
        }

        async function tryGetDeliveryQuote() {
            const formData = getFormData();

            let addressVerified = await validateAddressFields(formData);
            if (!addressVerified || !validateRequiredFields(formData)) {
                updateDeliveryCostSummary(null);
                return;
            }

            showLoadingState();

            getClosestSupplier(formData)
                .then(supplier => getDeliveryQuote(supplier, formData))
                .then(handleQuoteResponse)
                .catch(handleError);
        }

        function getFormData() {
            return {
                address: getInput('modal-address'),
                city: getInput('modal-city'),
                province: getInput('modal-province'),
                email: getInput('modal-customer-email'),
                name: getInput('modal-customer-name'),
                phone: getInput('modal-customer-phone'),
                iceAmount: parseFloat(getInput('modal-ice-amount')) || 1,
                postal: getInput('modal-postal'),
                locationName: getInput('modal-location-name'),
                unit: getInput('modal-unit') || ''
            };
        }

        function validateRequiredFields(formData) {
            const requiredFields = [formData.address, formData.city, formData.province, formData.postal];
            const isValid = requiredFields.every(val => val && val.trim());

            if (!isValid) {
                console.log('Missing required address fields for delivery calculation');
            }

            return isValid;
        }

        function showLoadingState() {
            const deliveryCostElement = document.querySelector('.cost-summary-delivery strong');
            if (deliveryCostElement) {
                deliveryCostElement.textContent = 'Calculating...';
            }
        }

        function getClosestSupplier(formData) {
            const url = `/test-closest-supplier?street=${encodeURIComponent(formData.address)}&city=${encodeURIComponent(formData.city)}&province=${encodeURIComponent(formData.province)}`;

            return fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Supplier API returned ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.closest_supplier || !data.closest_supplier.id) {
                        throw new Error('No supplier found in response');
                    }

                    const supplier = data.closest_supplier;

                    if($('#supplier_id').length) {
                        $('#supplier_id').val(supplier.id);
                    }

                    return supplier;
                });
        }

        function getDeliveryQuote(supplier, formData) {
            const quotePayload = {
                supplier_id: supplier.id,
                delivery: {
                    name: formData.locationName.trim() || 'N/A',
                    street: formData.address.trim(),
                    unit: formData.unit.trim() || '',
                    city: formData.city.trim(),
                    province: formData.province.trim(),
                    postal_code: formData.postal.trim(),
                    contact: formData.name.trim() || 'N/A',
                    phone: formData.phone.trim() || 'N/A',
                    email: formData.email.trim() || 'N/A'
                },
                weight: formData.iceAmount
            };

            return fetch('/get-delivery-quote', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(quotePayload)
            }).then(response => response.json());
        }

        function handleQuoteResponse(data) {
            const deliveryCostElement = document.querySelector('.cost-summary-delivery strong');

            if (data.success && data.total) {
                console.log('Quote successful, total:', data.total);
                updateDeliveryCostSummary(data.total);
                showSuccess(deliveryCostElement);
            } else {
                const errorMessages = extractErrorMessages(data);
                throw new Error(errorMessages);
            }
        }

        function extractErrorMessages(data) {
            if (!data?.data?.problems) {
                return 'Quote failed';
            }
            const problems = data.data.problems;
            const extractMessages = (problems) => {
                let messages = [];
                problems.forEach(problem => {
                    if (problem.message) messages.push(problem.message);
                    if (problem.problems) {
                        messages = messages.concat(extractMessages(problem.problems));
                    }
                });
                return messages;
            };

            return extractMessages(data.data.problems).join('\n');
        }

        function handleError(error) {
            console.error('Delivery quote error:', error);

            const deliveryCostElement = document.querySelector('.cost-summary-delivery strong');
            updateDeliveryCostSummary(null);
            showError(deliveryCostElement);

            Swal.fire({
                icon: 'error',
                title: 'Delivery Quote Error',
                text: error.message || 'Failed to get delivery quote',
                confirmButtonText: 'OK'
            });
        }

        function showSuccess(element) {
            if (element) {
                element.style.color = 'green';
                setTimeout(() => element.style.color = '', 2000);
            }
        }

        function showError(element) {
            if (element) {
                element.style.color = 'red';
                setTimeout(() => element.style.color = '', 3000);
            }
        }

        async function validateAddressFields(formData) {
            const apiKey = "{{config('services.google.address_api_key')}}";
            const response = await fetch(`https://addressvalidation.googleapis.com/v1:validateAddress?key=${apiKey}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    address: {
                        regionCode: "CA", // For Canada
                        addressLines: [formData.address],
                        locality: formData.city,
                        administrativeArea: formData.province,
                        postalCode: formData.postal
                    }
                })
            });

            const result = await response.json();
            if (result.result.verdict.possibleNextAction === "FIX" || result.result.verdict.hasUnconfirmedComponents) {
                Swal.fire({
                    title: 'Address Validation',
                    html: `Address could not be confirmed. Please provide proper address. <br>Street Address, City, Province, Postal`,
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#d33',
                });
                return false;
            } else {
                return true;
            }
        }

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('btn-view')) {
                const orderId = e.target.dataset.orderId;
                loadModalContent('edit', orderId);
            }
        });

        // // Setup event listeners for address fields that should trigger recalculation
        // function initializeDeliveryCalculation() {
        //     const deliveryOption = document.getElementById('modal-pickup-or-delivery');
        //
        //     // Check current state on page load
        //     if (deliveryOption && deliveryOption.value === 'delivery') {
        //         setupDeliveryCalculation();
        //         tryGetDeliveryQuote(); // Calculate immediately if delivery is already selected
        //     } else if (deliveryOption && deliveryOption.value === 'pickup') {
        //         updateDeliveryCostSummary(0); // Set to 0 for pickup
        //     }
        // }

        // // Initialize on page load
        // initializeDeliveryCalculation();
    });


    function tryPushOrderToNovex(orderId) {


        // Get orderId from modal if not provided as parameter
        if (!orderId) {
            const modalOrderId = document.getElementById('modal-order-id');
            if (modalOrderId && modalOrderId.value) {
                orderId = modalOrderId.value;
            } else {
                console.error('No order ID found');
                return;
            }
        }

        const pushButton = document.querySelector(`#push-btn-${orderId}`);
        const statusLabel = document.querySelector(`#push-status-${orderId}`);

        // Check if elements exist before manipulating them
        if (pushButton) {
            pushButton.disabled = true;
            pushButton.textContent = 'Pushing...';
        }

        fetch(`/orders/${orderId}/push-novex`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Push response:', data);

                if (data.success) {
                    if (statusLabel) {
                        statusLabel.textContent = '✔ Pushed';
                        statusLabel.style.color = 'green';
                    }
                    if (pushButton) {
                        pushButton.style.display = 'none';
                    }
                } else {
                    throw new Error(data.error || 'Push failed');
                }
            })
            .catch(error => {
                console.error('Push error:', error);
                if (statusLabel) {
                    statusLabel.textContent = '⚠ Push Failed';
                    statusLabel.style.color = 'orange';
                }
                if (pushButton) {
                    pushButton.disabled = false;
                    pushButton.textContent = 'Push Order';
                }
            });
    }


    function loadRecurringOrderDetails(action=0, orderId,recurring_id) {
        const url = `/admin/orders/modal/${orderId}/edit?recurring=`+action+`&recurring_id=`+recurring_id;

        // Show loading state
        document.getElementById('modal-content-container').innerHTML = `
                <div class="modal-body text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading...</p>
                </div>`;

        // Show the modal immediately with loading state
        $("#orderSummaryModal").modal('show');

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.text();
            })
            .then(html => {
                document.getElementById('modal-content-container').innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading modal content:', error);
                document.getElementById('modal-content-container').innerHTML = `
                    <div class="modal-body text-center">
                        <i class="la la-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Error Loading Content</h5>
                    </div>`;
            });
    }

    function hideOrderDetails(){
        $("#orderSummaryModal").modal('hide');
    }

</script>

@endpush
