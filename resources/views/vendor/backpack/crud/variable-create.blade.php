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
