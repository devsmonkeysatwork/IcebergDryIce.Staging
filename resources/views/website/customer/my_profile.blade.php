@extends(backpack_view('blank'))


@section('title', 'My Profile')
 {{-- Adjust based on your website layout --}}



    @section('content')
        <div class="container ">
            <div class="row" bp-section="crud-operation-create">
                <div class="col-12">
                    {{-- Page Header --}}
                    <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none" bp-section="page-header">
                        <h1 class="text-capitalize mb-0" bp-section="page-heading">My Profile</h1>
                        <p class="ms-2 ml-2 mb-0" bp-section="page-subheading">Manage your account information.</p>
                    </section>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    {{-- Success Messages --}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Profile Information Form --}}
                    <form method="post" class="card" action="{{ route('customer.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12 px-4">
                                <h3 class="form-group-heading m-0 mb-4">
                                    <i class="la la-user me-2"></i> Personal Information
                                </h3>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               value="{{ old('name', $customer->name ?? '') }}" placeholder="Name" required>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="{{ old('email', $customer->email ?? '') }}" placeholder="Email" readonly required>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                               value="{{ old('phone', $customer->phone ?? '') }}" placeholder="Phone" required>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control" id="address" name="address"
                                               value="{{ old('address', $customer->address ?? '') }}" placeholder="Address" required>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" id="city" name="city"
                                               value="{{ old('city', $customer->city ?? '') }}" placeholder="City" required>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="postal_code">Postal Code</label>
                                        <input type="text" class="form-control" id="postal_code" name="postal_code"
                                               value="{{ old('postal_code', $customer->postal_code ?? '') }}" placeholder="Postal Code" required>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="province">Province</label>
                                        <select class="form-control" id="province" name="province" required>
                                            <option value="">-- Select Province --</option>
                                            <option value="BC" {{ old('province', $customer->province ?? '') == 'BC' ? 'selected' : '' }}>BC</option>
                                            <option value="AB" {{ old('province', $customer->province ?? '') == 'AB' ? 'selected' : '' }}>AB</option>
                                        </select>
                                    </div>

                                    <div class="form-group d-none col-md-4">
                                        <label for="country">Country</label>
                                        <input type="text" class="form-control" id="country" name="country"
                                               value="Canada" readonly>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button class="btn-primary btn-submission btn" type="submit">
                                        <i class="la la-save me-2"></i> Update Profile
                                    </button>
                                    <a href="{{ route('customer.orders') }}" class="btn btn-secondary btn-submission mx-2">
                                        <i class="la la-arrow-left me-2"></i> Back to Orders
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Change Password Form --}}
                    <form method="post" class="card" action="{{ route('customer.password.change') }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12 px-4">
                                <h3 class="form-group-heading m-0 mb-4">
                                    <i class="la la-lock me-2"></i> Change Password
                                </h3>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="current_password">Current Password</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password"
                                               placeholder="Enter current password" required>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="new_password">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password"
                                               placeholder="Enter new password" required>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="new_password_confirmation">Confirm New Password</label>
                                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation"
                                               placeholder="Confirm new password" required>
                                    </div>

                                    <div class="col-md-12">
                                        <small class="text-muted">
                                            <i class="la la-info-circle me-1"></i>
                                            Password must be at least 8 characters long and contain a mix of letters, numbers, and special characters.
                                        </small>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button class="btn-primary btn-submission btn" type="submit">
                                        <i class="la la-key me-2"></i> Change Password
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-submission mx-2" onclick="clearPasswordForm()">
                                        <i class="la la-refresh me-2"></i> Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <style>
            h1 {
                font-weight: 800;
                font-size: 32px;
                line-height: 42px;
                letter-spacing: -0.11px;
            }

            form.card {
                padding: 25px;
                background: white;
                border-radius: 20px;
                margin-top: 15px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            form.card > .card {
                border: none;
            }

            form.card > .card > .card-body {
                padding: 0px;
            }

            h3.form-group-heading {
                font-weight: 800;
                font-size: 24px;
                line-height: 36px;
                letter-spacing: -0.11px;
                color: #333;
            }

            .form-control {
                border-radius: 10px !important;
                border: 1px solid #e3e6f0;
                padding: 10px 15px;
                font-size: 14px;
                transition: border-color 0.3s ease;
            }

            .form-control:focus {
                border-color: #007bff;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
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

            .btn-submission:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }

            .btn-primary {
                background-color: #007bff;
                border-color: #007bff;
            }

            .btn-secondary {
                background-color: #6c757d;
                border-color: #6c757d;
            }

            .alert {
                border-radius: 10px;
                border: none;
                padding: 15px 20px;
                margin-bottom: 20px;
            }

            .alert-success {
                background-color: #d4edda;
                color: #155724;
            }

            .alert-danger {
                background-color: #f8d7da;
                color: #721c24;
            }

            .form-group label {
                font-weight: 600;
                color: #333;
                margin-bottom: 5px;
            }

            .header-operation {
                padding: 20px 0;
                border-bottom: 1px solid #e3e6f0;
                margin-bottom: 20px;
            }

            .container {
                max-width: 1200px;
            }

            @media (max-width: 768px) {
                .form-group {
                    margin-bottom: 15px;
                }

                .btn-submission {
                    width: 100%;
                    margin-bottom: 10px;
                }

                .float-end {
                    float: none !important;
                }
            }
        </style>

    @endsection

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            function clearPasswordForm() {
                document.getElementById('current_password').value = '';
                document.getElementById('new_password').value = '';
                document.getElementById('new_password_confirmation').value = '';
            }

            // Show success message with SweetAlert if there's a success session
            @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#007bff',
                timer: 3000,
                timerProgressBar: true
            });
            @endif

            // Password strength indicator
            document.getElementById('new_password').addEventListener('input', function() {
                const password = this.value;
                const strength = calculatePasswordStrength(password);

                // You can add a password strength indicator here
                console.log('Password strength:', strength);
            });

            function calculatePasswordStrength(password) {
                let strength = 0;

                if (password.length >= 8) strength++;
                if (password.match(/[a-z]/)) strength++;
                if (password.match(/[A-Z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[^a-zA-Z0-9]/)) strength++;

                return strength;
            }
        </script>

@endsection
