@extends(backpack_view('blank'))

@section('header')
<section class="container-fluid header">
    <h1 class="text-capitalize mb-0" bp-section="page-heading">Inventory</h1>

  <small><a href="{{ url('admin/inventory/create') }}" class="btn btn-add btn-sm btn-primary"><i class="la la-plus"></i> Add Inventory</a></small>
</section>
@endsection

@section('content')
<div class="container-fluid">
  <!-- <div class="row flex-row mb-3">
    <div class="col-md-6">
      <div class="d-flex align-items-center">
        <span>Showing {{ $entries->count() }} of {{ $entries->total() }} entries</span>
        <form action="{{ url()->current() }}" method="GET" class="ml-3">
          <button type="submit" class="btn btn-sm btn-secondary">Reset</button>
        </form>
      </div>
    </div>
  </div> -->

  <div class="row flex-row">
    <div class="col-md-8">
      <div class="card text-center">
        <div class="card-header">
          Month
        </div>
        <div class="card-body inventory">
          <div class="d-flex align-items-center">
            <div class="stat">
                <div class="d-flex align-items-center">
                    <img src="{{asset('reports_volume.png')}}" class="me-2" alt="">
                    <h5 class="text-start m-0"><span>1000 lbs</span> in volume</h5>
                </div>
                <div class="d-flex align-items-center">
                    <img src="{{asset('reports_sales.png')}}" class="me-2" alt="">
                    <h5 class="text-start m-0"><span>$30,000</span> in sales</h5>
                </div>
                <div class="d-flex align-items-center">
                    <img src="{{asset('reports_revenue.png')}}" class="me-2" alt="">
                    <h5 class="text-start m-0"><span>$25,000</span> in revenue</h5>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-header">
          On Hand
        </div>
        <div class="card-body inventory">
          <div class="stat right">
              <div class="d-flex align-items-center">
                  <img src="{{asset('dry-ice-hand.png')}}" class="me-2" alt="">
                  <h5 class="text-start m-0"><span>{{ $onHand }} lbs</span> Dry Ice on hand</h5>
              </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="card text-center">
        <div class="card-header">
          Today
        </div>
        <div class="card-body inventory">
          <div class="d-flex align-items-center">
            <div class="stat">
              <div class="d-flex">
                  <img src="{{asset('reports_volume.png')}}" class="me-2" alt="">
                  <h5 class="text-start m-0"><span>50 lbs</span> in volume</h5>
              </div>
              <div class="d-flex align-items-center">
                  <img src="{{asset('reports_sales.png')}}" class="me-2" alt="">
                  <h5 class="text-start m-0"><span>$1,500</span> in sales</h5>
              </div>
              <div class="d-flex align-items-center">
                  <img src="{{asset('reports_revenue.png')}}" class="me-2" alt="">
                  <h5 class="text-start m-0"><span>$1,200</span> in revenue</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-header">
          To Be Received
        </div>
        <div class="card-body inventory">
          <div class="stat right">
              <div class="d-flex align-items-center">
                  <img src="{{asset('reports_recieved.png')}}" class="me-2" alt="">
                  <h5 class="text-start m-0"><span>{{ $toBeReceived }} lbs</span> Dry Ice to be received</h5>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Start of Day</th>
            <th>Warehouse Orders</th>
            <th>Praxair Supply Orders</th>
            <th>Online Orders</th>
            <th>To be Received</th>
            <th>End of Day</th>
            <th>Sublimation</th>
{{--            <th>Actions</th>--}}
          </tr>
        </thead>
        <tbody>
          @foreach ($entries as $entry)
          <tr>
            <td>{{ $entry->date }}</td>
            <td>{{ $entry->start_of_day }}</td>
            <td>{{ $entry->warehouse_orders }}</td>
            <td>{{ $entry->praxair_supply_orders }}</td>
            <td>{{ $entry->online_orders }}</td>
            <td>{{ $entry->to_be_received }}</td>
            <td>{{ $entry->end_of_day }}</td>
            <td>{{ $entry->sublimation }}</td>
{{--            <td>--}}
{{--              {!! $entry->getPreviewButton($crud) !!}--}}
{{--              {!! $entry->getEditButton($crud) !!}--}}
{{--              {!! $entry->getDeleteButton($crud) !!}--}}
{{--            </td>--}}
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-md-3">
      <form action="{{ url()->current() }}" method="GET">
        <div class="form-group entries">
          <label for="per_page">entries per page</label>
          <select name="per_page" id="per_page" class="form-control" onchange="this.form.submit()">
            <option value="10" {{ request()->per_page == 10 ? 'selected' : '' }}>10</option>
            <option value="25" {{ request()->per_page == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ request()->per_page == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request()->per_page == 100 ? 'selected' : '' }}>100</option>
          </select>
        </div>
      </form>
    </div>
    <div class="col-md-6">
      {{ $entries->appends(request()->except('page'))->links() }} <!-- Pagination links -->
    </div>
  </div>
