@extends('website.layouts.main')

@section('content')

    <!-- -->
    <img class="border" align="right" style="margin: 0px 0px 0px 20px;" src="{{asset('website/images/chris_tyler.jpg')}}">
    <p>
        {!! __('home_company_intro') !!}
        {{ __('home_delivery_description') }}
        {{ __('home_distribution_info') }}
    </p>
    <p>
        {!! __('home_experience_description') !!}
        {!! __('home_applications_description') !!}
    </p>
@endsection
