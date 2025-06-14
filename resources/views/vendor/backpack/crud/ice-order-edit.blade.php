@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.add') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
//  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none" bp-section="page-header">
        <h1 class="text-capitalize mb-0" bp-section="page-heading">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</h1>
        <p class="ms-2 ml-2 mb-0" bp-section="page-subheading">{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</p>
        @if ($crud->hasAccess('list'))
            <p class="mb-0 ms-2 ml-2" bp-section="page-subheading-back-button">
                <small>
                    <a href="{{ url($crud->route) }}" class="d-print-none font-sm">
                        <span><i class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></span>
                    </a>
                </small>
            </p>
        @endif
    </section>
@endsection

@section('content')

<div class="row" bp-section="crud-operation-create">
	<div class="{{ $crud->getCreateContentClass() }} col-md-12">
		{{-- Default box --}}

		@include('crud::inc.grouped_errors')

        <form method="post" class="card" action="{{ route('ice-orders.update', ['id' => $entry->id]) }}">
            {!! csrf_field() !!}

              @if ($errors->any())
                  <div class="alert alert-danger">
                      <ul class="mb-0">
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif

    <div class="row">
        <div class="col-4 px-4">
            <h3 class="form-group-heading m-0"><i class="la la-calendar me-2"></i> Date</h3>
            <div class="form-group w-50">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date"
                       value="{{ old('date', $entry->date ?? '') }}" required>
            </div>
        </div>

        <div class="col-4 px-4">
            <h3 class="form-group-heading m-0"><i class="la la-cart-arrow-down me-2"></i> Supplier</h3>
            <div class="form-group">
                <label for="supplier_name">Supplier</label>
                <input type="text" name="supplier_name" class="form-control"
                       value="{{ old('supplier_name', $entry->supplier_name ?? '') }}" required>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="ice_cost">Ice Cost</label>
                    <input type="number" step="0.01" class="form-control" id="ice_cost" name="ice_cost"
                           value="{{ old('ice_cost', $entry->ice_cost ?? '') }}" placeholder="Cost $" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="ice_invoice">Ice Invoice #</label>
                    <input type="text" class="form-control" id="ice_invoice" name="ice_invoice"
                           value="{{ old('ice_invoice', $entry->ice_invoice ?? '') }}" placeholder="Invoice #" required>
                </div>
            </div>
        </div>

        <div class="col-4 px-4">
            <div class="row">
                <h3 class="form-group-heading m-0"><i class="la la-truck-moving me-2"></i> Carrier</h3>
                <div class="form-group col-md-12">
                    <label for="shipper_name">Carrier</label>
                    <input type="text" class="form-control" id="shipper_name" name="shipper_name"
                           value="{{ old('shipper_name', $entry->shipper_name ?? '') }}" placeholder="Carrier Name" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="shipper_cost">Carrier Cost</label>
                    <input type="number" step="0.01" class="form-control" id="shipper_cost" name="shipper_cost"
                           value="{{ old('shipper_cost', $entry->shipper_cost ?? '') }}" placeholder="Cost $" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="probill">Probill #</label>
                    <input type="text" class="form-control" id="probill" name="probill"
                           value="{{ old('probill', $entry->probill ?? '') }}" placeholder="Probill #" required>
                </div>
            </div>
        </div>
    </div>

            <div class="row mt-3">
                <div class="col-4 px-4">
                    <h3 class="form-group-heading m-0"><i class="la la-donate me-2"></i> Amount</h3>
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="weight">Amount (lbs)</label>
                            <input type="number" class="form-control" id="weight" name="weight"
                                   value="{{ old('weight', $entry->weight ?? '') }}" placeholder="Weight in lbs" required>
                        </div>

                        <div class="form-group col-12">
                            <label for="totes">Totes</label>
                            <input type="number" class="form-control" id="totes" name="totes"
                                   value="{{ old('totes', $entry->totes ?? '') }}" placeholder="# of totes" required>
                        </div>
                    </div>
                </div>

                <div class="col-4 px-4">
                    <h3 class="form-group-heading m-0"><i class="la la-flag me-2"></i> Border</h3>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="border">Border</label>
                            <input type="text" class="form-control" id="border" name="border"
                                   value="{{ old('border', $entry->border ?? ' ') }}" placeholder="Carson" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="border_cost">Border Cost</label>
                            <input type="number" step="0.01" class="form-control" id="border_cost" name="border_cost"
                                   value="{{ old('border_cost', $entry->border_cost ?? '') }}" placeholder="Cost $" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="border_invoice">Border Invoice #</label>
                            <input type="text" class="form-control" id="border_invoice" name="border_invoice"
                                   value="{{ old('border_invoice', $entry->border_invoice ?? '') }}" placeholder="Invoice #" required>
                        </div>
                    </div>
                </div>

                <div class="col-4 px-4">
                    <h3 class="form-group-heading m-0"><i class="la la-radiation me-2"></i> Other</h3>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="other_description">Other</label>
                            <textarea class="form-control" id="other_description" name="other_description" rows="1"
                                      placeholder="Description of extra costs.">{{ old('other_description', $entry->other_description ?? '') }}</textarea>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="other_cost">Other Cost</label>
                            <input type="number" step="0.01" class="form-control" id="other_cost" name="other_cost"
                                   value="{{ old('other_cost', $entry->other_cost ?? '') }}" placeholder="Cost $" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group px-4 mt-4">
                <button type="submit" class="btn btn-primary btn-submission">Update</button>
                <button type="button" class="btn btn-secondary btn-submission mx-2" onclick="window.location.href='/admin/ice-orders'">
                    Close
                </button>
                <button type="button" class="btn btn-danger btn-submission float-end" onclick="confirmDelete()">
                    Delete
                </button>
            </div>

        </form>
        <form id="delete-form" method="POST" action="{{ route('ice-orders.custom_delete', $entry->id) }}" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>








<style>

    h1 {

    }
    form.card {
        padding: 25px;
        background: white;
        border-radius: 20px;
        margin-top: 15px;
    }
    form.card > .card {
        border: none;
    }
    form.card > .card > .card-body {
        padding: 0px;
    }

    h3.form-group-heading {
        font-weight: 800;
        font-size: 24px;
        line-height: 36px;
        letter-spacing: -0.11px;
    }
    .form-control {
        border-radius: 10px !important;
    }
    .btn-submission {
        font-weight: 600;
        font-size: 16px;
        line-height: 20.8px;
        letter-spacing: 0px;
        text-align: center;
        border-radius: 25px;
        padding: 8px 35px;
    }
    .btn-secondary {
        background: lightgrey;
        color: black;
    }

    footer {
        display: none;
    }

</style>

@endsection

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the ice order.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form').submit();
                }
            });
        }
    </script>

