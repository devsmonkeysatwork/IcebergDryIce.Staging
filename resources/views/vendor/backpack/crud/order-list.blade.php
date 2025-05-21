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

           <a href="{{ url('admin/orders/create') }}" class="btn btn-add btn-sm"><i class="la la-plus mx-2"></i> New Order</a>
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
                              <button class="btn btn-primary btn-view"
                                      data-bs-toggle="modal"
                                      data-bs-target="#orderSummaryModal"
                                      data-id="{{ $order->id }}"
                                      data-customer="{{ $order->customer_name }}"
                                      data-email="{{ $order->email }}"
                                      data-phone="{{ $order->phone }}"
                                      data-ice="{{ $order->amount_of_ice }}"
                                      data-boxes="{{ $order-> amount_of_boxes}}"
                                      data-recurring="{{ $order->recurring }}"
                                      data-address="{{ $order->address }}"
                                      data-unit="{{ $order->unit }}"
                                      data-city="{{ $order->city }}"
                                      data-postal="{{ $order->postal }}"
                                      data-province="{{ $order->province }}"
                                      data-country="{{ $order->country }}"
                                      data-delivery-date="{{ $order->delivery_date }}"
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
                <h4 class="modal-title fw-bold"><i class="la la-file-invoice mx-2"></i> Order Summary</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <!-- Order Details -->
                    <div class="col-md-3">
                        <h5><i class="la la-shopping-cart"></i> Order</h5>
                        <div class="">
                            <label class="form-label">Order #</label>
                            <input id="modal-order-id" class="form-control" readonly>
                        </div>
                        <div class="">
                            <label class="form-label">Name</label>
                            <input id="modal-customer-name" class="form-control">
                        </div>
                        <div class="">
                            <label class="form-label">Email</label>
                            <input id="modal-customer-email" class="form-control">
                        </div>
                        <div class="">
                            <label class="form-label">Phone</label>
                            <input id="modal-customer-phone" class="form-control">
                        </div>
                        <div class="">
                            <label class="form-label">Amount of Ice</label>
                            <input id="modal-ice-amount" class="form-control">
                        </div>
                        <div class="">
                            <label class="form-label">Amount of Boxes</label>
                            <input id="modal-box-amount" class="form-control">
                        </div>
                        <div class="">
                            <label class="form-label">Recurring</label>
                            <select id="modal-recurring" class="form-select">
                                <option value=""></option>
                                <option value="recurring" {{ request('recurring') == 'recurring' ? 'selected' : '' }}>Yes</option>
                                <option value="non-recurring" {{ request('recurring') == 'non-recurring' ? 'selected' : '' }}>No</option>
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
                                <input id="modal-address" class="form-control">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Unit</label>
                                <input id="modal-unit" class="form-control">
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
                                    <select id="modal-province" class="form-select"></select>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Country</label>
                                <input id="modal-country" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <label class="form-label">Pickup or Delivery</label>
                                <select id="modal-pickup-or-delivery" class="form-select">
                                    <option value="pickup" {{ request('pickup_delivery') == 'pickup' ? 'selected' : '' }}>Pick Up</option>
                                    <option value="delivery" {{ request('pickup_delivery') == 'delivery' ? 'selected' : '' }}>Delivery</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="modal-status">
                                    <option value="valid" {{ request('status') == 'valid' ? 'selected' : '' }}>Valid</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label">Delivery Date</label>
                                <input id="modal-delivery-date" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Delivery Time</label>
                                <input id="modal-delivery-time" class="form-control">
                            </div>
                        </div>
                        <div class="">
                            <label class="form-label">Notes</label>
                            <textarea id="modal-notes" class="form-control"></textarea>
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
                    <button class="btn btn-secondary mx-2" data-bs-dismiss="modal">Cancel</button>
                </div>
                <button class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Modal -->


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
        form .select2.select2-container {
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

<script>
    document.addEventListener('DOMContentLoaded', function() {

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
        document.querySelectorAll('.btn-view').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('modal-order-id').value = this.dataset.id;
                document.getElementById('modal-customer-name').value = this.dataset.customer;
                document.getElementById('modal-customer-email').value = this.dataset.email;
                document.getElementById('modal-customer-phone').value = this.dataset.phone;
                document.getElementById('modal-ice-amount').value = this.dataset.ice;
                document.getElementById('modal-box-amount').value = this.dataset.boxes;
                document.getElementById('modal-recurring').value = this.dataset.recurring;
                document.getElementById('modal-address').value = this.dataset.address;
                document.getElementById('modal-unit').value = this.dataset.unit;
                document.getElementById('modal-city').value = this.dataset.city;
                document.getElementById('modal-postal').value = this.dataset.postal;
                document.getElementById('modal-province').value = this.dataset.province;
                document.getElementById('modal-country').value = this.dataset.country;



                document.getElementById('modal-notes').value = this.dataset.notes;
                document.getElementById('modal-status').value = this.dataset.status;
                document.getElementById('modal-pickup-or-delivery').value = this.dataset.pickup_delivery;
            });
        });


    });


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
                            q: params.term // search term
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
