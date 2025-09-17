@extends('website.layouts.main')

@section('content')

    <div class="container">
<div class='info'><h1 class='info_header'>{!! __('dryice_safety.title.1') !!}</h1>
    <p>
        {!! __('dryice_safety.body.1') !!}
    </p>
    <i><b>{!! __('dryice_safety.note.1') !!}</b></i>
</div>

<div class='info'><h1 class='info_header'>{!! __('dryice_safety.title.2') !!}</h1>
    <p>
        {!! __('dryice_safety.body.2') !!}
    </p>
    <i><b>{!! __('dryice_safety.note.2') !!}</b></i>
</div>

<div class='info'><h1 class='info_header'>{!! __('dryice_safety.title.3') !!}</h1>
    <p>
        {!! __('dryice_safety.body.3') !!}
    </p>
    <i><b>{!! __('dryice_safety.note.3') !!}</b></i>
</div>
<br>
<br>
<center>{!! __('dryice_safety.contact_line') !!} <a href="mailto:admin@icebergdryice.com?subject=Dry Ice Safety">EMAIL</a> us.</center>

@endsection
