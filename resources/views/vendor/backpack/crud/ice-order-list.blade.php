@extends(backpack_view('blank'))

@section('header')
    <section class="content-header ps-2">
        <h1>
            Ice Orders
        </h1>
    </section>
@endsection

@section('content')
    <div class="row" bp-section="crud-operation-list">
        <div class="col-md-12 pt-4">
            <a href="{{ route('ice-orders.create') }}" class="btn btn-add btn-primary">
                <i class="la la-plus mx-2"></i> Add Ice Order
            </a>

            <table class="table table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th>Date</th>
                    <th>Supplier Name</th>
                    <th>ICE Cost</th>
                    <th>ICE Invoice</th>
                    <th>Border Cost</th>
                    <th>Border Invoice</th>
                    <th>Shipper Name</th>
                    <th>Shipper Cost</th>
                    <th>Probill</th>
                    <th>Other Description</th>
                    <th>Other Cost</th>
                    <th>Weight</th>
                    <th>Totes</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($iceOrders as $iceOrder)
                    <tr>
                        <td>{{ $iceOrder->date }}</td>
                        <td>{{ $iceOrder->supplier_name }}</td>
                        <td>${{ number_format($iceOrder->ice_cost, 2) }}</td>
                        <td>{{ $iceOrder->ice_invoice }}</td>
                        <td>${{ number_format($iceOrder->border_cost, 2) }}</td>
                        <td>{{ $iceOrder->border_invoice }}</td>
                        <td>{{ $iceOrder->shipper_name }}</td>
                        <td>${{ number_format($iceOrder->shipper_cost, 2) }}</td>
                        <td>{{ $iceOrder->probill }}</td>
                        <td>{{ $iceOrder->other_description }}</td>
                        <td>${{ number_format($iceOrder->other_cost, 2) }}</td>
                        <td>{{ $iceOrder->weight }}</td>
                        <td>{{ $iceOrder->totes }}</td>
                        <td>
                            <a href="{{ route('ice-orders.view', $iceOrder->id) }}" class="btn btn-primary btn-view">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" class="text-center">No payments found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
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
