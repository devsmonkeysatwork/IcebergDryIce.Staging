@extends(backpack_view('blank'))

@section('header')
    <section class="container-fluid header">
        <h2 class="title text-capitalize">
            Invoice Generator
        </h2>
        <small>
            <a href="{{ url('admin/dashboard') }}" class="btn btn-manual btn-sm mx-3"><i class="la la-arrow-left mx-2"></i> Back to Dashboard</a>
        </small>
    </section>
@endsection

@section('content')
    <div class="container-fluid">

        {{-- STEP 1: Customer + Date Range Selector --}}
        <div class="table" id="selector-card">
            <div class="table-header">
                Select Customer &amp; Date Range
            </div>
            <div class="p-3">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Customer <span class="text-danger">*</span></label>
                        <select id="gen-customer-id" class="form-select">
                            <option value="">Select customer...</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Start Date <span class="text-danger">*</span></label>
                        <input type="date" id="gen-start-date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date <span class="text-danger">*</span></label>
                        <input type="date" id="gen-end-date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <button id="build-draft-btn" class="btn btn-add w-100">
                            <i class="la la-search mx-1"></i> Build Draft
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- STEP 2: Draft Review / Edit --}}
        <div class="table" id="draft-card" style="display:none;">
            <div class="table-header d-flex justify-content-between align-items-center">
                <span>Draft Invoice</span>
                <span class="text-muted" style="font-size: 14px;" id="draft-range-label"></span>
            </div>

            <div class="p-3">

                <div class="row">
                    <div class="col-md-7">
                        <h5 class="mb-2"><i class="la la-list"></i> Orders Included</h5>
                        <div class="table-responsive-wrapper mb-4">
                            <table id="order-refs-table">
                                <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Reference</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="order-refs-body">
                                </tbody>
                            </table>
                        </div>

                        <h5 class="mb-2"><i class="la la-cubes"></i> Line Items</h5>
                        <div class="table-responsive-wrapper mb-4">
                            <table id="line-items-table">
                                <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody id="line-items-body">
                                </tbody>
                            </table>
                        </div>

                        <h5 class="mb-2"><i class="la la-plus-circle"></i> Add Flat Charge / Discount</h5>
                        <div class="row g-2 align-items-end mb-3">
                            <div class="col-md-3">
                                <select id="new-charge-key" class="form-select form-select-sm">
                                    <option value="custom">Custom Charge</option>
                                    <option value="discount">Discount</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="new-charge-label" class="form-control form-control-sm" placeholder="Label">
                            </div>
                            <div class="col-md-3">
                                <input type="number" id="new-charge-amount" class="form-control form-control-sm" placeholder="Amount (use - for discount)" step="0.01">
                            </div>
                            <div class="col-md-2">
                                <button id="add-charge-btn" class="btn btn-add btn-sm w-100">Add</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <h5 class="mb-2"><i class="la la-dollar-sign"></i> Cost Summary</h5>
                        <div class="p-3 rounded" style="background: rgba(245, 246, 250, 1);">
                            <div id="flat-charges-summary"></div>
                            <hr>
                            <div class="m-1">
                                <p class="m-0 d-flex justify-content-between align-items-center">
                                    Sub-Total: <strong id="summary-subtotal">$0.00</strong>
                                </p>
                            </div>
                            <div class="m-1">
                                <p class="m-0 d-flex justify-content-between align-items-center">
                                    Flat Charges: <strong id="summary-flattotal">$0.00</strong>
                                </p>
                            </div>
                            <hr>
                            <div class="m-1">
                                <p class="d-flex justify-content-between align-items-center m-0">
                                    <strong>TOTAL:</strong> <strong id="summary-total">$0.00</strong>
                                </p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="invoice-notes" class="form-label" style="font-size:14px;font-weight:600;">Invoice Notes</label>
                            <textarea id="invoice-notes" class="form-control" rows="3"
                                placeholder="Optional notes"></textarea>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button id="finalize-btn" class="btn btn-primary flex-grow-1">
                                <i class="la la-check-circle"></i> Confirm &amp; Generate Invoice
                            </button>
                            <button id="cancel-draft-btn" class="btn btn-secondary">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- STEP 3: Result --}}
        <div class="table" id="result-card" style="display:none;">
            <div class="table-header">Invoice Generated</div>
            <div class="p-4 text-center">
                <i class="la la-check-circle text-success" style="font-size: 60px;"></i>
                <h4 class="mt-3">Invoice <span id="result-invoice-number"></span> created successfully</h4>
                <a id="result-pdf-link" href="#" target="_blank" class="btn btn-primary mt-3">
                    <i class="la la-file-pdf"></i> Download PDF
                </a>
                <button id="start-new-btn" class="btn btn-secondary mt-3 mx-2">Start New Invoice</button>
            </div>
        </div>

    </div>
@endsection

