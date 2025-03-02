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
	<div class="{{ $crud->getCreateContentClass() }}">
		{{-- Default box --}}

		@include('crud::inc.grouped_errors')

		  <form method="post" class="card"
		  		action="{{ url($crud->route) }}"
				@if ($crud->hasUploadFields('create'))
				enctype="multipart/form-data"
				@endif
		  		>
			  {!! csrf_field() !!}

    <div class="row">
        <div class="col-4 px-4">
            <h3 class="form-group-heading m-0"><i class="la la-calendar me-2"></i> Date</h3>
            <div class="form-group w-50">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
        </div>

        <div class="col-4 px-4">
            <h3 class="form-group-heading m-0"><i class="la la-cart-arrow-down me-2"></i> Supplier</h3>
            <div class="form-group">
                <label for="supplier_name">Supplier</label>
                <input type="text" class="form-control" id="supplier_name" name="supplier_name" placeholder="Dry Ice Supplier Name" required>
            </div>
            <div class="row">
                <!-- Ice Cost -->
                <div class="form-group col-md-6">
                    <label for="ice_cost">Ice Cost</label>
                    <input type="number" step="0.01" class="form-control" id="ice_cost" name="ice_cost" placeholder="Cost $" required>
                </div>

                <!-- Ice Invoice -->
                <div class="form-group col-md-6">
                    <label for="ice_invoice">Ice Invoice #</label>
                    <input type="text" class="form-control" id="ice_invoice" name="ice_invoice" placeholder="Invoice #" required>
                </div>
            </div>
        </div>

        <div class="col-4 px-4">
            <div class="row">
                <h3 class="form-group-heading m-0"><i class="la la-truck-moving me-2"></i> Carrier</h3>
                <!-- Carrier Name -->
                <div class="form-group col-md-12">
                    <label for="shipper_name">Carrier</label>
                    <input type="text" class="form-control" id="shipper_name" name="shipper_name" placeholder="Carrier Name" required>
                </div>

                <!-- Carrier Cost -->
                <div class="form-group col-md-6">
                    <label for="shipper_cost">Carrier Cost</label>
                    <input type="number" step="0.01" class="form-control" id="shipper_cost" name="shipper_cost" placeholder="Cost $" required>
                </div>

                <!-- Probill -->
                <div class="form-group col-md-6">
                    <label for="probill">Probill #</label>
                    <input type="text" class="form-control" id="probill" name="probill" placeholder="Probill #" required>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-4 px-4">
                <h3 class="form-group-heading m-0"><i class="la la-donate me-2"></i> Amount</h3>
                <div class="row">
                    <!-- Weight -->
                    <div class="form-group col-12">
                        <label for="weight">Amount (lbs)</label>
                        <input type="number" class="form-control" id="weight" name="weight" placeholder="Weight in lbs" required>
                    </div>

                    <!-- Totes -->
                    <div class="form-group col-12">
                        <label for="totes">Totes</label>
                        <input type="number" class="form-control" id="totes" name="totes" placeholder="# of totes" required>
                    </div>
                </div>
            </div>

            <div class="col-4 px-4">
                <h3 class="form-group-heading m-0"><i class="la la-flag me-2"></i> Border</h3>
                <div class="row">
                    <!-- Border -->
                    <div class="form-group col-md-12">
                        <label for="border">Border</label>
                        <input type="text" class="form-control" id="border" name="border" placeholder="Carson" required>
                    </div>

                    <!-- Border Cost -->
                    <div class="form-group col-md-6">
                        <label for="border_cost">Border Cost</label>
                        <input type="number" step="0.01" class="form-control" id="border_cost" name="border_cost" placeholder="Cost $" required>
                    </div>

                    <!-- Border Invoice -->
                    <div class="form-group col-md-6">
                        <label for="border_invoice">Border Invoice #</label>
                        <input type="text" class="form-control" id="border_invoice" name="border_invoice" placeholder="Invoice #" required>
                    </div>
                </div>
            </div>

            <div class="col-4 px-4">
                <h3 class="form-group-heading m-0"><i class="la la-radiation me-2"></i> Other</h3>
                <div class="row">
                    <!-- Other Description -->
                    <div class="form-group col-md-12">
                        <label for="other_description">Other</label>
                        <textarea class="form-control" id="other_description" name="other_description" rows="1" placeholder="Description of extra costs."></textarea>
                    </div>

                    <!-- Other Cost -->
                    <div class="form-group col-md-6">
                        <label for="other_cost">Other Cost</label>
                        <input type="number" step="0.01" class="form-control" id="other_cost" name="other_cost" placeholder="Cost $" required>
                    </div>
                </div>
            </div>
            <div class="form-group px-4 mt-4">
                <button type="submit" class="btn btn-primary btn-submission">Submit</button>
                <button type="reset" class="btn btn-secondary btn-submission mx-2">Clear</button>
            </div>
        </div>
    </div>



              {{-- load the view from the application if it exists, otherwise load the one in the package --}}
{{--		      @if(view()->exists('vendor.backpack.crud.form_content'))--}}
{{--		      	@include('vendor.backpack.crud.form_content', [ 'fields' => $crud->fields(), 'action' => 'create' ])--}}
{{--		      @else--}}
{{--		      	@include('crud::form_content', [ 'fields' => $crud->fields(), 'action' => 'create' ])--}}
{{--		      @endif--}}
{{--                --}}{{-- This makes sure that all field assets are loaded. --}}
{{--                <div class="d-none" id="parentLoadedAssets">{{ json_encode(Basset::loaded()) }}</div>--}}

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
