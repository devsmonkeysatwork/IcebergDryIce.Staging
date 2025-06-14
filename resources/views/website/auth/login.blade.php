@extends('website.layouts.main')

@section('content')

    <div class="container">
        <div class='info'>
            <h1 class=''>Online Ordering System - Login</h1>

            {{-- Display error messages --}}
            @if(session('ibdi_error'))
                <div class="alert alert-danger">
                    <b>{{ session('ibdi_error') }}</b>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <b>{{ $errors->first() }}</b>
                </div>
            @endif

            <form action="{{ route('login.custom') }}" method="POST">
                @csrf
                <table border="0">
                    <tr>
                        <td width="100px">Email:</td>
                        <td>
                            <input class='textbox_data'
                                   type="email"
                                   id='username'
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus>
                        </td>
                    </tr>
                    <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                    <tr>
                        <td>Password:</td>
                        <td>
                            <input class='textbox_data'
                                   type="password"
                                   name="password"
                                   required>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" align="left">
                            <a href="{{route('customer.register.form')}}" style='width: 100px; text-align: center; color: #090; border: outset 2px #090;' class='is_button'>
                                Sign Up
                            </a>
                        </td>
                        <td colspan="2" align="right">
                            <input class='is_button'
                                   type="submit"
                                   value="Login">
                        </td>
                    </tr>
                </table>
            </form>

            <br><br><br>

            <div class="additional-info">
                <p>
                    You can now buy dry ice for delivery here -
                    <a style='color: #F00; text-decoration: underline;'
                       href="">BUY NOW</a>
                </p>

                <p>
                    Online access is reserved for account holders.
                    To obtain an account, please request a credit application.
                </p>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-focus on username field
            document.getElementById('username').focus();
        });
    </script>
@endpush
