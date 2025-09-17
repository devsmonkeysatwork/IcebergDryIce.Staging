
@extends('website.layouts.main')

@section('content')
    <div class="container">
        <h1 class='info_header'>{{ __('manual_title') }}</h1>
        <table>
            <tr>
                <td width='350px'>{{ __('manual_coldjet100') }}</td>
                <td rowspan='2'><img src='{{asset('website/images/cj100.png')}}'></td>
            </tr><tr><td valign="top">
                    <a href="{{asset('website/downloads/cj100_training.wmv')}}" target='_blank'>{{ __('manual_training') }}</a><br>
                    <a href="{{asset('website/downloads/cj100_parts_05-16-12.pdf')}}" target='_blank'>{{ __('manual_parts') }}</a></td>
            </tr><tr>
                <td>{{ __('manual_coldjet_75') }}</td>
                <td rowspan='2'><img src='{{asset('website/images/cj75.png')}}'></td>
            </tr><tr><td valign="top">
                    <a href='website/downloads/cj75_manual.pdf' target='_blank'>{{ __('manual_manual') }}</a><br>
                    <a href='website/downloads/cj75_training.wmv' target='_blank'>{{ __('manual_training') }}</a><br>
                    <a href='website/downloads/cj75_parts.pdf' target='_blank'>{{ __('manual_parts') }}</a><br>
                    <a href='website/downloads/cj75_parts_applicator.pdf' target='_blank'>{{ __('manual_applicator_parts') }}</a></td>

            </tr>
        </table>
    </div>
@endsection
