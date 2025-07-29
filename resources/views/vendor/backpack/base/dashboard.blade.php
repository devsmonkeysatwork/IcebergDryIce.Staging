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
{{--            <p style="color: rgba(141, 141, 141, 1);">via CC or Online orders</p>--}}
          <p class="card-text stat"><span>{{$onlineChange}}% Up</span> from last year</p>
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
{{--            <p style="color: rgba(141, 141, 141, 1);">via Manual Payments</p>--}}
            <p class="card-text stat"><span>{{$manualChange}}% Up</span> from last year</p>
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
          <p class="card-text stat"><span>{{$dryIceChange}}% Up</span> from last year</p>
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
          <p class="card-text stat"><span>{{$styrofoamChange}}% Up</span> from last year</p>
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
            <tr data-href="{{ url('admin/orders/' . $order['id'] . '/show') }}">
              <td>{{ $order['id'] }}</td>
              <td>{{ $order['customer_name'] }}</td>
              <td>{{ \Illuminate\Support\Carbon::parse($order['delivery_date'])->format("Y-m-d") }}</td>
              <td>
                  <p class="status p-3">{{ $order['status'] }}</p>
              </td>
              <td>${{ $order['total_cost'] }}</td>
              <td>{{ $order['origin'] }}</td>
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
            <tr>
              <td>{{ $order['customer_name']}}</td>
              <td>{{ \Illuminate\Support\Carbon::parse($order['delivery_date'])->format('Y-m-d')}}</td>
              <td>{{ $order['amount_of_ice']}} lbs</td>
              <td>{{ $order['amount_of_boxes']}}</td>
              <td>{{ $order['address']}}</td>
                <td>
                    @php
                        $dateTime = \Carbon\Carbon::parse($order['delivery_date']);
                    @endphp
                    <button class="btn btn-primary btn-view" data-order-id="{{ $order['id'] }}">
                        View
                    </button>
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
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($recurringOrders as $order)
            <tr data-href="{{ url('admin/orders/' . $order->id . '/show') }}">
              <td>{{ $order->order_id }}</td>
              <td>{{ $order->order->customer_name }}</td>
              <!-- <td>{{ $order->order->address }}</td> -->
              <td>{{ \Carbon\Carbon::parse($order->scheduled_delivery_date)->format('Y-m-d') }}</td>
              <!-- <td>{{ $order->order->amount_of_ice }} lbs</td> -->
              <!-- <td>{{ $order->order->amount_of_boxes }}</td> -->
              <td>
                <p class="status p-3">{{ $order->status }}</p>
              </td>
              <td>${{ $order->order->total_cost }}</td>
              <td><button class="btn btn-primary rounded-5 px-3" onclick="loadRecurringOrderDetails(1,'{{$order->order_id}}','{{$order->id}}')">View</button></td>
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
    // Replace the existing JavaScript section in your blade template with this:

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
            const addressFields = ['modal-address', 'modal-city', 'modal-province', 'modal-postal', 'modal-ice-amount'];
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
                        if (deliveryOption && deliveryOption.value === 'delivery') {
                            if (!recalculateButton) {
                                $('.cost-summary-delivery').append(btnHtml);
                            }
                        }
                    };
                    field.addEventListener('input', field._deliveryListener);
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
            const iceAmount = parseFloat(document.getElementById('modal-ice-amount').value) || 0;
            const boxAmount = parseFloat(document.getElementById('modal-box-amount').value) || 0;
            const pickupDelivery = document.getElementById('modal-pickup-or-delivery').value;
            const deliveryCost = document.getElementById('modal-delivery-cost').value;

            const pricePerLb = 1.95;
            const pricePerBox = 30.00;
            const deliveryFee = pickupDelivery === 'delivery' ? parseFloat(deliveryCost) : 0.00; // Fix this line

            const iceCost = iceAmount * pricePerLb;
            const boxCost = boxAmount * pricePerBox;
            const subTotal = iceCost + boxCost;
            const taxRate = 0.15;
            const tax = subTotal * taxRate;
            const total = subTotal + tax + deliveryFee;

            // Update the cost summary section
            document.querySelector('.cost-summary-ice').innerHTML =
                `<p class="m-0">Dry Ice (${iceAmount} lbs @ $${pricePerLb.toFixed(2)}/lb):</p>
            <strong>$${iceCost.toFixed(2)}</strong>`;

            document.querySelector('.cost-summary-box').innerHTML =
                `Styrofoam Box (${boxAmount} @ $${pricePerBox.toFixed(2)}/box):<strong>$${boxCost.toFixed(2)}</strong>`;

            document.querySelector('.cost-summary-delivery').innerHTML =
                `Pickup/Delivery:<strong>$${deliveryFee.toFixed(2)}</strong>`;

            document.querySelector('.cost-summary-subtotal').innerHTML =
                `Sub-Total:<strong>$${subTotal.toFixed(2)}</strong>`;

            document.querySelector('.cost-summary-tax').innerHTML =
                `Tax (${(taxRate * 100).toFixed(0)}%):<strong>$${tax.toFixed(2)}</strong>`;

            document.querySelector('.cost-summary-total').innerHTML =
                `TOTAL:<strong>$${total.toFixed(2)}</strong>`;
        }

        function addCostCalculationListeners() {
            const iceField = document.getElementById('modal-ice-amount');
            const boxField = document.getElementById('modal-box-amount');
            const deliveryField = document.getElementById('modal-pickup-or-delivery');

            if (iceField) iceField.addEventListener('input', updateCostSummary);
            if (boxField) boxField.addEventListener('input', updateCostSummary);
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
                displayElement.textContent = amount !== null ? `$${amount.toFixed(2)}` : 'Not found';
            }

            // Update TOTAL only if amount is not null
            if (amount !== null) {
                const dryIceText = document.querySelector('.cost-summary-ice strong').textContent.replace('$', '') || 0;
                const boxText = document.querySelector('.cost-summary-box strong').textContent.replace('$', '') || 0;
                const delivery = amount;

                const subtotal = parseFloat(dryIceText) + parseFloat(boxText);
                const tax = subtotal * 0.15;
                const total = subtotal + tax + delivery;

                document.querySelector('.cost-summary-subtotal strong').textContent = `$${subtotal.toFixed(2)}`;
                document.querySelector('.cost-summary-tax strong').textContent = `$${tax.toFixed(2)}`;
                document.querySelector('.cost-summary-total strong').textContent = `$${total.toFixed(2)}`;
            } else {
                // Reset totals when delivery cost can't be calculated
                document.querySelector('.cost-summary-subtotal strong').textContent = '$0.00';
                document.querySelector('.cost-summary-tax strong').textContent = '$0.00';
                document.querySelector('.cost-summary-total strong').textContent = '$0.00';
            }
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

            // Check required address fields for delivery calculation
            const requiredAddressFields = [formData.address, formData.city, formData.province, formData.postal];
            if (!requiredAddressFields.every(val => val && val.trim())) {
                console.log('Missing required address fields for delivery calculation');
                updateDeliveryCostSummary(null);
                return;
            }

            // Show loading
            const deliveryCostElement = document.querySelector('.cost-summary-delivery strong');
            if (deliveryCostElement) {
                deliveryCostElement.textContent = 'Calculating...';
            }

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
                    if($('#supplier_id').length){
                        $('#supplier_id').val(supplier.id);
                    }
                    // Get delivery quote
                    const quotePayload = {
                        supplier_id: supplier.id,
                        delivery: {
                            name: formData.locationName.trim() || 'N/A',
                            street: formData.address.trim(),
                            unit: formData.unit.trim() || '', // Ensure it's always a string
                            city: formData.city.trim(),
                            province: formData.province.trim(),
                            postal_code: formData.postal.trim(),
                            contact: formData.name.trim() || 'N/A',
                            phone: formData.phone.trim() || 'N/A',
                            email: formData.email.trim() || 'N/A'
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
                    updateDeliveryCostSummary(null); // Set to null on error
                    showError(deliveryCostElement);
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




    // Initialize Select2 for customer search
    $(document).ready(function() {
        $('#customer_id').select2({
            placeholder: 'Search by Customer ID or Name',
            minimumInputLength: 1,
            ajax: {
                url: '{{ route("ajax.customers") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });



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
