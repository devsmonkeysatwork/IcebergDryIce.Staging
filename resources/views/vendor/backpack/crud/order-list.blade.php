@extends(backpack_view('blank'))



@section('header')
<section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-center d-print-none justify-content-between" bp-section="page-header">
  <div>
      <h1 class="text-capitalize mb-0" bp-section="page-heading">{!! $crud->getHeading() ?? $crud->entity_name_plural !!} List</h1>
      <p class="ms-2 ml-2 d-none mb-0" id="datatable_info_stack" bp-section="page-subheading">{!! $crud->getSubheading() ?? '' !!}</p>
  </div>
   <div>
       <small>
           <a href="{{ url('admin/manual-payments') }}" class="btn btn-add btn-manual btn-sm mx-3"><i class="la la-wallet mx-2"></i> Manual Payment</a>

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
          <div class="d-flex filters">
              <select name="" id="">
                  <option value="">Status</option>
                  <option value="">Status</option>
              </select>
              <select name="" id="" class="mx-2">
                  <option value="">Transfer Status</option>
              </select>
              <select name="" id="">
                  <option value="">Reccurring</option>
              </select>
              <select name="" id="" class="mx-2">
                  <option value="">Customer id</option>
              </select>
              <button class="btn btn-primary">Apply</button>
          </div>
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

                  <tr>
                      <td>00002</td>
                      <td>Chris</td>
                      <td>07/09/2024</td>
                      <td>Valid</td>
                      <td>$277.68</td>
                      <td>Online</td>
                      <td>No</td>
                      <td><button class="btn btn-primary btn-view" data-bs-toggle="modal" data-bs-target="#orderSummaryModal">View</button></td>
                  </tr>
                  <tr>
                      <td>00002</td>
                      <td>Chris</td>
                      <td>07/09/2024</td>
                      <td>Valid</td>
                      <td>$277.68</td>
                      <td>Online</td>
                      <td>No</td>
                      <td><button class="btn btn-primary btn-view" data-bs-toggle="modal" data-bs-target="#orderSummaryModal">View</button></td>
                  </tr>
                  <tr>
                      <td>00002</td>
                      <td>Chris</td>
                      <td>07/09/2024</td>
                      <td>Valid</td>
                      <td>$277.68</td>
                      <td>Online</td>
                      <td>No</td>
                      <td><button class="btn btn-primary btn-view" data-bs-toggle="modal" data-bs-target="#orderSummaryModal">View</button></td>
                  </tr>

                  <tr>
                      <td>00002</td>
                      <td>Chris</td>
                      <td>07/09/2024</td>
                      <td>Valid</td>
                      <td>$277.68</td>
                      <td>Online</td>
                      <td>No</td>
                      <td><button class="btn btn-primary btn-view" data-bs-toggle="modal" data-bs-target="#orderSummaryModal">View</button></td>
                  </tr>
                  <tr>
                      <td>00002</td>
                      <td>Chris</td>
                      <td>07/09/2024</td>
                      <td>Valid</td>
                      <td>$277.68</td>
                      <td>Online</td>
                      <td>No</td>
                      <td><button class="btn btn-primary btn-view" data-bs-toggle="modal" data-bs-target="#orderSummaryModal">View</button></td>
                  </tr>
                  <tr>
                      <td>00002</td>
                      <td>Chris</td>
                      <td>07/09/2024</td>
                      <td>Valid</td>
                      <td>$277.68</td>
                      <td>Online</td>
                      <td>No</td>
                      <td><button class="btn btn-primary btn-view" data-bs-toggle="modal" data-bs-target="#orderSummaryModal">View</button></td>
                  </tr>
                  <tr>
                      <td>00002</td>
                      <td>Chris</td>
                      <td>07/09/2024</td>
                      <td>Valid</td>
                      <td>$277.68</td>
                      <td>Online</td>
                      <td>No</td>
                      <td><button class="btn btn-primary btn-view" data-bs-toggle="modal" data-bs-target="#orderSummaryModal">View</button></td>
                  </tr>
                  <tr>
                      <td>00002</td>
                      <td>Chris</td>
                      <td>07/09/2024</td>
                      <td>Valid</td>
                      <td>$277.68</td>
                      <td>Online</td>
                      <td>No</td>
                      <td><button class="btn btn-primary btn-view" data-bs-toggle="modal" data-bs-target="#orderSummaryModal">View</button></td>
                  </tr>

                  </tbody>
              </table>
          </div>
      </div>
      <div class="row mt-3">
          <div class="col-md-2">
              <form action="{{ url()->current() }}" method="GET">
                  <div class="form-group entries">
                      <label for="per_page">entries per page</label>
                      <select name="per_page" id="per_page" class="form-control" onchange="this.form.submit()">
                          <option value="10">10</option>
                          <option value="25">25</option>
                          <option value="50">50</option>
                          <option value="100">100</option>
                      </select>
                  </div>
              </form>
          </div>
          <div class="col-md-6">
              <!-- Pagination links -->
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


@section('after_scripts')
{{--@include('crud::inc.datatables_logic')--}}

{{-- CRUD LIST CONTENT - crud_list_scripts stack --}}
{{--@stack('crud_list_scripts')--}}

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

    });
</script>

@endsection
