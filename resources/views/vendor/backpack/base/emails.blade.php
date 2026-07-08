@extends(backpack_view('blank'))

@section('content')
    <!-- Include SweetAlert2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.32/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.32/sweetalert2.min.css">

    <div class="container">
        <h1 class="text-capitalize mb-0" bp-section="page-heading">Emails</h1>
        <div class="row">
            <div class="col-md-10">
                <div class="card mt-3 p-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <h3 class="form-group-heading"><i class="la la-envelope me-2"></i>Templates</h3>
                                <input type="date" class="date-input-field" id="date-input" onchange="updateEmailContent()">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="btn-group-vertical">
                                <button class="btn btn-secondary email-temp-btn" data-location="Kuehne & Nagel" data-email="admin@icebergdryice.com" onclick="selectLocation(this)">Kuehne & Nagel</button>
                                <button class="btn btn-secondary email-temp-btn" data-location="Praxair" data-email="vancouver@linde.com" onclick="selectLocation(this)">Praxair</button>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <form id="email-form" onsubmit="sendEmail(event)">
                                <div class="form-group">
                                    <label for="email-to">To</label>
                                    <input type="email" class="form-control" id="email-to" value="" placeholder="Select a location first" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="email-subject">Subject</label>
                                    <input type="text" class="form-control" id="email-subject" value="Dry Ice Orders - Please Select Date and Location" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="email-body">Body</label>
                                    <textarea class="form-control" id="email-body" rows="8" readonly>Please select a location and date to view orders.</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-submission float-end" id="send-btn" disabled>Send</button>
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
            border: 1px solid rgba(158, 158, 158, 1);
            transition: all 0.3s ease;
        }

        .email-temp-btn:hover {
            color: white;
            background: rgba(69, 75, 90, 1);
        }

        .email-temp-btn.active {
            color: white !important;
            background: rgba(69, 75, 90, 1) !important;
            border-color: rgba(69, 75, 90, 1) !important;
        }

        .date-input-field {
            width: 120px;
            height: 35px;
            font-weight: 500;
            font-size: 12px;
            line-height: 14.06px;
            letter-spacing: 0px;
            border: 1px solid rgba(158, 158, 158, 1);
            border-radius: 7px;
            padding: 5px 10px;
        }

        .btn-submission {
            font-weight: 600;
            font-size: 16px;
            line-height: 20.8px;
            letter-spacing: 0px;
            text-align: center;
            border-radius: 25px;
            padding: 8px 35px;
            transition: all 0.3s ease;
        }

        .btn-submission:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        footer {
            display: none;
        }

        /* Custom SweetAlert styling */
        .swal2-popup {
            border-radius: 15px;
        }

        .swal2-confirm {
            border-radius: 25px !important;
            padding: 8px 35px !important;
        }

        .swal2-cancel {
            border-radius: 25px !important;
            padding: 8px 35px !important;
        }
    </style>

    <script>
        let selectedLocation = null;
        let selectedDate = null;
        let selectedEmail = null;

        const locationEmails = {
            'Kuehne & Nagel': 'admin@icebergdryice.com',
            'Praxair': 'vancouver@linde.com'
        };

        function selectLocation(button) {
            document.querySelectorAll('.email-temp-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Add active class to selected button
            button.classList.add('active');
            selectedLocation = button.dataset.location;
            selectedEmail = button.dataset.email;

            // Update email field
            document.getElementById('email-to').value = selectedEmail;

            // Update email content
            updateEmailContent();
        }

        function updateEmailContent() {
            const dateInput = document.getElementById('date-input');
            const subjectInput = document.getElementById('email-subject');
            const bodyTextarea = document.getElementById('email-body');
            const sendBtn = document.getElementById('send-btn');

            selectedDate = dateInput.value;

            if (selectedLocation && selectedDate) {
                // Format date to full format (Month DD, YYYY)
                const date = new Date(selectedDate);
                const fullDate = date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                // Update subject with complete date
                subjectInput.value = `Dry Ice Orders - ${fullDate}`;

                // Fetch and display orders from database
                fetchOrdersFromDatabase(selectedLocation, selectedDate);

                // Enable send button
                sendBtn.disabled = false;
            } else {
                subjectInput.value = "Dry Ice Orders - Please Select Date and Location";
                bodyTextarea.value = "Please select a location and date to view orders.";
                sendBtn.disabled = true;
            }
        }

        async function fetchOrdersFromDatabase(location, date) {
            const bodyTextarea = document.getElementById('email-body');

            // Show loading state
            bodyTextarea.value = "Loading orders...";

            try {
                // Fetch orders from database where delivery_date matches selected date
                const response = await fetch('/admin/orders_of_date', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        location: location,
                        delivery_date: date
                    })
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch orders');
                }

                const orders = await response.json();

                if (orders.length > 0) {
                    // Format orders for email body
                    let ordersList = `Dear Team,\n\nPlease find below the dry ice orders for ${location} scheduled on ${new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}:\n\n`;

                    ordersList += orders.map((order, index) => {
                        const productsList = order.items.map(item =>
                            `   - ${item.product.product_name}: ${item.amount_of_items} ${item.product.unit}`
                        ).join('\n');
                        return `${index + 1}. Customer: ${order.customer_name}\n${productsList}\n   Contact: ${order.phone || 'N/A'}\n`;
                    }).join('\n');

                    ordersList += `\nTotal Orders: ${orders.length}\n\nBest regards,\nDry Ice Management System`;

                    bodyTextarea.value = ordersList;
                } else {
                    bodyTextarea.value = `Dear Team,\n\nNo orders found for ${location} on ${new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}.\n\nBest regards,\nDry Ice Management System`;
                }
            } catch (error) {
                bodyTextarea.value = `Error fetching orders: ${error.message}`;
                console.error('Error fetching orders:', error);

                // Show error alert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch orders from database. Please try again.',
                    confirmButtonColor: '#dc3545'
                });
            }
        }

        function sendEmail(event) {
            event.preventDefault();

            if (!selectedLocation || !selectedDate || !selectedEmail) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please select both location and date before sending.',
                    confirmButtonColor: '#ffc107'
                });
                return;
            }

            // Show confirmation with SweetAlert
            const fullDate = new Date(selectedDate).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            Swal.fire({
                title: 'Confirm Email Send',
                html: `
            <div style="text-align: left; margin: 20px 0;">
                <p>Are you sure you want to send the email?</p>
                <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #007bff;">
                    <strong>To:</strong> ${selectedEmail}<br>
                    <strong>Location:</strong> ${selectedLocation}<br>
                    <strong>Subject:</strong> ${document.getElementById('email-subject').value}<br>
                    <strong>Date:</strong> ${fullDate}
                </div>
            </div>
        `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Send Email',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    confirmSend();
                }
            });
        }

        async function confirmSend() {
            // Show loading alert
            Swal.fire({
                title: 'Sending Email...',
                text: 'Please wait while we send your email.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                // Prepare email data
                const emailData = {
                    location: selectedLocation,
                    to: selectedEmail,
                    subject: document.getElementById('email-subject').value,
                    body: document.getElementById('email-body').value,
                    delivery_date: selectedDate
                };

                // Send email via backend
                const response = await fetch('/admin/send-email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(emailData)
                });

                if (!response.ok) {
                    throw new Error('Failed to send email');
                }

                const result = await response.json();

                // Show success alert
                Swal.fire({
                    icon: 'success',
                    title: 'Email Sent Successfully!',
                    text: `Email has been sent successfully to ${selectedLocation} (${selectedEmail})`,
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    // Reset form after success
                    resetForm();
                });

            } catch (error) {
                console.error('Error sending email:', error);

                // Show error alert
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Send Email',
                    text: `Error: ${error.message}`,
                    confirmButtonColor: '#dc3545'
                });
            }
        }

        function resetForm() {
            selectedLocation = null;
            selectedDate = null;
            selectedEmail = null;

            // Reset UI
            document.querySelectorAll('.email-temp-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            document.getElementById('date-input').value = '';
            document.getElementById('email-to').value = '';
            document.getElementById('email-to').placeholder = 'Select a location first';
            document.getElementById('email-subject').value = 'Dry Ice Orders - Please Select Date and Location';
            document.getElementById('email-body').value = 'Please select a location and date to view orders.';
            document.getElementById('send-btn').disabled = true;
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Set today's date as default
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date-input').value = today;
            selectedDate = today;
        });
    </script>
@endsection
