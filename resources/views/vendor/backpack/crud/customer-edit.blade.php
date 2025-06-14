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
              action="{{ route('customer.update', ['id' => $entry->id]) }}">
            {!! csrf_field() !!}

            <div class="row">
                <div class="col-md-12 px-4">
                    <h3 class="form-group-heading m-0 mb-4"><i class="la la-user me-2"></i> Customer Information</h3>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ old('name', $entry->name ?? '') }}" placeholder="Name" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="{{ old('email', $entry->email ?? '') }}" placeholder="Email" required>
                        </div>


                        <div class="form-group col-md-4">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                   value="{{ old('phone', $entry->phone ?? '') }}" placeholder="Phone" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address"
                                   value="{{ old('address', $entry->address ?? '') }}" placeholder="Address" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city"
                                   value="{{ old('city', $entry->city ?? '') }}" placeholder="City" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="postal_code">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code"
                                   value="{{ old('postal_code', $entry->postal_code ?? '') }}" placeholder="Postal Code" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="province">Province</label>
                            <select class="form-control" id="province" name="province" required>
                                <option value="">-- Select Province --</option>
                                <option value="BC" {{ old('province', $entry->province ?? '') == 'BC' ? 'selected' : '' }}>BC</option>
                                <option value="AB" {{ old('province', $entry->province ?? '') == 'AB' ? 'selected' : '' }}>AB</option>
                            </select>
                        </div>

                        <div class="form-group d-none col-md-4">
                            <label for="country">Country</label>
                            <input type="text" class="form-control" id="country" name="country"
                                   value="Canada" readonly>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn-primary btn-submission btn" type="submit">Update</button>
                        <button type="button" class="btn btn-secondary btn-submission mx-2" onclick="window.location.href='/admin/customers'">
                            Close
                        </button>
                        <button type="button" class="btn btn-danger btn-submission float-end" onclick="confirmDelete()">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <form id="delete-form" method="POST" action="{{ route('customer.custom_delete', $entry->id) }}" style="display:none;">
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
