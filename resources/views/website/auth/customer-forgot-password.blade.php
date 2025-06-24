@extends('website.layouts.main')

@section('content')

    <div class="container">
        <div class='info'>
            <h1 class=''>Forget Password</h1>

            {{-- Display success or error messages --}}
            @if (session('status'))
                <table class="mt-2">
                    <tr>
                        <td colspan="2">
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        </td>
                    </tr>
                </table>
            @endif

            @if ($errors->any())
                <table class="mt-2">
                    <tr>
                        <td colspan="2">
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        </td>
                    </tr>
                </table>
            @endif

            {{-- Password reset form --}}
            <form action="{{ route('customer.password.email') }}" method="POST">
                @csrf
                <table border="0">
                    <tr>
                        <td width="100px">Email:</td>
                        <td>
                            <input class='textbox_data'
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="left">
                            <input class='is_button'
                                   type="submit"
                                   value="{{ session('status') ? 'Resend Reset Link' : 'Send Reset Link' }}">
                        </td>
                    </tr>
                </table>
            </form>

            <br><br>
        </div>
    </div>

@endsection
