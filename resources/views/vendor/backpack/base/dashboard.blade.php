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
  <div class="row flex-row mb-3">
    <div class="col-md-3">
      <div class="card dashboard text-center">
        <div class="card-header">
          Total Online Sales
        </div>
        <div class="card-body">
          <h2 class="title">${{ number_format($totalSalesOnline, 2) }}</h2>
            <p style="color: rgba(141, 141, 141, 1);">via CC or Online orders</p>
          <p class="card-text stat"><span>27.9% Up</span> from last year</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card dashboard text-center">
        <div class="card-header">
          Total Manual Sales
        </div>
        <div class="card-body">
          <h2 class="title">${{ number_format($totalSalesManual, 2) }}</h2>
            <p style="color: rgba(141, 141, 141, 1);">via Manual Payments</p>
            <p class="card-text stat"><span>26.6% Up</span> from last year</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card dashboard text-center">
        <div class="card-header">
          Dry Ice Units Sold
        </div>
        <div class="card-body">
          <h2 class="title">{{ number_format($dryIceUnitSold, 2) }} lbs</h2>
          <p class="card-text stat"><span>27.0% Up</span> from last year</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card dashboard text-center">
        <div class="card-header">
          Styrofoam Boxes Units Sold
        </div>
        <div class="card-body">
          <h2 class="title">{{ $styrofoamBoxUnitSold }} boxes</h2>
          <p class="card-text stat"><span>17.4% Up</span> from last year</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="table">
        <div class="table-header">
          Last Orders
        </div>
        <table>
          <thead>
            <tr>
              <th>Order #</th>
              <th>Customer</th>
              <!-- <th>Address</th> -->
              <th>Delivery Date</th>
              <!-- <th>Ice</th> -->
              <!-- <th>Box</th> -->
              <th>Status</th>
              <th>Total</th>
              <th>Origin</th>
            </tr>
          </thead>
          <tbody>
            @foreach($lastOrders as $order)
            <tr data-href="{{ url('admin/orders/' . $order->id . '/show') }}">
              <td>{{ $order->id }}</td>
              <td>{{ $order->customer_name }}</td>
              <!-- <td>{{ $order->address }}</td> -->
              <td>{{ $order->delivery_date }}</td>
              <!-- <td>{{ $order->amount_of_ice }} lbs</td> -->
              <!-- <td>{{ $order->amount_of_boxes }}</td> -->
              <td class="status">{{ $order->status }}</td>
              <td>${{ $order->total_cost }}</td>
              <td>{{ $order->origin }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="col-md-6">
      <div class="table mb-3">
        <div class="table-header">
            CC orders
        </div>
        <table>
          <thead>
            <tr>
{{--              <th>Order #</th>--}}
              <th>Customer</th>
              <th>Delivery Date</th>
              <th>Ice</th>
              <th>Box</th>
              <th>Address</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($ccOrders as $order)
{{-- data-href="{{ url('admin/orders/' . $order->id . '/show') }}"  --}}
            <tr>
{{--              <td>{{ $order->id }}</td>--}}
              <td>{{ $order->customer_name }}</td>
              <td>{{ $order->delivery_date }}</td>
              <td>{{ $order->amount_of_ice }} lbs</td>
              <td>{{ $order->amount_of_boxes }}</td>
              <td>{{ $order->address }}</td>
                <td>
                    @php
                        $dateTime = \Carbon\Carbon::parse($order->delivery_date);
                    @endphp
                    <button class="btn btn-primary btn-view"
                            data-bs-toggle="modal"
                            data-bs-target="#orderSummaryModal"
                            data-id="{{ $order->id }}"
                            data-customer="{{ $order->customer_name }}"
                            data-email="{{ $order->email }}"
                            data-phone="{{ $order->phone }}"
                            data-ice="{{ $order->amount_of_ice }}"
                            data-boxes="{{ $order->amount_of_boxes }}"
                            data-recurring="{{ $order->recurring }}"
                            data-location="{{ $order->location_name }}"
                            data-address="{{ $order->address }}"
                            data-unit="{{ $order->unit }}"
                            data-city="{{ $order->city }}"
                            data-postal_code="{{ $order->postal_code }}"
                            data-province="{{ $order->province }}"
                            data-country="{{ $order->country }}"
                            data-delivery-date="{{ $dateTime->format('Y-m-d') }}"
                            data-delivery-time="{{ $dateTime->format('H:i') }}"
                            data-notes="{{ $order->notes }}"
                            data-status="{{ $order->status }}"
                            data-pickup_delivery="{{ $order->pickup_delivery }}"
                    >View</button>
                </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>


        <div class="table recurring">
        <div class="table-header">
          Recurring Orders
        </div>
        <table>
          <thead>
            <tr>
              <th>Order #</th>
              <th>Customer</th>
              <!-- <th>Address</th> -->
              <th>Delivery Date</th>
              <!-- <th>Ice</th> -->
              <!-- <th>Box</th> -->
              <th>Status</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recurringOrders as $order)
            <tr data-href="{{ url('admin/orders/' . $order->id . '/show') }}">
              <td>{{ $order->id }}</td>
              <td>{{ $order->customer_name }}</td>
              <!-- <td>{{ $order->address }}</td> -->
              <td>{{ $order->delivery_date }}</td>
              <!-- <td>{{ $order->amount_of_ice }} lbs</td> -->
              <!-- <td>{{ $order->amount_of_boxes }}</td> -->
              <td class="status">{{ $order->status }}</td>
              <td>${{ $order->total_cost }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="orderSummaryModal" tabindex="-1" aria-labelledby="orderSummaryLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title fw-bold" id="modal-title">
                    <i class="la la-file-invoice mx-2"></i>
                    <span id="modal-title-text">Create New Order</span>
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <!-- Order Details -->
                    <div class="col-md-3">
                        <h5><i class="la la-shopping-cart"></i> Order</h5>

                        <!-- Order ID field - only shown in edit mode -->
                        <div class="mb-2" id="order-id-section" style="display: none;">
                            <label class="form-label">Order #</label>
                            <input id="modal-order-id" class="form-control" readonly>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <select id="modal-customer-email" class="form-control" style="width: 100%;" required>
                                <!-- Options will be populated by Select2 -->
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input id="modal-customer-name" class="form-control" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input id="modal-customer-phone" class="form-control" type="tel" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Amount of Ice (lbs) <span class="text-danger">*</span></label>
                            <input id="modal-ice-amount" class="form-control" type="number" min="1" step="0.1" placeholder="Enter lbs" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Amount of Boxes</label>
                            <input id="modal-box-amount" class="form-control" type="number" min="0" step="1" value="0">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Recurring <span class="text-danger">*</span></label>
                            <select id="modal-recurring" class="form-select">
                                <option value="">Select...</option>
                                <option value="recurring">Yes</option>
                                <option value="non-recurring">No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Delivery Details -->
                    <div class="col-md-5 px-3">
                        <h5><i class="la la-truck"></i> Delivery</h5>
                        <div class="mb-2">
                            <label class="form-label">Location Name <span class="text-danger">*</span></label>
                            <input id="modal-location-name" type="text" class="form-control" required>
                        </div>
                        <div class="row mb-2">
                            <div class="col-8">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <input id="modal-address" class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label class="form-label">Unit</label>
                                <input id="modal-unit" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label class="form-label">City <span class="text-danger">*</span></label>
                                <input id="modal-city" type="text" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Postal <span class="text-danger">*</span></label>
                                <input id="modal-postal" type="text" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Province <span class="text-danger">*</span></label>
                                <select id="modal-province" class="form-select" required>
                                    <option value="">Select...</option>
                                    <option value="BC">BC</option>
                                    <option value="AB">AB</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Country</label>
                                <input id="modal-country" class="form-control" value="Canada" readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5">
                                <label class="form-label">Pickup or Delivery <span class="text-danger">*</span></label>
                                <select id="modal-pickup-or-delivery" class="form-select" required>
                                    <option value="">Select...</option>
                                    <option value="pickup">Pick Up</option>
                                    <option value="delivery">Delivery</option>
                                </select>
                            </div>
                            <div class="col-4" id="status-section">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="modal-status">
                                    <option value="valid">Valid</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-5">
                                <label class="form-label">Delivery Date<span class="text-danger">*</span></label>
                                <input id="modal-delivery-date" type="date" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Delivery Time</label>
                                <input id="modal-delivery-time" type="time" class="form-control">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Notes</label>
                            <textarea id="modal-notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Cost Summary -->
                    <div class="col-md-4">
                        <h5><i class="la la-dollar-sign"></i> Cost Summary</h5>
                        <div class="p-3 rounded" style="background: rgba(245, 246, 250, 1);">
                            <div class="d-flex justify-content-between align-items-center m-1 cost-summary-ice">
                                <p class="m-0">Dry Ice (0 lbs @ $1.95/lb):</p>
                                <strong>$0.00</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center m-1 cost-summary-box">
                                <p class="m-0">Styrofoam Box (0 @ $30.00/box): </p>
                                <strong>$0.00</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center m-1 cost-summary-delivery">
                                <p class="m-0">Pickup/Delivery: </p>
                                <strong>$0.00</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center m-1 cost-summary-subtotal">
                                <p class="m-0">Sub-Total: </p>
                                <strong>$0.00</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center m-1 cost-summary-tax">
                                <p class="m-0">Tax (15%):  </p>
                                <strong>$0.00</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center m-1 cost-summary-total">
                                <p class="m-0">TOTAL: </p>
                                <strong>$0.00</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 d-flex justify-content-between">
                <div>
                    <button id="save-order-btn" class="btn btn-primary">
                        <i class="la la-save"></i> <span id="save-btn-text">Create Order</span>
                    </button>
                    @if(isset($order) && $order)
                        <button id="push-btn-{{ $order->id }}" onclick="tryPushOrderToNovex({{ $order->id }})" class="btn btn-primary button-push" style="background: gray">
                            Push Order
                        </button>
                        <span id="push-status-{{ $order->id }}" class="ml-2 text-sm text-muted status-push"></span>
                    @endif
                    <button class="btn btn-secondary mx-2" data-bs-dismiss="modal">Cancel</button>
                </div>
                <button id="delete-order-btn" class="btn btn-danger" style="display: none;">
                    <i class="la la-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Modal -->


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

    .row.flex-row {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }

    .row.flex-row > .col-md-3 {
      flex: 1;
      min-width: 250px;
      display: flex;
    }

    .card {
      flex: 1;
      border: none;
      border-radius: 8px;
      box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
      background-color: #221e26;
    }

    .card-header {
      --bs-bg-opacity: 1 !important;
      background-color: #221e26;
      color: #ffffff;
      border-top-left-radius: 8px !important;
      border-top-right-radius: 8px !important;
        font-size: 16px;
        font-weight: 700;
    }

    .card-body {
      background-color: white;
      position: relative;
      padding: 24px 32px 16px;
      text-align: left;
      height: 130px;
      border-bottom-left-radius: 8px;
      border-bottom-right-radius: 8px;
    }

    .card-body h2.title {
      font-weight: 800;
        font-size: 30px;
    }

    .card-body .card-text {
      color: var(--tblr-card-text);
      font-size: 14px;
      transform: translateY(-40%);
    }

    .card.dashboard .card-body .card-text.stat {
      position: absolute;
      bottom: 5px;
      left: 32px;
      font-size: 16px;
      width: fit-content;
    }

    .card-body .card-text span {
      color: var(--tblr-success);
      margin-right: 4px;
    }

    .card .card-body.inventory {
      height: fit-content;
      padding: 16px 64px;
    }

    .card .card-body .stat {
      display: flex;
      justify-content: space-between;
      width: 100%;
      align-items: center;
      text-align: center;
    }

    .card .card-body .stat.right {
      flex-direction: column;
    }

    .card .card-body .stat h5 {
      display: flex;
      flex-direction: column;
      text-transform: uppercase;
      font-size: 12px
    }

    .card .card-body .stat h5 span {
      font-size: 24px;
      font-weight: 700;
    }

    .row .table-header {
        font-weight: 500;
        font-size: 24px;
        line-height: 36px;
        letter-spacing: -0.11px;
    }

    .row .table-head-wrapper {
      overflow: hidden;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
    }

    .row table {
      width: 100%;
      margin-bottom: 0;
      border-collapse: separate;
      border-spacing: 0 15px;
    }

    .row table thead tr {
      --bs-bg-opacity: 1 !important;
      background-color: #221e26 !important;
      color: var(--tblr-secondary-text-emphasis);
      text-transform: capitalize;
      padding: 10px 0;
    }

    .row table thead tr th {
      color: white;
      text-transform: capitalize;
      font-size: 14px;
      font-weight: 500;
    }

    .row table th,
    .row table td {
      font-size: 14px;
      padding: 12px 15px;
      vertical-align: middle;
    }

    .row table tbody tr {
      background-color: white;
      color: var(--tblr-body-color);
      box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
      padding: 10px 5px;
    }

    .row table tbody tr td {
      padding: 15px;
    }

    .row table tbody tr:hover {
      background-color: var(--tblr-secondary-bg-subtle);
    }

    .row .table.recurring {
      margin-top: 38px;
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

    td.status {
      text-transform: capitalize;
      transform: translateY(10px);
    }

    .status.valid {
      color: var(--tblr-success) !important;
      font-weight: bold;
    }

    .status.skip {
      color: var(--tblr-warning);
      font-weight: bold;
    }

    .status.cancelled {
      color: var(--tblr-danger);
      font-weight: bold;
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

</style>

@endsection

@push('after_scripts')
@vite(['resources/js/app.js'])

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            prepareModalForCreate();
        });

        // Edit Order buttons (existing functionality)
        document.querySelectorAll('.btn-view').forEach(function(btn) {
            btn.addEventListener('click', function() {
                prepareModalForEdit(this);
            });
        });

        function prepareModalForCreate() {
            isEditMode = false;

            // Update modal title and button text
            document.getElementById('modal-title-text').textContent = 'Create New Order';
            document.getElementById('save-btn-text').textContent = 'Create Order';

            // Hide order ID section and delete button
            document.getElementById('order-id-section').style.display = 'none';
            document.getElementById('delete-order-btn').style.display = 'none';

            // Hide push button and status in create mode
            document.querySelectorAll('.button-push').forEach(btn => btn.style.display = 'none');
            document.querySelectorAll('.status-push').forEach(status => status.style.display = 'none');

            // Clear all form fields
            clearModalForm();

            // Set default values
            document.getElementById('modal-country').value = 'Canada';
            document.getElementById('modal-box-amount').value = '0';
            document.getElementById('modal-status').value = 'valid';

            // Calculate initial cost
            updateCostSummary();
        }

        function prepareModalForEdit(btn) {
            isEditMode = true;
            const orderId = btn.dataset.id; // Get the order ID from the button

            // Update modal title and button text
            document.getElementById('modal-title-text').textContent = 'Edit Order';
            document.getElementById('save-btn-text').textContent = 'Update Order';

            // Show order ID section and delete button
            document.getElementById('order-id-section').style.display = 'block';
            document.getElementById('delete-order-btn').style.display = 'block';

            // Show push button and status in edit mode
            document.querySelectorAll('.button-push').forEach(btn => {
                btn.style.display = 'inline-block';
                btn.dataset.orderId = orderId;
            });
            document.querySelectorAll('.status-push').forEach(status => {
                status.style.display = 'inline';
            });

            // Populate form with existing data
            document.getElementById('modal-order-id').value = orderId;

            // For email field in edit mode, we need to handle Select2 differently
            const customerEmail = btn.dataset.email || '';
            if (customerEmail) {
                // Create option and set it as selected
                const emailOption = new Option(customerEmail, customerEmail, true, true);
                $('#modal-customer-email').append(emailOption).trigger('change');
            }
            $('#modal-customer-email').prop('disabled', isEditMode);

            document.getElementById('modal-customer-name').value = btn.dataset.customer || '';
            document.getElementById('modal-customer-phone').value = btn.dataset.phone || '';
            document.getElementById('modal-ice-amount').value = btn.dataset.ice || '';
            document.getElementById('modal-box-amount').value = btn.dataset.boxes || '0';
            document.getElementById('modal-recurring').value = btn.dataset.recurring || '';
            document.getElementById('modal-location-name').value = btn.dataset.location || '';
            document.getElementById('modal-address').value = btn.dataset.address || '';
            document.getElementById('modal-unit').value = btn.dataset.unit || '';
            document.getElementById('modal-city').value = btn.dataset.city || '';
            document.getElementById('modal-postal').value = btn.dataset.postal_code || '';
            document.getElementById('modal-province').value = btn.dataset.province || '';
            document.getElementById('modal-country').value = btn.dataset.country || 'Canada';
            document.getElementById('modal-delivery-date').value = btn.dataset.deliveryDate || '';
            document.getElementById('modal-delivery-time').value = btn.dataset.deliveryTime || '';
            document.getElementById('modal-notes').value = btn.dataset.notes || '';
            document.getElementById('modal-status').value = btn.dataset.status || 'valid';
            document.getElementById('modal-pickup-or-delivery').value = btn.dataset.pickup_delivery || '';

            // Store order ID for operations
            document.getElementById('save-order-btn').dataset.orderId = orderId;
            document.getElementById('delete-order-btn').dataset.orderId = orderId;

            // Calculate and display costs
            updateCostSummary();
        }

        function clearModalForm() {
            // Clear Select2 email field
            if ($('#modal-customer-email').hasClass('select2-hidden-accessible')) {
                $('#modal-customer-email').val(null).trigger('change');
            } else {
                document.getElementById('modal-customer-email').value = '';
            }

            $('#modal-customer-email').prop('disabled', false);

            // Clear all other input fields
            document.querySelectorAll('#orderSummaryModal input').forEach(input => {
                if (input.id !== 'modal-customer-email') { // Skip email field as it's handled above
                    if (input.type === 'number') {
                        input.value = '0';
                    } else if (input.type !== 'readonly') {
                        input.value = '';
                    }
                }
            });

            // Clear select fields
            document.querySelectorAll('#orderSummaryModal select').forEach(select => {
                if (select.id !== 'modal-customer-email') { // Skip email field
                    select.selectedIndex = 0;
                }
            });

            // Clear textarea
            document.querySelectorAll('#orderSummaryModal textarea').forEach(textarea => {
                textarea.value = '';
            });

            // Remove stored order IDs
            document.getElementById('save-order-btn').removeAttribute('data-order-id');
            document.getElementById('delete-order-btn').removeAttribute('data-order-id');
        }

        // Dynamic cost calculation
        function updateCostSummary() {
            const iceAmount = parseFloat(document.getElementById('modal-ice-amount').value) || 0;
            const boxAmount = parseFloat(document.getElementById('modal-box-amount').value) || 0;
            const pickupDelivery = document.getElementById('modal-pickup-or-delivery').value;

            const pricePerLb = 1.95;
            const pricePerBox = 30.00;
            const deliveryFee = pickupDelivery === 'delivery' ? 20.00 : 0.00;

            const iceCost = iceAmount * pricePerLb;
            const boxCost = boxAmount * pricePerBox;
            const subTotal = iceCost + boxCost + deliveryFee;
            const taxRate = 0.15;
            const tax = subTotal * taxRate;
            const total = subTotal + tax;

            // Update the cost summary section
            document.querySelector('.cost-summary-ice').innerHTML =
                `<p class="m-0">Dry Ice (${iceAmount} lbs @ $${pricePerLb.toFixed(2)}/lb):</p>
     <strong>$${iceCost.toFixed(2)}</strong>`;

            document.querySelector('.cost-summary-box').innerHTML =
                `<p class="m-0">Styrofoam Box (${boxAmount} @ $${pricePerBox.toFixed(2)}/box):</p>
     <strong>$${boxCost.toFixed(2)}</strong>`;

            document.querySelector('.cost-summary-delivery').innerHTML =
                `<p class="m-0">Pickup/Delivery:</p>
     <strong>$${deliveryFee.toFixed(2)}</strong>`;

            document.querySelector('.cost-summary-subtotal').innerHTML =
                `<p class="m-0">Sub-Total:</p>
     <strong>$${subTotal.toFixed(2)}</strong>`;

            document.querySelector('.cost-summary-tax').innerHTML =
                `<p class="m-0">Tax (${(taxRate * 100).toFixed(0)}%):</p>
     <strong>$${tax.toFixed(2)}</strong>`;

            document.querySelector('.cost-summary-total').innerHTML =
                `<p class="m-0">TOTAL:</p>
     <strong>$${total.toFixed(2)}</strong>`;
        }

        // Add event listeners for cost calculation
        document.getElementById('modal-ice-amount').addEventListener('input', updateCostSummary);
        document.getElementById('modal-box-amount').addEventListener('input', updateCostSummary);
        document.getElementById('modal-pickup-or-delivery').addEventListener('change', updateCostSummary);

        // Form validation
        function validateForm() {
            const requiredFields = [
                { id: 'modal-customer-name', label: 'Customer Name' },
                { id: 'modal-customer-email', label: 'Customer Email' },
                { id: 'modal-customer-phone', label: 'Customer Phone' },
                { id: 'modal-ice-amount', label: 'Amount of Ice' },
                { id: 'modal-recurring', label: 'Recurring Option' },
                { id: 'modal-location-name', label: 'Location Name' },
                { id: 'modal-address', label: 'Address' },
                { id: 'modal-city', label: 'City' },
                { id: 'modal-postal', label: 'Postal Code' },
                { id: 'modal-province', label: 'Province' },
                { id: 'modal-delivery-date', label: 'Delivery Date' },
                { id: 'modal-pickup-or-delivery', label: 'Pickup or Delivery' }
            ];

            let isValid = true;
            let firstInvalidField = null;
            let missingFields = [];

            requiredFields.forEach(({ id, label }) => {
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

                // Special validation for ice amount > 0
                if (id === 'modal-ice-amount' && parseFloat(fieldValue) <= 0) {
                    field.classList.add('is-invalid');
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = field;
                    if (!missingFields.includes(label)) {
                        missingFields.push(`${label} must be greater than 0`);
                    }
                }
            });

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
        document.getElementById('save-order-btn').addEventListener('click', function() {
            if (!validateForm()) return;

            const saveBtn = this;
            const originalText = saveBtn.innerHTML;

            // Show loading state
            saveBtn.innerHTML = isEditMode ?
                '<i class="la la-spinner la-spin"></i> Updating...' :
                '<i class="la la-spinner la-spin"></i> Creating...';
            saveBtn.disabled = true;

            // Collect form data
            const formData = new FormData();

            if (isEditMode) {
                formData.append('_method', 'PUT');
            }

            // Get email value from Select2
            let emailValue = document.getElementById('modal-customer-email').value;
            if ($('#modal-customer-email').hasClass('select2-hidden-accessible')) {
                emailValue = $('#modal-customer-email').val();
            }

            formData.append('customer_name', document.getElementById('modal-customer-name').value);
            formData.append('email', emailValue);
            formData.append('phone', document.getElementById('modal-customer-phone').value);
            formData.append('amount_of_ice', document.getElementById('modal-ice-amount').value);
            formData.append('amount_of_boxes', document.getElementById('modal-box-amount').value);
            formData.append('recurring', document.getElementById('modal-recurring').value);
            formData.append('location_name', document.getElementById('modal-location-name').value);
            formData.append('address', document.getElementById('modal-address').value);
            formData.append('unit', document.getElementById('modal-unit').value);
            formData.append('city', document.getElementById('modal-city').value);
            formData.append('postal_code', document.getElementById('modal-postal').value);
            formData.append('province', document.getElementById('modal-province').value);
            formData.append('country', document.getElementById('modal-country').value);
            formData.append('notes', document.getElementById('modal-notes').value);
            formData.append('status', document.getElementById('modal-status').value);
            formData.append('pickup_delivery', document.getElementById('modal-pickup-or-delivery').value);

            // Handle delivery date/time
            const date = document.getElementById('modal-delivery-date').value;
            const time = document.getElementById('modal-delivery-time').value;
            if (date && time) {
                formData.append('delivery_date', `${date} ${time}:00`);
            } else if (date) {
                formData.append('delivery_date', `${date} 00:00:00`);
            } else {
                formData.append('delivery_date', '');
            }

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                resetButton();
                return;
            }

            // Determine URL and method
            const baseUrl = '{{ url("admin/orders") }}';
            let url, method;

            if (isEditMode) {
                const orderId = this.dataset.orderId;
                url = `${baseUrl}/${orderId}/ajax-update`;
                method = 'POST';
            } else {
                url = `${baseUrl}/ajax-create`;
                method = 'POST';
            }

            // Send AJAX request
            fetch(url, {
                method: method,
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
                            text: isEditMode ?
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
        document.getElementById('delete-order-btn').addEventListener('click', function() {
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

                    fetch(`{{ url('admin/orders') }}/${orderId}/ajax-delete`, {
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
    });

    document.addEventListener('DOMContentLoaded', function () {
        let closestSupplier = null;

        const deliveryOption = document.getElementById('modal-pickup-or-delivery');

        function getInput(id) {
            return document.getElementById(id)?.value?.trim();
        }

        function updateDeliveryCostSummary(amount) {
            document.querySelector('.cost-summary-delivery strong').textContent = `$${amount.toFixed(2)}`;

            // Update TOTAL
            const dryIceText = document.querySelector('.cost-summary-ice strong').textContent.replace('$', '') || 0;
            const boxText = document.querySelector('.cost-summary-box strong').textContent.replace('$', '') || 0;
            const delivery = amount;

            const subtotal = parseFloat(dryIceText) + parseFloat(boxText) + delivery;
            const tax = subtotal * 0.15;
            const total = subtotal + tax;

            document.querySelector('.cost-summary-subtotal strong').textContent = `$${subtotal.toFixed(2)}`;
            document.querySelector('.cost-summary-tax strong').textContent = `$${tax.toFixed(2)}`;
            document.querySelector('.cost-summary-total strong').textContent = `$${total.toFixed(2)}`;
        }

        function tryGetDeliveryQuote() {
            // Get form values
            const formData = {
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

            // Check required fields
            const required = [formData.address, formData.city, formData.province, formData.email, formData.name, formData.phone, formData.postal, formData.locationName];
            if (!required.every(val => val && val.trim())) {
                console.log('Missing required fields');
                return;
            }

            // Show loading
            const deliveryCostElement = document.querySelector('.cost-summary-delivery strong');
            deliveryCostElement.textContent = 'Calculating...';

            console.log('Starting delivery quote request...');

            // Get closest supplier first
            fetch(`/test-closest-supplier?street=${encodeURIComponent(formData.address)}&city=${encodeURIComponent(formData.city)}&province=${encodeURIComponent(formData.province)}`)
                .then(response => {
                    console.log('Supplier response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`Supplier API returned ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {

                    // Extract supplier from response
                    if (!data.closest_supplier || !data.closest_supplier.id) {
                        throw new Error('No supplier found in response');
                    }

                    const supplier = data.closest_supplier;


                    // Get delivery quote
                    const quotePayload = {
                        supplier_id: supplier.id,
                        delivery: {
                            name: formData.locationName.trim(),
                            street: formData.address.trim(),
                            unit: formData.unit.trim() || '', // Ensure it's always a string
                            city: formData.city.trim(),
                            province: formData.province.trim(),
                            postal_code: formData.postal.trim(),
                            contact: formData.name.trim(),
                            phone: formData.phone.trim(),
                            email: formData.email.trim()
                        },
                        weight: formData.iceAmount
                    };

                    console.log('Quote payload:', quotePayload);

                    return fetch('/get-delivery-quote', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(quotePayload)
                    });
                })
                .then(response => {
                    console.log('Quote response status:', response.status);

                    // Add response text logging for debugging
                    return response.text().then(text => {
                        console.log('Quote response text:', text);

                        if (!response.ok) {
                            throw new Error(`Quote API returned ${response.status}: ${text}`);
                        }

                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Failed to parse JSON:', e);
                            throw new Error('Invalid JSON response from quote API');
                        }
                    });
                })
                .then(data => {
                    console.log('Quote data received:', data);

                    if (data.success && data.total) {
                        console.log('Quote successful, total:', data.total);
                        updateDeliveryCostSummary(data.total);
                        showSuccess(deliveryCostElement);
                    } else {
                        console.error('Quote unsuccessful:', data);
                        throw new Error(data.error || 'Quote failed');
                    }
                })
                .catch(error => {
                    console.error('Full error details:', error);
                    console.error('Error message:', error.message);
                    updateDeliveryCostSummary(20.00); // Default fallback
                    showError(deliveryCostElement);
                });
        }

        function showSuccess(element) {
            element.style.color = 'green';
            setTimeout(() => element.style.color = '', 2000);
        }

        function showError(element) {
            element.style.color = 'orange';
            setTimeout(() => element.style.color = '', 3000);
        }

        deliveryOption.addEventListener('change', function () {
            if (this.value === 'delivery') {
                // Setup auto listener to check fields and trigger quote
                const inputs = ['modal-address', 'modal-city', 'modal-province', 'modal-customer-email', 'modal-customer-name', 'modal-customer-phone', 'modal-ice-amount', 'modal-postal', 'modal-location-name'];

                inputs.forEach(id => {
                    document.getElementById(id)?.addEventListener('input', () => {
                        clearTimeout(window.__quoteTimer);
                        window.__quoteTimer = setTimeout(tryGetDeliveryQuote, 500); // debounce
                    });
                });

                // Initial trigger in case fields are already filled
                tryGetDeliveryQuote();
            }
        });
    });



    function tryPushOrderToNovex(orderId) {
        // Check if orderId is valid
        if (!orderId || orderId === '' || orderId === 'undefined') {
            console.error('Invalid order ID provided');
            return;
        }

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




    document.addEventListener('DOMContentLoaded', function () {
        const dateInput = document.getElementById('modal-delivery-date');
        const today = new Date();

        // Force the timezone offset so it's truly today's date even if user is in a different timezone
        const localISODate = new Date(today.getTime() - today.getTimezoneOffset() * 60000)
            .toISOString()
            .split('T')[0];

        if (dateInput) {
            dateInput.setAttribute('min', localISODate);
        }
    });



</script>
@endpush
