@extends('website.layouts.main')

@section('content')
    <div class="container">

        <div class='info'><h1 class='info_header'>{{ __('blasting_title') }}</h1>
            <p>
                {!! __('blasting_description') !!}
            </p>
        </div>

        <!-------------------------------------------------------------------------------------------------------------------------------->
        <div class='info'><h1 class='info_header'>{!! __('blasting_rental') !!}</h1>

            {!! __('blasting_rental_desc') !!}
        </div>
        <!-------------------------------------------------------------------------------------------------------------------------------->

        <div class='info'><h1 class='info_header'>{!! __('blasting_repairs') !!}</h1>

            {!! __('blasting_repairs_para_1') !!}
            <br><br>
            {!! __('blasting_repairs_para_1') !!}
        </div>
    </div>
@endsection
