@extends(backpack_view('blank'))

@section('content')
<div class="container">
  <h1>Manual Payment</h1>
    <div class="row my-5">
        <div class="col-12 col-lg-9">
            <form action="" class="card p-5">
                <div class="row">
                    <div class="col-6 px-4">
                        <h3 class="form-group-heading m-0"><i class="la la-user-circle me-2"></i> Contact</h3>
                        <div class="form-group">
                            <label for="contact-name">Name</label>
                            <input type="text" class="form-control" id="contact-name" placeholder="Contact Name">
                        </div>
                        <div class="form-group">
                            <label for="contact-email">Email</label>
                            <input type="email" class="form-control" id="contact-email" placeholder="Email">
                        </div>
                    </div>

                    <div class="col-6 px-4">
                        <h3 class="form-group-heading m-0"><i class="la la-credit-card me-2"></i> Payment</h3>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" placeholder="Description">
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="text" class="form-control" id="amount" placeholder="Amount - example 15.75">
                        </div>
                    </div>

                    <div class="col-6 px-4 my-3">
                        <h3 class="form-group-heading m-0"><i class="la la-cart-plus me-2"></i> Order</h3>
                        <div class="form-group">
                            <label for="order-number">Order #</label>
                            <input type="text" class="form-control" id="order-number" placeholder="Order #">
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
