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
            <div class="row">
                <h3 class="form-group-heading m-0"><i class="la la-boxes me-2"></i> Inventory Information</h3>

                <div class="form-group col-md-12">
                    <label for="ice_on_hand">Ice On Hand</label>
                    <input type="text" class="form-control" id="ice_on_hand" name="ice_on_hand" placeholder="e.g., 1405" required>
                </div>
                <div class="form-group col-md-12">
                    <label for="ice_on_hand_date">Ice On Hand Date</label>
                    <input type="date" class="form-control" id="ice_on_hand_date" name="ice_on_hand_date" required>
                </div>

                <h3 class="form-group-heading m-0 mt-5 m-2"><i class="la la-percent me-2"></i> Rate Information</h3>

                <div class="form-group col-md-12">
                    <label for="sublimation_rate">Sublimation Rate</label>
                    <input type="number" step="0.01" class="form-control" id="sublimation_rate" name="sublimation_rate" placeholder="e.g., 4" required>
                </div>
                <div class="form-group col-md-12">
                    <label for="us_exchange">US Exchange</label>
                    <input type="number" step="0.01" class="form-control" id="us_exchange" name="us_exchange" placeholder="e.g., 1.0" required>
                </div>
            </div>
        </div>

        <div class="col-8 px-4">
            <h3 class="form-group-heading m-0"><i class="la la-donate me-2"></i> Cost Information</h3>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="border_cost">Border Cost</label>
                        <input type="number" step="0.01" class="form-control" id="border_cost" name="border_cost" placeholder="e.g., 15.0" required>
                    </div>
                    <div class="form-group">
                        <label for="online_box_charge_van1">Online Box Charge - Vancouver</label>
                        <input type="number" step="0.01" class="form-control" id="online_box_charge_van1" name="online_box_charge_van1" placeholder="e.g., 300" required>
                    </div>
                    <div class="form-group">
                        <label for="online_ice_cost_van1">Online Ice Cost - Vancouver</label>
                        <input type="number" step="0.01" class="form-control" id="online_ice_cost_van1" name="online_ice_cost_van1" placeholder="e.g., 195" required>
                    </div>
                    <div class="form-group">
                        <label for="shipping_cost_per_10">Shipping Cost per 10</label>
                        <input type="number" step="0.01" class="form-control" id="shipping_cost_per_10" name="shipping_cost_per_10" placeholder="e.g., 150.00" required>
                    </div>
                    <div class="form-group">
                        <label for="online_hazmat_cost">Online Hazmat Cost</label>
                        <input type="number" step="0.01" class="form-control" id="online_hazmat_cost" name="online_hazmat_cost" placeholder="e.g., 6.95" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="online_other_cost">Online Other Cost</label>
                        <input type="number" step="0.01" class="form-control" id="online_other_cost" name="online_other_cost" placeholder="e.g., 120" required>
                    </div>
                    <div class="form-group">
                        <label for="online_box_charge_van2">Online Box Charge - Vancouver</label>
                        <input type="number" step="0.01" class="form-control" id="online_box_charge_van2" name="online_box_charge_van2" placeholder="e.g., 1500" required>
                    </div>
                    <div class="form-group">
                        <label for="online_ice_cost_van2">Online Ice Cost - Vancouver</label>
                        <input type="number" step="0.01" class="form-control" id="online_ice_cost_van2" name="online_ice_cost_van2" placeholder="e.g., 200" required>
                    </div>
                    <div class="form-group">
                        <label for="tote_return_cost">Tote Return Cost</label>
                        <input type="number" step="0.01" class="form-control" id="tote_return_cost" name="tote_return_cost" placeholder="e.g., 50" required>
                    </div>
                    <div class="form-group">
                        <label for="total_expenses">Total Expenses</label>
                        <input type="number" step="0.01" class="form-control" id="total_expenses" name="total_expenses" placeholder="e.g., 0" required>
                    </div>
                </div>
            </div>
        </div>

    </div>
              <div class="px-4 mt-3">
                  <button class="btn-primary btn-submission btn" type="submit">Submit</button>
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

        <div class="row">
            <div class="col-lg-8">
                <form method="post" class="card"
                      action="{{ url($crud->route) }}"
                      @if ($crud->hasUploadFields('create'))
                          enctype="multipart/form-data"
                    @endif
                >
                    {!! csrf_field() !!}

                    <div class="row">
                        <div class="col-12 px-4">
                            <div class="row">
                                <h3 class="form-group-heading m-0"><i class="la la-plus me-2"></i> Add New Variable</h3>

                                <div class="form-group col-md-6">
                                    <label for="variable_name">Variable Name</label>
                                    <input type="text" class="form-control" id="variable_name" name="name" placeholder="Variable Name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="variable_value">Value</label>
                                    <input type="text" class="form-control" id="variable_value" name="value"  placeholder="Value" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 mt-2">
                        <button class="btn-primary btn-submission btn" type="submit">Add</button>
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

    footer {
        display: none;
    }

</style>

@endsection
