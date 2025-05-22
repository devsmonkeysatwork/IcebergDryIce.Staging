@extends(backpack_view('blank'))

@section('header')
<section class="container-fluid header">
  <h2 class="title text-capitalize">
    Dashboard
  </h2>
    <small>
        <a href="{{ url('admin/manual-payments/create') }}" class="btn btn-add btn-sm mx-3 btn-manual"><i class="la la-wallet mx-2"></i> Manual Payment</a>

        <a href="{{ url('admin/orders/create') }}" class="btn btn-add btn-sm"><i class="la la-plus mx-2"></i> New Order</a>
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
                <h4 class="modal-title fw-bold"><i class="la la-file-invoice mx-2"></i> CC Order Summary</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <!-- Order Details -->
                    <div class="col-md-3">
                        <h5><i class="la la-shopping-cart"></i> Order</h5>
                        <div class="mb-2">
                            <label class="form-label">Order #</label>
                            <input id="modal-order-id" class="form-control" readonly>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Name</label>
                            <input id="modal-customer-name" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email</label>
                            <input id="modal-customer-email" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Phone</label>
                            <input id="modal-customer-phone" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Amount of Ice</label>
                            <input id="modal-ice-amount" class="form-control" type="number" min="0" step="1">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Amount of Boxes</label>
                            <input id="modal-box-amount" class="form-control" type="number" min="0" step="1">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Recurring</label>
                            <select id="modal-recurring" class="form-select">
                                <option value=""></option>
                                <option value="recurring">Yes</option>
                                <option value="non-recurring">No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Delivery Details -->
                    <div class="col-md-5 px-3">
                        <h5><i class="la la-truck"></i> Delivery</h5>
                        <div class="mb-2">
                            <label class="form-label">Location Name</label>
                            <input type="text" class="form-control" value="Residence">
                        </div>
                        <div class="row mb-2">
                            <div class="col-8">
                                <label class="form-label">Address</label>
                                <input id="modal-address" class="form-control">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Unit</label>
                                <input id="modal-unit" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label class="form-label">City</label>
                                <input id="modal-city" type="text" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Postal</label>
                                <input id="modal-postal" type="text" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Province</label>
                                <select id="modal-province" class="form-select">
                                    <option value="BC">BC</option>
                                    <option value="AB">AB</option>
                                    <option value="ON">ON</option>
                                    <option value="QC">QC</option>
                                    <!-- Add more provinces as needed -->
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Country</label>
                                <input id="modal-country" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5">
                                <label class="form-label">Pickup or Delivery</label>
                                <select id="modal-pickup-or-delivery" class="form-select">
                                    <option value="pickup">Pick Up</option>
                                    <option value="delivery">Delivery</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="modal-status">
                                    <option value="valid">Valid</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-5">
                                <label class="form-label">Delivery Date</label>
                                <input id="modal-delivery-date" type="date" class="form-control">
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
                    <button class="btn btn-primary" id="update-order-btn">Update</button>
                    <button class="btn btn-dark mx-2">Push</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
                <button class="btn btn-danger" id="delete-order-btn">Delete</button>
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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    document.addEventListener('DOMContentLoaded', function() {
        let summaryModal = document.getElementById("orderSummaryModal");
        let sidebar = document.querySelector("aside.navbar-vertical");

        summaryModal.addEventListener("show.bs.modal", function() {
            if (sidebar) {
                sidebar.style.zIndex = "-1";
            }
        });

        summaryModal.addEventListener("hidden.bs.modal", function() {
            if (sidebar) {
                sidebar.style.zIndex = "1030";
            }
        });

        // Populate modal with order data
        document.querySelectorAll('.btn-view').forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Populate order details
                document.getElementById('modal-order-id').value = this.dataset.id;
                document.getElementById('modal-customer-name').value = this.dataset.customer;
                document.getElementById('modal-customer-email').value = this.dataset.email;
                document.getElementById('modal-customer-phone').value = this.dataset.phone;
                document.getElementById('modal-ice-amount').value = this.dataset.ice;
                document.getElementById('modal-box-amount').value = this.dataset.boxes;
                document.getElementById('modal-recurring').value = this.dataset.recurring;

                // Populate delivery details
                document.getElementById('modal-address').value = this.dataset.address;
                document.getElementById('modal-unit').value = this.dataset.unit;
                document.getElementById('modal-city').value = this.dataset.city;
                document.getElementById('modal-postal').value = this.dataset.postal_code;
                document.getElementById('modal-province').value = this.dataset.province;
                document.getElementById('modal-country').value = this.dataset.country;
                document.getElementById('modal-delivery-date').value = this.dataset.deliveryDate;
                document.getElementById('modal-delivery-time').value = this.dataset.deliveryTime;
                document.getElementById('modal-notes').value = this.dataset.notes;
                document.getElementById('modal-status').value = this.dataset.status;
                document.getElementById('modal-pickup-or-delivery').value = this.dataset.pickup_delivery;

                // Calculate and display order costs
                updateCostSummary();

                // Store the order ID for update and delete operations
                document.getElementById('update-order-btn').dataset.orderId = this.dataset.id;
                document.getElementById('delete-order-btn').dataset.orderId = this.dataset.id;
            });
        });

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
            document.querySelector('#orderSummaryModal .modal-body .cost-summary-ice').innerHTML =
                `<p class="m-0">Dry Ice (${iceAmount} lbs @ $${pricePerLb.toFixed(2)}/lb):</p>
             <strong>$${iceCost.toFixed(2)}</strong>`;

            document.querySelector('#orderSummaryModal .modal-body .cost-summary-box').innerHTML =
                `<p class="m-0">Styrofoam Box (${boxAmount} @ $${pricePerBox.toFixed(2)}/box):</p>
             <strong>$${boxCost.toFixed(2)}</strong>`;

            document.querySelector('#orderSummaryModal .modal-body .cost-summary-delivery').innerHTML =
                `<p class="m-0">Pickup/Delivery:</p>
             <strong>$${deliveryFee.toFixed(2)}</strong>`;

            document.querySelector('#orderSummaryModal .modal-body .cost-summary-subtotal').innerHTML =
                `<p class="m-0">Sub-Total:</p>
             <strong>$${subTotal.toFixed(2)}</strong>`;

            document.querySelector('#orderSummaryModal .modal-body .cost-summary-tax').innerHTML =
                `<p class="m-0">Tax (${(taxRate * 100).toFixed(0)}%):</p>
             <strong>$${tax.toFixed(2)}</strong>`;

            document.querySelector('#orderSummaryModal .modal-body .cost-summary-total').innerHTML =
                `<p class="m-0">TOTAL:</p>
             <strong>$${total.toFixed(2)}</strong>`;
        }

        // Add event listeners to inputs that affect cost calculation
        document.getElementById('modal-ice-amount').addEventListener('change', updateCostSummary);
        document.getElementById('modal-box-amount').addEventListener('change', updateCostSummary);
        document.getElementById('modal-pickup-or-delivery').addEventListener('change', updateCostSummary);

        // Handle Update Button Click
        document.getElementById('update-order-btn').addEventListener('click', function() {
            const orderId = this.dataset.orderId;

            if (!orderId) {
                alert('Order ID not found');
                return;
            }

            // Show loading state
            this.textContent = 'Updating...';
            this.disabled = true;

            // Collect all form data
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('customer_name', document.getElementById('modal-customer-name').value);
            formData.append('email', document.getElementById('modal-customer-email').value);
            formData.append('phone', document.getElementById('modal-customer-phone').value);
            formData.append('amount_of_ice', document.getElementById('modal-ice-amount').value);
            formData.append('amount_of_boxes', document.getElementById('modal-box-amount').value);
            formData.append('recurring', document.getElementById('modal-recurring').value);
            formData.append('address', document.getElementById('modal-address').value);
            formData.append('unit', document.getElementById('modal-unit').value);
            formData.append('city', document.getElementById('modal-city').value);
            formData.append('postal', document.getElementById('modal-postal').value);
            formData.append('province', document.getElementById('modal-province').value);
            formData.append('country', document.getElementById('modal-country').value);
            const date = document.getElementById('modal-delivery-date').value;
            const time = document.getElementById('modal-delivery-time').value;

            if (date && time) {
                formData.append('delivery_date', `${date} ${time}:00`); // e.g., "2025-05-20 14:30:00"
            } else if (date) {
                formData.append('delivery_date', `${date} 00:00:00`);
            } else {
                formData.append('delivery_date', '');
            }
            formData.append('notes', document.getElementById('modal-notes').value);
            formData.append('status', document.getElementById('modal-status').value);
            formData.append('pickup_delivery', document.getElementById('modal-pickup-or-delivery').value);

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found. Make sure you have <meta name="csrf-token" content="{{ csrf_token() }}"> in your head tag.');
                this.textContent = 'Update';
                this.disabled = false;
                return;
            }

            // Send AJAX request to update order
            fetch(`{{ url('admin/orders') }}/${orderId}/ajax-update`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    // Reset button state
                    this.textContent = 'Update';
                    this.disabled = false;

                    if (data.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('orderSummaryModal'));
                        modal.hide();

                        // Show success notification
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Order has been updated successfully',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            alert('Order updated successfully!');
                            window.location.reload();
                        }
                    } else {
                        throw new Error(data.message || 'Update failed');
                    }
                })
                .catch(error => {
                    console.error('Error updating order:', error);

                    // Reset button state
                    this.textContent = 'Update';
                    this.disabled = false;

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Error!',
                            text: error.message || 'Failed to update order. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        alert('Error updating order: ' + (error.message || 'Please try again.'));
                    }
                });
        });

        // Handle Delete Button Click
        document.getElementById('delete-order-btn').addEventListener('click', function() {
            const orderId = this.dataset.orderId;

            if (!orderId) {
                alert('Order ID not found');
                return;
            }

            // Show confirmation dialog
            const confirmDelete = typeof Swal !== 'undefined' ?
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }) :
                Promise.resolve({ isConfirmed: confirm("Are you sure you want to delete this order? You won't be able to revert this!") });

            confirmDelete.then((result) => {
                if (result.isConfirmed) {
                    // Get CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        return;
                    }

                    // Send AJAX request to delete order
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
                                // Close modal
                                const modal = bootstrap.Modal.getInstance(document.getElementById('orderSummaryModal'));
                                modal.hide();

                                // Show success notification
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: 'Order has been deleted successfully',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    alert('Order deleted successfully!');
                                    window.location.reload();
                                }
                            } else {
                                throw new Error(data.message || 'Delete failed');
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting order:', error);
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Error!',
                                    text: error.message || 'Failed to delete order. Please try again.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                alert('Error deleting order: ' + (error.message || 'Please try again.'));
                            }
                        });
                }
            });
        });
    });






</script>
@endpush
