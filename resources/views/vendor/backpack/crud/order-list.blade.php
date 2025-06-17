@php

    // This handles cases where $entries might not be a paginator instance
    $isPaginated = isset($entries) && method_exists($entries, 'links');

@endphp
@extends(backpack_view('blank'))

@section('header')
<section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-center d-print-none justify-content-between" bp-section="page-header">
  <div>
      <h1 class="text-capitalize mb-0" bp-section="page-heading">{!! $crud->getHeading() ?? $crud->entity_name_plural !!} List</h1>
      <p class="ms-2 ml-2 d-none mb-0" id="datatable_info_stack" bp-section="page-subheading">{!! $crud->getSubheading() ?? '' !!}</p>
  </div>
   <div>
       <small>
           <a href="{{ url('admin/manual-payments/create') }}" class="btn btn-add btn-manual btn-sm mx-3"><i class="la la-wallet mx-2"></i> Manual Payment</a>

           <button id="create-order-btn" data-bs-toggle="modal" data-bs-target="#orderSummaryModal" class="btn btn-add btn-sm"><i class="la la-plus mx-2"></i> New Order</button>

       </small>
   </div>

</section>
@endsection

@section('content')
{{-- Default box --}}
<div class="row" bp-section="crud-operation-list">

  {{-- THE ACTUAL CONTENT --}}
  <div class="{{ $crud->getListContentClass() }}">
    <h3 class="filter-heading">
        <i class="la la-filter la-2x"></i>
        Filter by
    </h3>
    <div class="row my-3 align-items-center">
      <div class="col-sm-10">
          <form action="{{ url()->current() }}" method="GET">
              {{-- Preserve pagination parameter --}}
              <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

              <div class="d-flex filters">
                  <select name="status" id="status">
                      <option value="">Status</option>
                      <option value="valid" {{ request('status') == 'valid' ? 'selected' : '' }}>Valid</option>
                      <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                  </select>
                  <select name="transfer_status" id="transfer_status" class="mx-2">
                      <option value="">Transfer Status</option>
                      <!-- Add options here -->
                  </select>
                  <select name="recurring" id="recurring">
                      <option value="">Recurring</option>
                      <option value="recurring" {{ request('recurring') == 'recurring' ? 'selected' : '' }}>Yes</option>
                      <option value="non-recurring" {{ request('recurring') == 'non-recurring' ? 'selected' : '' }}>No</option>
                  </select>
                  <select name="customer_id" id="customer_id" class="form-control mx-2" style="width: 250px; border-radius: 3px "></select>
                  <button type="submit" class="btn btn-primary">Apply</button>
              </div>
          </form>
      </div>
      <div class="col-sm-2">

      </div>
    </div>


      <div class="row" bp-section="crud-operation-list">

          {{-- THE ACTUAL CONTENT --}}
          <div class="col-md-12">
              <table>
                  <thead>
                  <tr>
                      <th>Order #</th>
                      <th>Customer Name</th>
                      <th>Delivery Date</th>
                      <th>Status</th>
                      <th>Total</th>
                      <th>Origin</th>
                      <th>Recurring</th>
                      <th></th>
                  </tr>
                  </thead>
                  <tbody>

                  @foreach($entries as $order)
                      <tr>
                          <td>{{ $order->id }}</td>
                          <td>{{ $order->customer_name }}</td>
                          <td>{{ \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') }}</td>
                          <td>{{ $order->status }}</td>
                          <td>${{ number_format($order->total_cost, 2) }}</td>
                          <td>{{ $order->origin }}</td>
                          <td>{{ $order->recurring}}</td>
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
                              >
                                  View
                              </button>

                          </td>
                      </tr>
                  @endforeach

                  </tbody>
              </table>
          </div>
      </div>
      {{-- Updated pagination section --}}
      <div class="row mt-3">
          <div class="col-md-6">
              <form action="{{ url()->current() }}" method="GET" class="mb-3">
                  {{-- Preserve any existing filter parameters --}}
                  @foreach(request()->except(['page', 'per_page']) as $key => $value)
                      <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                  @endforeach

                  <div class="form-group entries d-flex align-items-center">
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
              <div class="float-end">
                  {{-- Pagination links (only if $entries is a paginator instance) --}}
                  @if($isPaginated)
                      {{ $entries->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                  @endif
              </div>
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
                            <label class="form-label">Phone<span class="text-danger">*</span></label>
                            <input id="modal-customer-phone" class="form-control" type="tel" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Amount of Ice (lbs) <span class="text-danger">*</span></label>
                            <input id="modal-ice-amount" class="form-control" type="number" min="0" step="0.1" required>
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
                            <label class="form-label">Location Name<span class="text-danger">*</span></label>
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
                    <button class="btn btn-secondary mx-2" data-bs-dismiss="modal">Cancel</button>
                </div>
                <button id="delete-order-btn" class="btn btn-danger" style="display: none;">
                    <i class="la la-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('after_styles')

{{-- DATA TABLES --}}
@basset('https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css')
@basset('https://cdn.datatables.net/fixedheader/3.3.1/css/fixedHeader.dataTables.min.css')
@basset('https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css')

{{-- CRUD LIST CONTENT - crud_list_styles stack --}}
@stack('crud_list_styles')


    <style>

        h1 {
            font-weight: 900;
            font-size: 36px;
            letter-spacing: -0.11px;
            margin: 0px;
        }
        .filter-heading {
            font-size: 17px;
            font-weight: 800;
        }


        .container-fluid .btn-add {
            padding: 8px 16px;
            font-weight: 500;
            font-size: 14px;
        }

        .container-fluid .btn-add {
            padding: 12px 20px;
            font-weight: 700;
            font-size: 18px;
            font-family: Nunito Sans;

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

        .d-print-none a.btn-primary {
            display: none;
        }

        .filters select {
            font-family: Nunito Sans;
            padding: 3px 8px;
            font-weight: 600;
            font-size: 14px;
            line-height: 19.1px;
            letter-spacing: 0px;
            border-radius: 8px;
            border: 1px solid rgba(213, 213, 213, 1)
        }
        form .select2.select2-container , .select2-container {
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
        .filters .btn {
            font-family: Nunito Sans;
            font-weight: 700;
            font-size: 14px;
            line-height: 19.1px;
            letter-spacing: 0px;
            border-radius: 8px;
            border: 1px solid rgba(2, 86, 197, 1);
            background: white;
            padding: 3px 20px;
            color: rgba(2, 86, 197, 1);
        }

        #crudTable_wrapper #crudTable, #crudTable_wrapper table.dataTable {
            border: none;
            background: transparent;
        }

        .table thead th {
            font-family: Nunito Sans;
            font-weight: 700;
            font-size: 14px;
            line-height: 19.1px;
            letter-spacing: 0px;
        }
        .table td{
            border: none;
        }

        table tr {
            border-collapse: separate;
            border-spacing: 0 15px;
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
        .modal .btn-secondary {
            background: lightgrey;
            color: black;
        }


        footer {
            display: none;
        }

    </style>

@endsection


@push('after_scripts')
{{--@include('crud::inc.datatables_logic')--}}

{{-- CRUD LIST CONTENT - crud_list_scripts stack --}}
{{--@stack('crud_list_scripts')--}}

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



            // Update modal title and button text
            document.getElementById('modal-title-text').textContent = 'Edit Order';
            document.getElementById('save-btn-text').textContent = 'Update Order';

            // Show order ID section and delete button
            document.getElementById('order-id-section').style.display = 'block';
            document.getElementById('delete-order-btn').style.display = 'block';

            // Populate form with existing data
            document.getElementById('modal-order-id').value = btn.dataset.id;

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
            document.getElementById('save-order-btn').dataset.orderId = btn.dataset.id;
            document.getElementById('delete-order-btn').dataset.orderId = btn.dataset.id;

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

        // Handle Delete Button Click (same as before)
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
            })
        });
    })

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
                    console.log('Supplier data received:', data);
                    console.log('Data structure:', JSON.stringify(data, null, 2));

                    // Fix: Handle the correct response structure
                    let supplier;

                    // Check if data has closest_supplier property
                    if (data.closest_supplier && data.closest_supplier.id) {
                        supplier = data.closest_supplier;
                    }
                    // Check if data itself contains the supplier info
                    else if (data.id) {
                        supplier = data;
                    }
                    // Check if it's an array and get the first element
                    else if (Array.isArray(data) && data.length > 0 && data[0].id) {
                        supplier = data[0];
                    }
                    else {
                        console.error('No supplier found in response:', data);
                        throw new Error('No supplier found in response');
                    }

                    console.log('Found supplier:', supplier);
                    console.log('Supplier ID:', supplier.id);

                    // Get delivery quote
                    const quotePayload = {
                        supplier_id: supplier.id,
                        delivery: {
                            name: formData.locationName.trim(),
                            street: formData.address.trim(),
                            unit: formData.unit.trim(),
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

</script>


@endpush
