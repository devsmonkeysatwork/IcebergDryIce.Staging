
@extends('website.layouts.main')

@section('content')

    <div class="container">
        <div class='info'>
            <h1 class=''>Password Changed Successfully</h1>
            <div class="alert alert-success" style="margin-bottom: 50px">
                Your password has been updated. <a class="is_button" href="{{ route('login.form') }}">login</a>
            </div>
        </div>
    </div>
@endsection
