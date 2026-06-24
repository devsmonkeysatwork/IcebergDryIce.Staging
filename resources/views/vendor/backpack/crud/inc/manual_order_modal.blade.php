@php
    $isEditMode = $mode === 'edit';
@endphp

<div class="modal-header border-0">
    <h4 class="modal-title fw-bold">
        <i class="la la-shopping-cart mx-2"></i>
        {{ $modalTitle }}
    </h4>
    <button type="button"
            class="btn-close"
            data-bs-dismiss="modal">
    </button>

</div>

<form id="order-form" method="POST">
    @csrf
    @if($isEditMode)
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="order_id" value="{{ $order->id }}">
    @endif

    <input type="hidden" name="origin" value="manual">

    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Customer
                    </label>

                    <select
                        id="manual-customer-id"
                        name="customer_id"
                        class="form-select"
                        required>

                        <option value="">Select Customer</option>

                        @foreach($customers as $customer)
                            <option
                                value="{{ $customer->id }}"
                                @selected(old('customer_id', $defaultValues['customer_id'] ?? null) == $customer->id)>
                                {{ $customer->name }} ({{ $customer->email }})
                            </option>
                        @endforeach

                    </select>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Product Type
                    </label>

                    <select
                        id="manual-product-id"
                        name="product"
                        class="form-select"
                        required>

                        <option value="">Select Product</option>

                        @foreach($products as $product)
                            <option
                                value="{{ $product->id }}"
                                @selected(old('product_id', $defaultValues['product_id'] ?? null) == $product->id)>
                                {{ $product->product_name }}
                            </option>
                        @endforeach

                    </select>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Amount (lbs/boxes)
                    </label>

                    <input
                        type="number"
                        min="1"
                        step="1"
                        class="form-control"
                        name="amount"
                        value="{{ old('amount', $defaultValues['amount'] ?? 1) }}"
                        required>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label fw-bold d-block">
                        Order Type
                    </label>

                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="pickup_delivery"
                            value="delivery"
                            id="delivery-option"
                            {{ ($defaultValues['pickup_delivery'] ?? 'delivery') == 'delivery' ? 'checked' : '' }}>

                        <label class="form-check-label" for="delivery-option">
                            Delivery
                        </label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="pickup_delivery"
                            value="pickup"
                            id="pickup-option"
                            {{ ($defaultValues['pickup_delivery'] ?? '') == 'pickup' ? 'checked' : '' }}>

                        <label class="form-check-label" for="pickup-option">
                            Pickup
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        PO #
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        name="po_number"
                        value="{{ old('po_number', $defaultValues['po_number'] ?? '') }}">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Recurring
                    </label>

                    <select
                        class="form-select"
                        name="recurring">

                        <option value="non-recurring"
                            {{ ($defaultValues['recurring'] ?? '') == 'non-recurring' ? 'selected' : '' }}>
                            No
                        </option>

                        <option value="recurring"
                            {{ ($defaultValues['recurring'] ?? '') == 'recurring' ? 'selected' : '' }}>
                            Yes
                        </option>

                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Date
                    </label>

                    <input
                        min="{{\Illuminate\Support\Carbon::now()->format('Y-m-d')}}"
                        type="date"
                        class="form-control"
                        id="modal-delivery-date"
                        name="delivery_date"
                        value="{{ old('delivery_date', $defaultValues['delivery_date'] ?? '') }}"
                        required>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Time
                    </label>

                    <input
                        min="{{\Illuminate\Support\Carbon::now()->format('h:m')}}"
                        type="time"
                        class="form-control"
                        id="modal-delivery-time"
                        name="delivery_time"
                        value="{{ old('delivery_time', $defaultValues['delivery_time'] ?? '') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Notes
                    </label>

                    <textarea
                        class="form-control"
                        rows="4"
                        name="notes">{{ old('notes', $defaultValues['notes'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

    </div>

    <div class="modal-footer border-0">

        <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal">
            Cancel
        </button>
        @if($order->payment_status !== 'paid')
            <button
                type="button"
                id="save-order-btn"
                class="btn btn-primary"
                data-mode="{{ $mode }}"
                @if($isEditMode)
                    data-order-id="{{ $order->id }}"
                @endif>
                {{ $saveButtonText }}
            </button>
        @endif

        @if($isEditMode)
            <button
                type="button"
                id="delete-order-btn"
                class="btn btn-danger"
                data-order-id="{{ $order->id }}">
                Delete
            </button>
        @endif

    </div>
</form>
