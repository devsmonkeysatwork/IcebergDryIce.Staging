@extends(backpack_view('blank'))

@section('content')
<div class="container">
    <h1 class="text-capitalize mb-0" bp-section="page-heading">Emails</h1>
    <div class="row">
        <div class="col-md-10">
            <div class="card mt-3 p-5">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <h3 class="form-group-heading"><i class="la la-envelope  me-2"></i>Templates</h3>
                            <input type="date" class="date-input-field" id="date-input">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="btn-group-vertical">
                            <button class="btn btn-secondary email-temp-btn">All</button>
                            <button class="btn btn-secondary email-temp-btn">Deliveries</button>
                            <button class="btn btn-secondary email-temp-btn">Kuehne & Nagel</button>
                            <button class="btn btn-secondary email-temp-btn">Praxair</button>
                            <button class="btn btn-secondary email-temp-btn">Sling Shot</button>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <form>
                            <div class="form-group">
                                <label for="email-to">To</label>
                                <input type="email" class="form-control" id="email-to" value="admin@icebergdryice.com">
                            </div>
                            <div class="form-group">
                                <label for="email-subject">Subject</label>
                                <input type="text" class="form-control" id="email-subject" value="Dry Ice Orders - Jul 11">
                            </div>
                            <div class="form-group">
                                <label for="email-body">Body</label>
                                <textarea class="form-control" id="email-body" rows="5">
            Konscious    60 lbs. Dry Ice
            Luniu Mall    300 lbs. Dry Ice
            Mott 32    25 lbs. Dry Ice
            Nutri Science    30 lbs. Dry Ice
          </textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-submission float-end">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .card {
        border-radius: 15px;
    }
    .form-group-heading {
        font-weight: 800;
        font-size: 24px;
        line-height: 36px;
        letter-spacing: -0.11px;
    }
    .form-control {
        border-radius: 10px !important;
    }

    .email-temp-btn {
        width: 220px;
        height: 50px;
        border-radius: 15px !important;
        margin: 8px;
        font-weight: 700;
        font-size: 16px;
        line-height: 24px;
        letter-spacing: 0px;
        text-align: center;
        color: rgba(0, 0, 0, 1);
        background: white;
        border: 1px solid rgba(158, 158, 158, 1)
    }
    .email-temp-btn:hover {
        color: white;
        background: rgba(69, 75, 90, 1);
    }
    .date-input-field {
        width: 100px;
        height: 25px;
        font-weight: 500;
        font-size: 12px;
        line-height: 14.06px;
        letter-spacing: 0px;
        border: 1px solid rgba(158, 158, 158, 1);
        border-radius: 7px;
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