@section('after_styles')
    @vite(['resources/scss/app.scss', 'resources/css/custom.css'])

    <style>
        .container-fluid h2.title {
            font-weight: 900;
            font-size: 36px;
            letter-spacing: -0.11px;
            margin: 0px;
        }

        .container-fluid.header {
            display: flex;
            justify-content: space-between;
            padding: 0 24px 20px;
            align-items: center;
        }

        .btn-manual {
            background: rgba(232, 232, 232, 1);
            border: 1px solid black;
            letter-spacing: -0.11px;
            color: black;
            border-radius: 10px;
            box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
        }

        .btn-add {
            background-color: var(--tblr-primary);
            border: 1px solid var(--tblr-primary);
            color: var(--tblr-light);
            border-radius: 10px;
            box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
        }

        .btn-add:hover {
            background-color: #0246a5;
            color: var(--tblr-light);
        }

        .table {
            margin-bottom: 30px;
            border-radius: 8px;
            background: white;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            color: #221e26;
            padding: 16px 20px;
            font-weight: 700;
            font-size: 22px;
            line-height: 1.4;
            letter-spacing: -0.11px;
            border-bottom: 1px solid #f1f3f4;
        }

        .table-responsive-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #order-refs-table, #line-items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        #order-refs-table thead, #line-items-table thead {
            background-color: #221e26;
        }

        #order-refs-table thead th, #line-items-table thead th {
            color: white;
            font-weight: 500;
            font-size: 13px;
            padding: 10px 12px;
            border: none;
            text-align: left;
        }

        #order-refs-table tbody tr, #line-items-table tbody tr {
            background-color: white;
            border-bottom: 1px solid #f1f3f4;
        }

        #order-refs-table tbody td, #line-items-table tbody td {
            padding: 8px 12px;
            font-size: 13px;
            vertical-align: middle;
        }

        .remove-ref-btn, .remove-charge-btn {
            background: none;
            border: none;
            color: #d33;
            cursor: pointer;
            font-size: 16px;
        }

        .badge-order-type {
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-onetime { background: #e7f1ff; color: #0256c5; }
        .badge-recurring { background: #fff3cd; color: #997404; }

        .line-item-input {
            width: 90px;
            padding: 2px 6px;
            font-size: 13px;
        }

        footer { display: none; }
    </style>
@endsection

@push('after_scripts')
    @vite(['resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            let currentDraft = null;

            const selectorCard = document.getElementById('selector-card');
            const draftCard     = document.getElementById('draft-card');
            const resultCard    = document.getElementById('result-card');

            function fmt(n) {
                return '$' + Number(n).toFixed(2);
            }

            function renderDraft(draft) {
                currentDraft = draft;

                document.getElementById('draft-range-label').textContent =
                    draft.start_date + ' to ' + draft.end_date;

                // Order refs
                const refsBody = document.getElementById('order-refs-body');
                refsBody.innerHTML = '';
                draft.order_refs.forEach((ref, idx) => {
                    const badgeClass = ref.order_type === 'recurring' ? 'badge-recurring' : 'badge-onetime';
                    refsBody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td><span class="badge-order-type ${badgeClass}">${ref.order_type}</span></td>
                    <td>${ref.label}</td>
                    <td>${ref.delivery_date ?? ''}</td>
                    <td>
                        <button class="remove-ref-btn" data-type="${ref.invoiceable_type}" data-id="${ref.invoiceable_id}" title="Remove from invoice">
                            <i class="la la-times-circle"></i>
                        </button>
                    </td>
                </tr>
            `);
                });

                // Line items
                const liBody = document.getElementById('line-items-body');
                liBody.innerHTML = '';
                draft.line_items.forEach((li, idx) => {
                    liBody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td>${li.description}</td>
                    <td><input type="number" class="form-control form-control-sm line-item-input li-qty" data-index="${idx}" value="${li.quantity}" min="0"></td>
                    <td><input type="number" class="form-control form-control-sm line-item-input li-price" data-index="${idx}" value="${li.unit_price}" step="0.01" min="0"></td>
                    <td>${fmt(li.total_price)}</td>
                </tr>
            `);
                });

                // Flat charges
                const flatSummary = document.getElementById('flat-charges-summary');
                flatSummary.innerHTML = '';
                draft.flat_charges.forEach((fc, idx) => {
                    const label = fc.label || fc.charge_key.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                    flatSummary.insertAdjacentHTML('beforeend', `
                <p class="m-0 d-flex justify-content-between align-items-center">
                    ${label}
                    <span>
                        <strong class="me-2">${fc.amount < 0 ? '-' : ''}${fmt(Math.abs(fc.amount))}</strong>
                        <button class="remove-charge-btn" data-index="${idx}" title="Remove charge"><i class="la la-times-circle"></i></button>
                    </span>
                </p>
            `);
                });

                document.getElementById('summary-subtotal').textContent  = fmt(draft.totals.sub_total);
                document.getElementById('summary-flattotal').textContent = fmt(draft.totals.flat_total);
                document.getElementById('summary-total').textContent     = fmt(draft.totals.total);

                selectorCard.style.display = 'none';
                draftCard.style.display = 'block';
                resultCard.style.display = 'none';
            }

            function postJson(url, payload) {
                return fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                }).then(res => res.json());
            }

            document.getElementById('build-draft-btn').addEventListener('click', function () {
                const customerId = document.getElementById('gen-customer-id').value;
                const startDate  = document.getElementById('gen-start-date').value;
                const endDate    = document.getElementById('gen-end-date').value;

                if (!customerId || !startDate || !endDate) {
                    Swal.fire('Missing Information', 'Please select a customer and date range.', 'warning');
                    return;
                }

                postJson('{{ route('admin.invoice-generator.draft') }}', {
                    customer_id: customerId,
                    start_date: startDate,
                    end_date: endDate
                }).then(data => {
                    if (data.success) {
                        renderDraft(data.draft);
                        if (data.using_default_pricing) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Default Pricing Applied',
                                text: 'No custom pricing found for this customer. Product default prices have been used. You can adjust line items manually before finalizing.',
                                confirmButtonText: 'OK'
                            });
                        }
                    } else {
                        Swal.fire('No Orders Found', data.message, 'info');
                    }
                });
            });

            document.getElementById('order-refs-body').addEventListener('click', function (e) {
                const btn = e.target.closest('.remove-ref-btn');
                if (!btn) return;

                postJson('{{ route('admin.invoice-generator.draft.update') }}', {
                    action: 'remove_order_ref',
                    invoiceable_type: btn.dataset.type,
                    invoiceable_id: btn.dataset.id
                }).then(data => {
                    if (data.success) renderDraft(data.draft);
                });
            });

            document.getElementById('add-charge-btn').addEventListener('click', function () {
                const chargeKey = document.getElementById('new-charge-key').value;
                const label     = document.getElementById('new-charge-label').value;
                const amount    = document.getElementById('new-charge-amount').value;

                if (!amount) {
                    Swal.fire('Missing Amount', 'Please enter an amount.', 'warning');
                    return;
                }

                postJson('{{ route('admin.invoice-generator.draft.update') }}', {
                    action: 'add_flat_charge',
                    charge_key: chargeKey,
                    label: label,
                    amount: amount
                }).then(data => {
                    if (data.success) {
                        renderDraft(data.draft);
                        document.getElementById('new-charge-label').value = '';
                        document.getElementById('new-charge-amount').value = '';
                    }
                });
            });

            document.getElementById('flat-charges-summary').addEventListener('click', function (e) {
                const btn = e.target.closest('.remove-charge-btn');
                if (!btn) return;

                postJson('{{ route('admin.invoice-generator.draft.update') }}', {
                    action: 'remove_flat_charge',
                    index: btn.dataset.index
                }).then(data => {
                    if (data.success) renderDraft(data.draft);
                });
            });

            document.getElementById('line-items-body').addEventListener('change', function (e) {
                const input = e.target;
                if (!input.classList.contains('li-qty') && !input.classList.contains('li-price')) return;

                const index = input.dataset.index;
                const payload = { action: 'update_line_item', index: index };

                if (input.classList.contains('li-qty')) payload.quantity = input.value;
                if (input.classList.contains('li-price')) payload.unit_price = input.value;

                postJson('{{ route('admin.invoice-generator.draft.update') }}', payload).then(data => {
                    if (data.success) renderDraft(data.draft);
                });
            });

            document.getElementById('cancel-draft-btn').addEventListener('click', function () {
                Swal.fire({
                    title: 'Discard this draft?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, discard'
                }).then(result => {
                    if (result.isConfirmed) {
                        draftCard.style.display = 'none';
                        selectorCard.style.display = 'block';
                    }
                });
            });

            document.getElementById('finalize-btn').addEventListener('click', function () {
                Swal.fire({
                    title: 'Generate this invoice?',
                    html: `Total amount: <strong>${document.getElementById('summary-total').textContent}</strong>`,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, generate'
                }).then(result => {
                    if (!result.isConfirmed) return;

                    postJson('{{ route('admin.invoice-generator.finalize') }}', {
                        notes: document.getElementById('invoice-notes').value
                    }).then(data => {
                        if (data.success) {
                            document.getElementById('result-invoice-number').textContent = '#' + data.invoice_id;
                            document.getElementById('result-pdf-link').href = data.pdf_url;
                            draftCard.style.display = 'none';
                            resultCard.style.display = 'block';
                        } else {
                            Swal.fire('Error', data.message || 'Failed to generate invoice.', 'error');
                        }
                    });
                });
            });

            document.getElementById('start-new-btn').addEventListener('click', function () {
                document.getElementById('gen-customer-id').value = '';
                document.getElementById('gen-start-date').value = '';
                document.getElementById('gen-end-date').value = '';
                resultCard.style.display = 'none';
                selectorCard.style.display = 'block';
            });

        });
    </script>
@endpush
