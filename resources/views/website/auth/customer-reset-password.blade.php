@extends('website.layouts.main')

@section('content')

    <div class="container">
        <div class='info'>
            <h1 class=''>Reset Password</h1>

            {{-- Success message --}}
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Validation error messages --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 1rem; color: red">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Password Reset Form --}}
            <form action="{{ route('customer.password.reset') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <table border="0">
                    <tr>
                        <td width="130px">Email:</td>
                        <td>
                            <input class='textbox_data'
                                   type="email"
                                   name="email"
                                   value="{{ old('email', $email ?? '') }}"
                                   required
                                   autofocus>
                        </td>
                    </tr>

                    <tr>
                        <td width="130px">Password:</td>
                        <td>
                            <input class='textbox_data'
                                   type="password"
                                   name="password"
                                   required>
                        </td>
                    </tr>

                    <tr>
                        <td width="130px">Confirm Password:</td>
                        <td>
                            <input class='textbox_data'
                                   type="password"
                                   name="password_confirmation"
                                   required>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" align="left">
                            <input class='is_button'
                                   type="submit"
                                   value="Reset Password">
                        </td>
                    </tr>
                </table>
            </form>

            <br><br><br>
        </div>
    </div>

@endsection
