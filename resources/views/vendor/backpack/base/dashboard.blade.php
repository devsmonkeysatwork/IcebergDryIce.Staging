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
                    <button class="btn btn-primary btn-view" data-bs-toggle="modal" data-bs-target="#orderSummaryModal">View</button>
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
                        <div class="">
                            <label class="form-label">Order #</label>
                            <input type="text" class="form-control" value="00002" readonly>
                        </div>
                        <div class="">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" value="Chris">
                        </div>
                        <div class="">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="admin@icebergdryice.com">
                        </div>
                        <div class="">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" value="5555555555">
                        </div>
                        <div class="">
                            <label class="form-label">Amount of Ice</label>
                            <input type="text" class="form-control" value="100 lbs">
                        </div>
                        <div class="">
                            <label class="form-label">Amount of Boxes</label>
                            <input type="text" class="form-control" value="2">
                        </div>
                        <div class="">
                            <label class="form-label">Recurring</label>
                            <select class="form-select">
                                <option>Non-recurring</option>
                            </select>
                        </div>
                    </div>

                    <!-- Delivery Details -->
                    <div class="col-md-5 px-3">
                        <h5><i class="la la-truck"></i> Delivery</h5>
                        <div class="">
                            <label class="form-label">Location Name</label>
                            <input type="text" class="form-control" value="Residence">
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" value="175 3rd street W">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Unit</label>
                                <input type="text" class="form-control" value="111">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" value="North Vancouver">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Postal</label>
                                <input type="text" class="form-control" value="V7M0G5">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Province</label>
                                <select class="form-select">
                                    <option selected>BC</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Country</label>
                                <input type="text" class="form-control" value="Canada">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <label class="form-label">Pickup or Delivery</label>
                                <select class="form-select">
                                    <option selected>Delivery</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label">Status</label>
                                <select class="form-select">
                                    <option selected>Valid</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label">Delivery Date</label>
                                <input type="text" class="form-control" value="Tomorrow">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Delivery Time</label>
                                <input type="text" class="form-control" value="10:00 am">
                            </div>
                        </div>
                        <div class="">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control">hi</textarea>
                        </div>
                    </div>

                    <!-- Cost Summary -->
                    <div class="col-md-4">
                        <h5><i class="la la-dollar-sign"></i> Cost Summary</h5>
                        <div class="p-3 rounded" style="background: rgba(245, 246, 250, 1);">
                            <div class="d-flex justify-content-between align-items-center m-1">
                                <p class="m-0">Dry Ice (100 lbs @ $1.95/lb):</p>
                                <strong>$195.00</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center m-1">
                                <p class="m-0">Styrofoam Box (2 @ $30/box): </p>
                                <strong>$60.00</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center m-1">
                                <p class="m-0">Pickup/Delivery: </p>
                                <strong>$20.00</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center m-1">
                                <p class="m-0">Sub-Total: </p>
                                <strong>$275.00</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center m-1">
                                <p class="m-0">Tax (15%):  </p>
                                <strong>$41.25</strong>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between align-items-center m-1">
                                <p class="m-0">TOTAL: </p>
                                <strong>$316.25</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 d-flex justify-content-between">
                <div>
                    <button class="btn btn-primary">Update</button>
                    <button class="btn btn-dark mx-2">Push</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
                <button class="btn btn-danger">Delete</button>
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

@section('after_scripts')
@vite(['resources/js/app.js'])
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('tr[data-href]');
    rows.forEach(row => {
      row.addEventListener('click', function() {
        window.location.href = this.dataset.href;
      });
      row.style.cursor = 'pointer'; // Optional: Change cursor to pointer to indicate row is clickable
    });

      let summaryModal = document.getElementById("orderSummaryModal");
      let sidebar = document.querySelector("aside.navbar-vertical"); // Adjust selector based on your layout


      summaryModal.addEventListener("show.bs.modal", function() {
          if (sidebar) {
              sidebar.style.zIndex = "-1"; // Lower sidebar when modal appears
          }
      });

      summaryModal.addEventListener("hidden.bs.modal", function() {
          if (sidebar) {
              sidebar.style.zIndex = "1030"; // Reset sidebar z-index after modal closes
          }
      });

  });






</script>
@endsection
