<div class="modal-header border-0">
    <h4 class="modal-title fw-bold">
        <i class="la la-file-invoice mx-2"></i>
        <span>{{ $modalTitle }}</span>
    </h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="row">
        <!-- Order Details -->
        <div class="col-md-3">
            <h5><i class="la la-shopping-cart"></i> Order</h5>

            @if($showOrderId)
                <div class="mb-2">
                    <label class="form-label">Order #</label>
                    <input id="modal-order-id" class="form-control" value="{{ $defaultValues['order_id'] }}" readonly>
                </div>
            @endif

            <div class="mb-2">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <select id="modal-customer-email" class="form-control" style="width: 100%;" required {{ $mode === 'edit' ? 'disabled' : '' }}>
                    @if($mode === 'edit' && isset($defaultValues['customer_email']))
                        <option value="{{ $defaultValues['customer_email'] }}" selected>{{ $defaultValues['customer_email'] }}</option>
                    @endif
                </select>
                @if($mode === 'edit')
                    <input type="hidden" name="customer_email" value="{{ $defaultValues['customer_email'] }}">
                @endif
            </div>

            <div class="mb-2">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input id="modal-customer-name" class="form-control" value="{{ $defaultValues['customer_name'] }}" required>
            </div>

            <div class="mb-2">
                <label class="form-label">Phone<span class="text-danger">*</span></label>
                <input id="modal-customer-phone" class="form-control" type="tel" value="{{ $defaultValues['customer_phone'] }}" required>
            </div>

            <div class="mb-2">
                <label class="form-label">Amount of Ice (lbs) <span class="text-danger">*</span></label>
                <input id="modal-ice-amount" class="form-control" type="number" min="0" step="0.1" value="{{ $defaultValues['ice_amount'] }}" required>
            </div>

            <div class="mb-2">
                <label class="form-label">Amount of Boxes</label>
                <input id="modal-box-amount" class="form-control" type="number" min="0" step="1" value="{{ $defaultValues['box_amount'] }}">
            </div>

            <div class="mb-2">
                <label class="form-label">Recurring <span class="text-danger">*</span></label>
                <select id="modal-recurring" class="form-select">
                    <option value="">Select...</option>
                    <option value="recurring" {{ $defaultValues['recurring'] === 'recurring' ? 'selected' : '' }}>Yes</option>
                    <option value="non-recurring" {{ $defaultValues['recurring'] === 'non-recurring' ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>

        <!-- Delivery Details -->
        <div class="col-md-5 px-3">
            <h5><i class="la la-truck"></i> Delivery</h5>

            <div class="mb-2">
                <label class="form-label">Location Name<span class="text-danger">*</span></label>
                <input id="modal-location-name" type="text" class="form-control" value="{{ $defaultValues['location_name'] }}" required>
            </div>

            <div class="row mb-2">
                <div class="col-8">
                    <label class="form-label">Address <span class="text-danger">*</span></label>
                    <input id="modal-address" class="form-control" value="{{ $defaultValues['address'] }}" required>
                </div>
                <div class="col-4">
                    <label class="form-label">Unit</label>
                    <input id="modal-unit" class="form-control" value="{{ $defaultValues['unit'] }}">
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3">
                    <label class="form-label">City <span class="text-danger">*</span></label>
                    <input id="modal-city" type="text" class="form-control" value="{{ $defaultValues['city'] }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Postal <span class="text-danger">*</span></label>
                    <input id="modal-postal" type="text" class="form-control" value="{{ $defaultValues['postal_code'] }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Province <span class="text-danger">*</span></label>
                    <select id="modal-province" class="form-select" required>
                        <option value="">Select...</option>
                        <option value="BC" {{ $defaultValues['province'] === 'BC' ? 'selected' : '' }}>BC</option>
                        <option value="AB" {{ $defaultValues['province'] === 'AB' ? 'selected' : '' }}>AB</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Country</label>
                    <input id="modal-country" class="form-control" value="{{ $defaultValues['country'] }}" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-5">
                    <label class="form-label">Pickup or Delivery <span class="text-danger">*</span></label>
                    <select id="modal-pickup-or-delivery" class="form-select" required>
                        <option value="">Select...</option>
                        <option value="pickup" {{ $defaultValues['pickup_delivery'] === 'pickup' ? 'selected' : '' }}>Pick Up</option>
                        <option value="delivery" {{ $defaultValues['pickup_delivery'] === 'delivery' ? 'selected' : '' }}>Delivery</option>
                    </select>
                </div>
                <div class="col-4">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="modal-status">
                        <option value="valid" {{ $defaultValues['status'] === 'valid' ? 'selected' : '' }}>Valid</option>
                        <option value="cancelled" {{ $defaultValues['status'] === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-5">
                    <label class="form-label">Delivery Date<span class="text-danger">*</span></label>
                    <input id="modal-delivery-date" type="date" class="form-control" value="{{ $defaultValues['delivery_date'] }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Delivery Time</label>
                    <input id="modal-delivery-time" type="time" class="form-control" value="{{ $defaultValues['delivery_time'] }}">
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label">Notes</label>
                <textarea id="modal-notes" class="form-control" rows="3">{{ $defaultValues['notes'] }}</textarea>
            </div>
        </div>

        <!-- Cost Summary -->
        <div class="col-md-4">
            <h5><i class="la la-dollar-sign"></i> Cost Summary</h5>
            <div class="p-3 rounded" style="background: rgba(245, 246, 250, 1);">
                <div class="d-flex justify-content-between align-items-center m-1 cost-summary-ice">
                    <p class="m-0">Dry Ice ({{ $defaultValues['ice_amount'] }} lbs @ $1.95/lb):</p>
                    <strong>${{ number_format($defaultValues['ice_amount'] * 1.95, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center m-1 cost-summary-box">
                    <p class="m-0">Styrofoam Box ({{ $defaultValues['box_amount'] }} @ $30.00/box): </p>
                    <strong>${{ number_format($defaultValues['box_amount'] * 30.00, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center m-1 cost-summary-delivery">
                    <p class="m-0">Pickup/Delivery: </p>
                    <strong>${{ number_format($defaultValues['delivery_cost'], 2) }}</strong>
                    <input id="modal-delivery-cost" type="hidden" value="{{ $defaultValues['delivery_cost'] }}">
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center m-1 cost-summary-subtotal">
                    <p class="m-0">Sub-Total: </p>
                    <strong>${{ number_format($defaultValues['sub_total'], 2) }}</strong>
                    <input id="modal-sub-total" type="hidden" value="{{ $defaultValues['sub_total'] }}">
                </div>
                <div class="d-flex justify-content-between align-items-center m-1 cost-summary-tax">
                    <p class="m-0">Tax (15%):  </p>
                    <strong>${{ number_format($defaultValues['tax'], 2) }}</strong>
                    <input id="modal-tax" type="hidden" value="{{ $defaultValues['tax'] }}">
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center m-1 cost-summary-total">
                    <p class="m-0">TOTAL: </p>
                    <strong>${{ number_format($defaultValues['total_cost'], 2) }}</strong>
                    <input id="modal-total-cost" type="hidden" value="{{ $defaultValues['total_cost'] }}">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer border-0 d-flex justify-content-between">
    <div>
        <button id="save-order-btn" class="btn btn-primary" data-mode="{{ $mode }}" @if($mode === 'edit') data-order-id="{{ $order->id }}" @endif>
            <i class="la la-save"></i> <span>{{ $saveButtonText }}</span>
        </button>

        @if($showPushButton && $order)
            @if(!$defaultValues['novex_pushed'])
                <button id="push-btn-{{ $order->id }}" onclick="tryPushOrderToNovex({{ $order->id }})" class="btn btn-primary button-push" style="background: gray">
                    Push Order
                </button>
                <span id="push-status-{{ $order->id }}" class="ml-2 text-sm text-muted status-push"></span>
            @else
                <span id="push-status-{{ $order->id }}" class="ml-2 text-sm text-muted status-push">Order pushed</span>
            @endif
            <span id="push-status-{{ $order->id }}" class="ml-2 text-sm text-muted status-push"></span>
        @endif

        <button class="btn btn-secondary mx-2" data-bs-dismiss="modal">Cancel</button>
    </div>

    @if($showDeleteButton)
        <button id="delete-order-btn" class="btn btn-danger" data-order-id="{{ $order->id }}">
            <i class="la la-trash"></i> Delete
        </button>
    @endif
</div>
