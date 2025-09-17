@extends('website.layouts.main')

@section('content')

    <div class="container">
        <div class='info'><h1 class='info_header'>{!! __('contact_info') !!}</h1>
            <table>
                <tr><th width="200px">{!! __('phone') !!}</th>	<td>(604) 524-0609</td></tr>
                <tr><th>{!! __('email') !!}</th>	<td><a href="mailto:admin@icebergdryice.com?subject=Web">admin@icebergdryice.com</a></td></tr>
                <tr><td><br></td></tr>
                <tr><th>{!! __('hours') !!} ({!! __('phone') !!})</th>	<td>9:00am - 4:00pm</td></tr>
            </table>
        </div>
    </div>




@endsection