</div>

@endsection

@section('after_styles')
@vite(['resources/scss/app.scss', 'resources/css/custom.css'])
<style>

    body {
      background-color: #F5F6FA;
      color: black;
    }

    .navbar .nav-link {
      display: flex;
    }

    .container-fluid h2 .title {
      font-weight: 700;
    }

    .container-fluid.header {
      display: flex;
      justify-content: space-between;
      padding: 0 24px 20px;
    }

    .container-fluid .btn-add {
      padding: 8px 16px;
      font-weight: 500;
      font-size: 14px;
    }

    .row.flex-row {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }

    .row.flex-row > .col-md-3 {
      flex: 1;
      min-width: 250px;
      display: flex;
    }

    .card {
      flex: 1;
      border: none;
      border-radius: 8px;
      box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
      background-color: #221e26;
    }

    .card-header {
      --bs-bg-opacity: 1 !important;
      background-color: #221e26 !important;
      color: #ffffff;
      border-top-left-radius: 15px !important;
      border-top-right-radius: 15px !important;
      font-weight: 700;
    }

    .card-body {
      background-color: white;
      position: relative;
      padding: 24px 32px 16px;
      text-align: left;
      height: 130px;
      border-bottom-left-radius: 8px;
      border-bottom-right-radius: 8px;
    }

    .card-body h3 {
      font-weight: 700;
    }
    .card-body .stat img {
        width: 32px;
        object-fit: contain;
    }

    .card-body .stat h5 {
        font-weight: 900;
        font-size: 24px;
        line-height: 24px;
        letter-spacing: 0px;
    }

    .card-body .card-text {
      color: var(--tblr-card-text);
      font-size: 14px;
      transform: translateY(-40%);
    }



    .card.dashboard .card-body .card-text.stat {
      position: absolute;
      bottom: 5px;
      left: 32px;
      font-size: 16px;
      width: fit-content;
    }

    .card-body .card-text span {
      color: var(--tblr-success);
      margin-right: 4px;
    }

    .card .card-body.inventory {
      height: fit-content;
      padding: 16px 64px;
    }

    .card .card-body .stat {
      display: flex;
      justify-content: space-between;
      width: 100%;
      align-items: center;
      text-align: center;
    }

    .card .card-body .stat.right {
      flex-direction: column;
    }

    .card .card-body .stat h5 {
      display: flex;
      flex-direction: column;
      text-transform: uppercase;
      font-size: 12px
    }

    .card .card-body .stat h5 span {
      font-size: 24px;
      font-weight: 700;
    }

    .row .table-header {
      font-weight: 500;
    }

    .row .table-head-wrapper {
      overflow: hidden;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
    }

    .row table {
      width: 100%;
      margin-bottom: 0;
      border-collapse: separate;
      border-spacing: 0 15px;
    }

    .row table thead tr {
      --bs-bg-opacity: 1 !important;
      background-color: #221e26 !important;
      color: var(--tblr-secondary-text-emphasis);
      text-transform: capitalize;
      padding: 10px 0;
    }

    .row table thead tr th {
      color: white;
      text-transform: capitalize;
      font-size: 14px;
      font-weight: 500;
    }

    .row table th,
    .row table td {
      font-size: 14px;
      padding: 12px 15px;
      vertical-align: middle;
    }

    .row table tbody tr {
      background-color: white;
      color: var(--tblr-body-color);
      box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
      padding: 10px 5px;
    }

    .row table tbody tr td {
      padding: 15px;
    }


    .row table tbody tr:hover {
        background: rgba(253, 255, 174, 1);
    }

    .row .table.recurring {
      margin-top: 38px;
    }

    .form-group.entries {
      display: flex;
      flex-direction: row-reverse;
      float: inline-start;
      gap: 5px;
      font-size: 14px;
    }

    .form-group .form-control {
      width: 62px;
      height: 23.14px;
      font-size: 14px;
      padding: 0;
    }

    /* Status Colors */

    td.status {
      text-transform: capitalize;
      transform: translateY(10px);
    }

    .status.valid {
      color: var(--tblr-success);
      font-weight: bold;
    }

    .status.skip {
      color: var(--tblr-warning);
      font-weight: bold;
    }

    .status.cancelled {
      color: var(--tblr-danger);
      font-weight: bold;
    }

    .btn-add {
      background-color: var(--tblr-primary);
      border: none;
      color: var(--tblr-light);
      border-radius: 4px;
    }

    .btn-add:hover {
      background-color: #0246a5;
      color: var(--tblr-light);
    }

    @media (min-width: 1400px) {
      .container-xxl,
      .container-xl,
      .container-lg,
      .container-md,
      .container-sm,
      .container {
        max-width: 1328px;
      }
    }

    footer {
        display: none;
    }

</style>
@endsection

@section('after_scripts')
@vite(['resources/js/app.js'])
@endsection
