@extends(backpack_view('blank'))

@section('content')
<div class="container">
  <h1>Manual Payment</h1>
    <div class="row my-5">
        <div class="col-12 col-lg-9">
            <form action="{{ route('manual-payments.store') }}" method="POST" class="card p-5">
                @csrf
                <div class="row">
                    <div class="col-6 px-4">
                        <h3 class="form-group-heading m-0"><i class="la la-user-circle me-2"></i> Contact</h3>
                        <div class="form-group">
                            <label for="contact-name">Name</label>
                            <input type="text" class="form-control" id="contact-name" name="contact_name" placeholder="Contact Name" required>
                        </div>
                        <div class="form-group">
                            <label for="contact-email">Email</label>
                            <input type="email" class="form-control" id="contact-email" name="email"  placeholder="Email" required>
                        </div>
                    </div>

                    <div class="col-6 px-4">
                        <h3 class="form-group-heading m-0"><i class="la la-credit-card me-2"></i> Payment</h3>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" name="description"  placeholder="Description" required>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount - example 15.75" required>
                        </div>
                    </div>

                    <div class="col-6 px-4 my-3">
                        <h3 class="form-group-heading m-0"><i class="la la-cart-plus me-2"></i> Order</h3>
                        <div class="form-group">
                            <label for="order-number">Order #</label>
                            <select id="order-number" name="order_number" class="form-control" style="width: 100%" required></select>
                        </div>
                    </div>

                </div>
                <div class="form-group px-3">
                    <button type="submit" class="btn btn-primary btn-submission">Review</button>
                    <button type="reset" class="btn btn-secondary btn-submission mx-2">Clear</button>
                </div>
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

    footer {
        display: none;
    }
</style>
@endsection
@push('after_scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>
        $(document).ready(function() {
            $('#order-number').select2({
                theme: 'bootstrap-5', // Optional if using Bootstrap 5
                placeholder: 'Search Order #',
                ajax: {
                    url: '{{ route('orders.ajax-search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // search term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(order => ({
                                id: order.id,
                                text: `#${order.id} - ${order.customer_name}`
                            }))
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            });
        });
    </script>
@endpush
