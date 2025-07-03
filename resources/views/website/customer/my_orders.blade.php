@extends(backpack_view('blank'))

@section('header')
{{--    <section class="container-fluid header">--}}
{{--        <h2 class="title text-capitalize">--}}
{{--            Dashboard--}}
{{--        </h2>--}}
{{--        <small>--}}
{{--            <a href="{{ url('admin/manual-payments/create') }}" class="btn btn-add btn-sm mx-3 btn-manual"><i class="la la-wallet mx-2"></i> Manual Payment</a>--}}

{{--            <button id="create-order-btn" data-bs-toggle="modal" data-bs-target="#orderSummaryModal" class="btn btn-add btn-sm"><i class="la la-plus mx-2"></i> New Order</button>--}}
{{--        </small>--}}
{{--    </section>--}}
@endsection

@section('content')
    <div class="container my-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">My Orders</h3>
                    </div>
                    <div class="card-body">
                        @if($orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer Name</th>
                                        <th>Delivery Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Origin</th>
                                        <th>Recurring</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->customer_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="badge
                                                    @if($order->status == 'completed') badge-success
                                                    @elseif($order->status == 'pending') badge-warning
                                                    @elseif($order->status == 'cancelled') badge-danger
                                                    @else badge-secondary
                                                    @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>${{ number_format($order->total_cost, 2) }}</td>
                                            <td>{{ $order->origin }}</td>
                                            <td>
                                                @if($order->recurring)
                                                    <span class="badge badge-info">Yes</span>
                                                @else
                                                    <span class="badge badge-secondary">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary btn-view"
                                                        data-order-id="{{ $order->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#orderDetailsModal">
                                                    <i class="la la-eye"></i> View
                                                </button>

                                                {{-- Add reorder button if needed --}}
                                                @if($order->status == 'completed')
                                                    <button class="btn btn-sm btn-success btn-reorder"
                                                            data-order-id="{{ $order->id }}">
                                                        <i class="la la-refresh"></i> Reorder
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination section --}}
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <form action="{{ route('customer.orders') }}" method="GET" class="mb-3">
                                        {{-- Preserve any existing filter parameters --}}
                                        @foreach(request()->except(['page', 'per_page']) as $key => $value)
                                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                        @endforeach

                                        <div class="form-group d-flex align-items-center">
                                            <label for="per_page" class="mb-0 me-2">Entries per page</label>
                                            <select name="per_page" id="per_page" class="form-control" style="width: auto;" onchange="this.form.submit()">
                                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-end">
                                        {{-- Pagination links --}}
                                        @if($isPaginated)
                                            {{ $orders->appends(request()->except('page'))->links() }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <h5>No orders found</h5>
                                <p class="text-muted">You haven't placed any orders yet.</p>
                                <a href="{{ route('home') }}" class="btn btn-primary">
                                    <i class="la la-shopping-cart"></i> Start Shopping
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>@endsection
