@extends(backpack_view('blank'))

{{--@php--}}
{{--$defaultBreadcrumbs = [--}}
{{--trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),--}}
{{--$crud->entity_name_plural => url($crud->route),--}}
{{--trans('backpack::crud.list') => false,--}}
{{--];--}}

{{--// if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs--}}
{{--// $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;--}}
{{--@endphp--}}

@section('header')
<section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none justify-content-between" bp-section="page-header">
  <div>
      <h1 class="text-capitalize mb-0" bp-section="page-heading">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</h1>
      <p class="ms-2 ml-2 d-none mb-0" id="datatable_info_stack" bp-section="page-subheading">{!! $crud->getSubheading() ?? '' !!}</p>
  </div>
  <small><a href="{{ url('admin/customers/create') }}" class="btn btn-add btn-sm btn-primary"><i class="la la-plus"></i> Add Customer</a></small>



</section>
@endsection

@section('content')
{{-- Default box --}}
<div class="row" bp-section="crud-operation-list">

  {{-- THE ACTUAL CONTENT --}}
        <div class="col-md-12">
            <table>
                <thead>
                <tr>
                    <th>Customer</th>
                    <th>Ice Charge (per lbs)</th>
                    <th>Delivery Charge (Customer)</th>
                    <th>Delivery Charge (Courier)</th>
                    <th>Hazmat Charge (Customer)</th>
                    <th>Hazmat Charge (Supplier)</th>
                    <th>Other Charge (Customer)</th>
                    <th>Other Charge (Iceberg)</th>
                    <th>PnL  ($)</th>
                    <th>PnL  (%)</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <td>1wa</td>
                    <td>1.95</td>
                    <td>20</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>$0</td>
                    <td>0%</td>
                    <td><button class="btn btn-primary btn-update" data-bs-toggle="modal" data-bs-target="#customerModal">Update</button></td>
                </tr>
                <tr>
                    <td>21CenturyTrading</td>
                    <td>1.95</td>
                    <td>20</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>$0</td>
                    <td>0%</td>
                    <td><button class="btn btn-primary btn-update" data-bs-toggle="modal" data-bs-target="#customerModal">Update</button></td>
                </tr>
                <tr>
                    <td>ABMGOOD</td>
                    <td>1.95</td>
                    <td>2500</td>
                    <td>5136</td>
                    <td>0</td>
                    <td>695</td>
                    <td>0</td>
                    <td>1120</td>
                    <td class="loss">-$4451</td>
                    <td class="loss">-64.03%</td>
                    <td><button class="btn btn-primary btn-update" data-bs-toggle="modal" data-bs-target="#customerModal">Update</button></td>
                </tr>
                <tr>
                    <td>AGROPUR</td>
                    <td>1.95</td>
                    <td>3000</td>
                    <td>1965</td>
                    <td>1012</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="profit">$2047</td>
                    <td class="profit">51.02%</td>
                    <td><button class="btn btn-primary btn-update" data-bs-toggle="modal" data-bs-target="#customerModal">Update</button></td>
                </tr>
                <tr>
                    <td>Ange</td>
                    <td>1</td>
                    <td>2</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="profit">$3</td>
                    <td class="profit">50%</td>
                    <td><button class="btn btn-primary btn-update" data-bs-toggle="modal" data-bs-target="#customerModal">Update</button></td>
                </tr>
                <tr>
                    <td>ApogeePharma</td>
                    <td>1.95</td>
                    <td>20</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>$0</td>
                    <td>0%</td>
                    <td><button class="btn btn-primary btn-update" data-bs-toggle="modal" data-bs-target="#customerModal">Update</button></td>
                </tr>
                <tr>
                    <td>Assoc. Grocers</td>
                    <td>1.95</td>
                    <td>491</td>
                    <td>1</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="profit">$490</td>
                    <td class="profit">99.80%</td>
                    <td><button class="btn btn-primary btn-update" data-bs-toggle="modal" data-bs-target="#customerModal">Update</button></td>
                </tr>
                <tr>
                    <td>Avanti</td>
                    <td>1.95</td>
                    <td>155</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="profit">$155</td>
                    <td class="profit">100%</td>
                    <td><button class="btn btn-primary btn-update" data-bs-toggle="modal" data-bs-target="#customerModal">Update</button></td>
                </tr>
                <tr>
                    <td>Bakerview</td>
                    <td>1.95</td>
                    <td>20</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>$0</td>
                    <td>0%</td>
                    <td><button class="btn btn-primary btn-update" data-bs-toggle="modal" data-bs-target="#customerModal">Update</button></td>
                </tr>
                <tr>
                    <td>Black & Blue</td>
                    <td>1.95</td>
                    <td>20</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>$0</td>
                    <td>0%</td>
                    <td><button class="btn btn-primary btn-update" data-bs-toggle="modal" data-bs-target="#customerModal">Update</button></td>
                </tr>

                </tbody>
            </table>
        </div>
</div>
<div class="row mt-3">
    <div class="col-md-3">
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
<!-- Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="orderSummaryLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title fw-bold"><i class="la la-file-invoice mx-2"></i> Customer Summary</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <!-- Order Details -->
                    <div class="col-md-6 px-3">
                        <div class="p-5 rounded h-100" style="background: rgba(245, 246, 250, 1);">
                            <h5><i class="la la-cart-arrow-down"></i> Ice</h5>
                            <div class="">
                                <label class="form-label">What WE charge per lbs of dry ice</label>
                                <input type="text" class="form-control w-50" value="$12.9">
                            </div>
                        </div>
                    </div>
                    <!-- Delivery Details -->
                    <div class="col-md-6 px-3">
                        <div class="p-5 rounded h-100" style="background: rgba(245, 246, 250, 1);">
                            <h5><i class="la la-truck"></i> Hazmat Cost</h5>
                            <div class="">
                                <label class="form-label">What WE charge for hazmat</label>
                                <input type="text" class="form-control w-50" value="$12.9">
                            </div>
                            <div class="">
                                <label class="form-label">What the dry ice SUPPLIER charges ICEBERG for hazmat</label>
                                <input type="text" class="form-control w-50" value="$12.9">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 px-3 mt-3">
                        <div class="p-5 rounded h-100" style="background: rgba(245, 246, 250, 1);">
                            <h5><i class="la la-truck"></i> Delivery Cost</h5>
                            <div class="">
                                <label class="form-label">What WE charge to deliver dry ice</label>
                                <input type="text" class="form-control w-50" value="$12.9">
                            </div>
                            <div class="">
                                <label class="form-label">What the COURIER charges ICEBERG to deliver dry ice</label>
                                <input type="text" class="form-control w-50" value="$12.9">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 px-3 mt-3">
                        <div class="p-5 rounded h-100" style="background: rgba(245, 246, 250, 1);">
                            <h5><i class="la la-dollar"></i> Other Cost</h5>
                            <div class="">
                                <label class="form-label">Various charges to the CUSTOMER - bags, etc.</label>
                                <input type="text" class="form-control w-50" value="$12.9">
                            </div>
                            <div class="">
                                <label class="form-label">Various charges to ICEBERG - bags, etc.</label>
                                <input type="text" class="form-control w-50" value="$12.9">
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


            .container-fluid .btn-add {
                padding: 8px 16px;
                font-weight: 500;
                font-size: 14px;
            }
            .btn-add {
                display: none;
            }


            .form-group .form-control {
                width: 62px;
                height: 23.14px;
                font-size: 14px;
                padding: 0;
            }

            /* Status Colors */
            .btn-update , .modal .btn {
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
                max-width: 70%;
                margin-top: 5%;
            }
            .modal-content {
                padding: 20px;
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
@include('crud::inc.datatables_logic')

{{-- CRUD LIST CONTENT - crud_list_scripts stack --}}
@stack('crud_list_scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {

        let summaryModal = document.getElementById("customerModal");
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
