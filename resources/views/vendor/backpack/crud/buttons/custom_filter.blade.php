@php
    // Fetch orders directly from the Order model
    $orders = \App\Models\Order::select('id', 'customer_name')->get();
@endphp

<form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center gap-3">



    <!-- Type Dropdown -->
    <div class="position-relative">
        <select name="type" class="form-select">
            <option value="">Type</option>
            <option value="recurring" {{ request('type') == 'recurring' ? 'selected' : '' }}>Recurring</option>
            <option value="non-recurring" {{ request('type') == 'manual' ? 'selected' : '' }}>Non Recurring</option>
        </select>
    </div>

    <!-- Status Dropdown -->
    <div class="position-relative">
        <select name="status" class="form-select">
            <option value="">Status</option>
            <option value="valid" {{ request('status') == 'valid' ? 'selected' : '' }}>Valid</option>
            <option value="skip" {{ request('status') == 'skip' ? 'selected' : '' }}>Skip</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
    </div>

    <!-- Transfer Status Dropdown -->
    <div class="position-relative">
        <select name="transfer_status" class="form-select">
            <option value="">Transfer Status</option>
            <option value="transferred" {{ request('transfer_status') == 'transferred' ? 'selected' : '' }}>Transferred</option>
            <option value="not_transferred" {{ request('transfer_status') == 'not_transferred' ? 'selected' : '' }}>Not Transferred</option>
        </select>
    </div>

{{--    <!-- Customer ID Input -->--}}
{{--    <select name="order_id" id="order_id" class="form-control" style="width: 250px;">--}}
{{--        <option value="">Search Customer Id</option>--}}
{{--        <option value="101" {{ request('order_id') == '101' ? 'selected' : '' }}>Order #101</option>--}}
{{--        <option value="102" {{ request('order_id') == '102' ? 'selected' : '' }}>Order #102</option>--}}
{{--        <option value="103" {{ request('order_id') == '103' ? 'selected' : '' }}>Order #103</option>--}}
{{--        <option value="104" {{ request('order_id') == '104' ? 'selected' : '' }}>Order #104</option>--}}
{{--        <option value="105" {{ request('order_id') == '105' ? 'selected' : '' }}>Order #105</option>--}}
{{--    </select>--}}

    <!-- Apply Button -->
    <button type="submit" class="btn btn-outline-primary">
        Apply
    </button>
</form>

{{--<!-- Include Select2 & jQuery -->--}}
{{--<!-- Include Select2 & jQuery -->--}}
{{--@push('crud_fields_scripts')--}}
{{--    <script>--}}
{{--        $(document).ready(function() {--}}
{{--            $('#order_id').select2({--}}
{{--                placeholder: "Search Order ID",--}}
{{--                allowClear: true,--}}
{{--                width: '100%'--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}
