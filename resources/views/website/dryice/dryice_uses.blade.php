<!-- resources/views/dry-ice-information.blade.php -->

@extends('website.layouts.main')
<style>
    .row {
        display: flex;
    }
    .col-md-6 {
        width: 50%;
    }
</style>

@section('content')
    <div class="container">
        <!-- SMOKE EFFECTS -->
        <div class='info'>
            <h1 class='info_header'>{{ __('dryice_uses.smoke_effects.title') }}</h1>

            <p>
                {{ __('dryice_uses.smoke_effects.intro') }}
            </p>
            <p>
                {{ __('dryice_uses.smoke_effects.tips') }}
            </p>

            <div id="t1">{{ __('dryice_uses.punch_bowls.title') }}</div>
            <img align="right" class="border" src="{{ asset('website/images/coast.jpg') }}" alt="Punch bowl with dry ice">
            <p>
                {{ __('dryice_uses.punch_bowls.description') }}
            </p>
            <p>
                <b>{{ __('dryice_uses.punch_bowls.usage') }}</b>
            </p>

            <div id="t1">{{ __('dryice_uses.cauldrons.usage') }}</div>
            <img align="right" class="border" src="{{ asset('website/images/cauldron.jpg') }}" alt="Cauldron with dry ice effect">
            <p>
                {{ __('dryice_uses.cauldrons.description') }} <a target="_blank" href="https://www.creativeeyemedia.com/Home.html">Creative Eye Media</a>.
            </p>
            <p>
                <b>{{ __('dryice_uses.cauldrons.usage') }}</b>
            </p>
            <br>
            <br>

            <div id="t1">{{ __('dryice_uses.dance_floors.title') }}</div>
            <img align="right" class="border" src="{{ asset('website/images/peasouper.jpg') }}" alt="Pea souper dry ice machine">
            <p>
                {!! __('dryice_uses.dance_floors.description') !!}
            </p>
            <p>
                {!! __('dryice_uses.dance_floors.option1') !!}
            </p>
            <p>
                {!! __('dryice_uses.dance_floors.option2') !!}
            </p>
            <p>
                {!! __('dryice_uses.dance_floors.option3') !!}
            </p>
            <p>
                <b>{!! __('dryice_uses.dance_floors.note') !!}</b>
            </p>
        </div>

        <!-- FOOD STORAGE -->
        <div class='info'>
            <h1 class='info_header'>{{ __('dryice_uses.food_storage.title') }}</h1>

            <p>
                {!! __('dryice_uses.food_storage.description1') !!}
            </p>
            <p>
                {!! __('dryice_uses.food_storage.description2') !!}
            </p>
        </div>

        <!-- SCIENCE EXPERIMENTS -->
        <div class='info'>
            <h1 class='info_header'>{!! __('dryice_uses.science.title') !!}</h1>

            <div class="row">
                <div class="col-md-6">
                    <p>
                        {!! __('dryice_uses.science.text1') !!}
                    </p>
                </div>
                <div class="col-md-6">
                    <img class="border img-fluid" src="{{ asset('website/images/volcano_cake.jpg') }}" alt="Volcano cake with dry ice">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/8tHOVVgGkpk?feature=player_detailpage" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-md-6">
                    <p>
                        {!! __('dryice_uses.science.text2') !!}
                    </p>
                    <p>
                        <i>{!! __('dryice_uses.science.video_credit') !!} www.youtube.com/brusspup</i>
                    </p>
                </div>
            </div>
        </div>

        <!-- VIDEOS - EVENTS -->
        <div class='info'>
            <h1 class='info_header'>{!! __('dryice_uses.videos.title') !!}</h1>

            <div class="row">
                <div class="col-md-6">
                    <p>
                        {!! __('dryice_uses.videos.intro') !!}
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/BtLPBotIjWs" allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            <div id="t1" class="mt-4">{!! __('dryice_uses.videos.subsection') !!}</div>
            <p>
                {!! __('dryice_uses.videos.explanation') !!}
            </p>

            <div class="row">
                <div class="col-md-6">
                    <h4>{!! __('dryice_uses.video.hot.0_1') !!}</h4>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/Q_sSohj3KmQ" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4>{!! __('dryice_uses.video.hot.5_6') !!}</h4>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/3mgKbsDhOMY" allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h4>{!! __('dryice_uses.video.hot.10_11') !!}</h4>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/mmtCbhN1OqA" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4>{!! __('dryice_uses.video.hot.15_16') !!}</h4>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/BPIxzTs0Dk0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h4>{!! __('dryice_uses.video.hot.20_21') !!}</h4>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/fZ9YYsuZdJQ" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4>{!! __('dryice_uses.video.cold.0_1') !!}</h4>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/CMaHg9ytw90" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
