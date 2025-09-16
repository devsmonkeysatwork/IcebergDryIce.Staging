@extends(backpack_view('blank'))

@section('content')
    <div class="container">
        <h1>Manual Payment</h1>
        <div class="row my-5">
            <div class="col-12 col-lg-9">
                <form id="payment-form" action="{{ route('manual-payments.store') }}" method="POST" class="card p-5">
                    @csrf
                    <div class="row">
                        <input type="hidden" id="invoice-id" name="invoice_id" value="">
                        <input type="hidden" id="payment-method" name="payment_method" value="other">

                        <!-- Exact Payment Hidden Fields (populated when credit card is selected) -->
                        <input type="hidden" id="x_login" name="x_login" value="">
                        <input type="hidden" id="x_fp_sequence" name="x_fp_sequence" value="">
                        <input type="hidden" id="x_fp_timestamp" name="x_fp_timestamp" value="">
                        <input type="hidden" id="x_fp_hash" name="x_fp_hash" value="">
                        <input type="hidden" id="x_currency_code" name="x_currency_code" value="CAD">
                        <input type="hidden" id="x_show_form" name="x_show_form" value="PAYMENT_FORM">
                        <input type="hidden" id="x_test_request" name="x_test_request" value="FALSE">
                        <input type="hidden" id="x_po_num" name="x_po_num" value="3">
                        <input type="hidden" id="x_invoice_num" name="x_invoice_num" value="">

                        <div class="col-8 px-4 my-3">
                            <h3 class="form-group-heading m-0"><i class="la la-cart-plus me-2"></i> Order</h3>
                            <div class="form-group">
                                <label for="invoice-number">Invoice #</label>
                                <select id="invoice-number" class="form-control" style="width: 100%" required></select>
                            </div>
                        </div>
                        <div class="col-6 px-4">
                            <h3 class="form-group-heading m-0"><i class="la la-user-circle me-2"></i> Contact</h3>
                            <div class="form-group">
                                <label for="contact-name">Name</label>
                                <input type="text" class="form-control" id="contact-name" name="contact_name" placeholder="Contact Name" required readonly>
                                <input type="hidden" id="x_first_name" name="x_first_name" value="">
                            </div>
                            <div class="form-group">
                                <label for="contact-email">Email</label>
                                <input type="email" class="form-control" id="contact-email" name="email" placeholder="Email" required readonly>
                                <input type="hidden" id="x_email" name="x_email" value="">
                            </div>

                        </div>

                        <div class="col-6 px-4">
                            <h3 class="form-group-heading m-0"><i class="la la-credit-card me-2"></i> Payment</h3>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" id="description" name="description" placeholder="Description">
                            </div>
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount - example 15.75" required readonly>
                                <input type="hidden" id="x_amount" name="x_amount" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group px-3 d-flex justify-content-between">
                        <div class="mt-2">
                            <button type="button" class="btn btn-credit btn-submission" id="credit-btn">
                                <i class="la la-credit-card me-2"></i>Credit Card
                            </button>
                            <button type="submit" class="btn btn-primary btn-submission" id="other-btn">Others</button>
                        </div>
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
    .btn-credit {
        background-color: #00aa00;
        color: white;
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
            // Existing select2 code...
            $('#invoice-number').select2({
                theme: 'bootstrap-5',
                placeholder: 'Enter Invoice No',
                ajax: {
                    url: '{{ route('invoices.ajax-search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { q: params.term };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(item => ({
                                id: item.id,
                                text: `#${item.invoice_number} - ${item.customer_name}`,
                                customer_name: item.customer_name,
                                customer_email: item.email,
                                total_cost: item.total_cost,
                                invoice_num: item.invoice_number
                            }))
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            });

            $('#invoice-number').on('select2:select', function(e) {
                const data = e.params.data;

                // Populate visible fields
                $('#contact-name').val(data.customer_name);
                $('#contact-email').val(data.customer_email);
                $('#amount').val(data.total_cost);
                $('#invoice-id').val(data.invoice_num);
                $('#origin-address').val(data.origin_address);
                $('#destination-address').val(data.destination_address);

                // Populate Exact hidden fields
                $('#x_first_name').val(data.customer_name);
                $('#x_invoice_num').val(data.id);
                $('#x_email').val(data.customer_email);
                $('#x_amount').val(data.total_cost);
            });

            // Credit card button handler
            $('#credit-btn').click(function(e) {
                e.preventDefault();

                if (!validateForm()) {
                    alert('Please fill in all required fields');
                    return;
                }

                const invoiceNumber = $('#invoice-number option:selected').text();
                const amount = $('#amount').val();

                Swal.fire({
                    title: 'Credit Card Payment',
                    html: `Do you want to pay <strong>${amount}</strong> for ${invoiceNumber} through credit card?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#00aa00',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Pay Now',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Set payment method
                        $('#payment-method').val('credit');

                        // Generate Exact security fields
                        generateExactFields().then(() => {
                            // Change form action to Exact
                            $('#payment-form').attr('action', 'https://rpm.demo.e-xact.com/payment');
                            $('#payment-form').submit();
                        });
                    }
                });
            });

            // Other payment button handler
            $('#other-btn').click(function(e) {
                e.preventDefault();

                if (!validateForm()) {
                    alert('Please fill in all required fields');
                    return;
                }

                const invoiceNumber = $('#invoice-number option:selected').text();
                const amount = $('#amount').val();

                Swal.fire({
                    title: 'Payment Confirmation',
                    html: `Have you received <strong>${amount}</strong> payment for ${invoiceNumber} through cash, check or any other source?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Payment Received',
                    cancelButtonText: 'No, Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#payment-method').val('other');
                        $('#payment-form').attr('action', '{{ route('manual-payments.store') }}');
                        $('#payment-form').submit();
                    }
                });
            });

            function validateForm() {
                return $('#contact-name').val() &&
                    $('#contact-email').val() &&
                    $('#amount').val() &&
                    $('#invoice-id').val();
            }

            async function generateExactFields() {
                try {
                    const response = await fetch('{{ route('generate-exact-fields') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({
                            amount: $('#x_amount').val()
                        })
                    });

                    const data = await response.json();

                    $('#x_login').val(data.x_login);
                    $('#x_fp_sequence').val(data.x_fp_sequence);
                    $('#x_fp_timestamp').val(data.x_fp_timestamp);
                    $('#x_fp_hash').val(data.x_fp_hash);
                } catch (error) {
                    console.error('Error generating Exact fields:', error);
                }
            }
        });
    </script>
@endpush
