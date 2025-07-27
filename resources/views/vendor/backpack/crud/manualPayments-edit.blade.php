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

@section('content')
<div class="container">
  <h1>Manual Payment</h1>
    <div class="row my-5">
        <div class="col-12 col-lg-9">
            <form action="{{ route('payments.update', ['id' => $entry->id]) }}" method="POST" class="card p-5">
                @csrf
                <div class="row">
                    <div class="col-8 px-4 my-3">
                        <h3 class="form-group-heading m-0"><i class="la la-cart-plus me-2"></i> Order</h3>
                        <div class="form-group">
                            <label for="order-number">Order #</label>
                            <select id="order-number" name="order_number" class="form-control" style="width: 100%" required>
                                <option value="{{ $entry->order_number }}"
                                    {{ old('order_number') == $entry->order_number ? 'selected' : '' }}>
                                    {{ $entry->order_number }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4 px-4 my-3">
                        <h3 class="form-group-heading m-0"><i class="la la-cart-plus me-2"></i> Order</h3>
                        <div class="form-group">
                            <label for="order-type">type</label>
                            <select id="order-type" name="order_type" class="form-control" style="width: 100%">
                                <option value="simple">Simple</option>
                                <option value="recurring" {{$entry->recurring_order_id?'selected':''}}>Recurring</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6 px-4">
                        <h3 class="form-group-heading m-0"><i class="la la-user-circle me-2"></i> Contact</h3>
                        <div class="form-group">
                            <label for="contact-name">Name</label>
                            <input type="text" class="form-control" id="contact-name" name="contact_name"
                                   value="{{ old('contact_name', $entry->contact_name ?? '') }}"
                                   placeholder="Contact Name" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="contact-email">Email</label>
                            <input type="email" class="form-control" id="contact-email" name="email"
                                   value="{{ old('email', $entry->email ?? '') }}"
                                   placeholder="Email" required readonly>
                        </div>
                    </div>

                    <div class="col-6 px-4">
                        <h3 class="form-group-heading m-0"><i class="la la-credit-card me-2"></i> Payment</h3>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" name="description"
                                   value="{{ old('description', $entry->description ?? '') }}"
                                   placeholder="Description">
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount"
                                   value="{{ old('amount', $entry->amount ?? 0) }}"
                                   placeholder="Amount - example 15.75" required readonly>
                        </div>
                    </div>

                </div>
                <div class="form-group px-3">
                    <button type="submit" class="btn btn-primary btn-submission">Update Manual Payment</button>
                    <button type="button" class="btn btn-secondary btn-submission mx-2" onclick="window.location.href='/admin/manual-payments'">
                        Close
                    </button>
                    <button type="button" class="btn btn-danger btn-submission float-end" onclick="confirmDelete()">
                        Delete
                    </button>
                </div>
            </form>
            <form id="delete-form" method="POST" action="{{ route('payments.custom_delete', $entry->id) }}" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

<style>
    form.card {
        padding: 25px;
        background: white;
        border-radius: 20px;
        margin-top: 15px;
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
    form .select2.select2-container {
        width: 250px;
        border-radius: 8px;
        line-height: 19.1px;
        border: 1px solid rgba(213, 213, 213, 1);
        background: white;
        padding: 3px;
    }
    span.select2-selection.select2-selection--single, .select2-selection.select2-selection--multiple ul.select2-selection__rendered {
        border: none;
        background: transparent;
        padding:  4px;
        color: rgba(141, 141, 141, 1);
    }
    .select2-search__field {
        border: 1px solid gray;
    }

    footer {
        display: none;
    }
</style>
@endsection
@push('after_scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        $(document).ready(function() {
            $('#order-number').select2({
                theme: 'bootstrap-5', // Optional if using Bootstrap 5
                placeholder: 'Enter Order ID',
                ajax: {
                    url: '{{ route('orders.ajax-search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            order_type: $('#order-type').val()
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(order => ({
                                id: order.id,
                                text: $('#order-type').val() == 'simple' ? `#${order.id} - ${order.customer_name}` :order.text ,
                            }))
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            });
        });


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
@endpush
