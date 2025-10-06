@extends('website.layouts.main')

@section('content')
    <div class="container">
        <!-- MEDIA BLASTING COMPARISON -->
        <div class='info'>
            <h1 class='info_header'>{!! __('blasting_info.title.comparison') !!}</h1>

            {!! __('blasting_info.intro.comparison') !!}<br><br>

            <table style="font-size: smaller" class="table_horz" rules='rows'>
                <tr>
                    <th width="200px">{!! __('blasting_info.table.headers.media') !!}</th>
                    <th width="75px">{!! __('blasting_info.table.headers.mose') !!}</th>
                    <th width="90px">{!! __('blasting_info.table.headers.cleanup') !!}</th>
                    <th>{!! __('blasting_info.table.headers.advantages') !!}</th>
                    <th>{!! __('blasting_info.table.headers.disadvantages') !!}</th>
                </tr>
                <tr>
                    <td>{{ __('Dry Ice') }}</td>
                    <td>{{ __('Soft') }}</td>
                    <td>0 lbs.</td>
                    <td><font color="blue">{{ __('Cold') }}</font>, {{ __('Thermal Shock') }}, <font color="green">{{ __('Environmentally Friendly') }}</font></td>
                    <td><font color="red">{{ __('Oxygen Displacement') }}</font></td>
                </tr>
                <tr>
                    <td>{{ __('Soda') }}</td>
                    <td>{{ __('Soft') }}</td>
                    <td>450 lbs.</td>
                    <td>{{ __('Buffers Smoke Smell') }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>{{ __('Walnut Shells, Corn Cobs, Sponge, Plastic Bead') }}</td>
                    <td>{{ __('Medium') }}</td>
                    <td>1800 lbs.</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>{{ __('Coal Slag, Glass') }}</td>
                    <td>{{ __('Hard') }}</td>
                    <td>1800 lbs.</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>{{ __('Water') }}</td>
                    <td>{{ __('Med-Hard') }}</td>
                    <td>1800 L</td>
                    <td>{{ __('Inexpensive') }}</td>
                    <td><font color="red">{{ __('Mould, Conductive') }}</font></td>
                </tr>
                <tr>
                    <td><font color="#666666">{{ __('Silica Sand') }}</font></td>
                    <td><font color="#666666">{{ __('Hard') }}</font></td>
                    <td><font color="#666666">1800 lbs.</font></td>
                    <td></td>
                    <td><font color="#666666">{{ __('Carcinogenic') }}</font></td>
                </tr>
                <tr>
                    <td>{{ __('Solvents') }}</td>
                    <td>{{ __('Soft') }}</td>
                    <td>{{ __('special') }}</td>
                    <td></td>
                    <td><font color="red">{{ __('Environmentally UNfriendly') }}</font></td>
                </tr>
            </table><br><br>

            <div id="t1">{!! __('blasting_info.sections.media.title') !!}</div>
            <p>{!! __('blasting_info.sections.media.text') !!}</p>

            <div id="t1">{!! __('blasting_info.sections.mose.title') !!}</div>
            <p>{!! __('blasting_info.sections.mose.text') !!}</p>
            <p>{!! __('blasting_info.sections.fryability.text') !!}</p>

            <div id="t1">{!! __('blasting_info.sections.cleanup.title') !!}</div>
            <p>{!! __('blasting_info.sections.cleanup.text') !!}</p>

            <div id="t1">{!! __('blasting_info.sections.advantages.title') !!}</div>
            <p>
                {!! __('blasting_info.sections.advantages.text.cold') !!}<br><br>
                {!! __('blasting_info.sections.advantages.text.thermalShock') !!}<br><br>
                {!! __('blasting_info.sections.advantages.text.ecoFriendly') !!}<br><br>
                {!! __('blasting_info.sections.advantages.text.soda') !!}<br><br>
                {!! __('blasting_info.sections.advantages.text.cheap') !!}
            </p>

            <div id="t1">{!! __('blasting_info.sections.disadvantages.title') !!}</div>
            <p>
                {!! __('blasting_info.sections.disadvantages.text.oxygen') !!}<br><br>
                {!! __('blasting_info.sections.disadvantages.text.mould') !!}<br><br>
                {!! __('blasting_info.sections.disadvantages.text.conductive') !!}<br><br>
                {!! __('blasting_info.sections.disadvantages.text.toxic') !!}
            </p>
        </div>

        <!-- HOW BLASTING WORKS -->
        <div class="info">
            <h1 class="info_header">{!! __('blasting_info.title.how') !!}</h1>

            <img class="border" align="right" alt="Example of the blasting/lifting process, courtesy of ColdJet" src="{{ asset('website/images/spatula.png') }}">
            <p>{!! __('blasting_info.sections.how.text1') !!}</p>
            <p>{!! __('blasting_info.sections.how.text2') !!}</p>
            <p>{!! __('blasting_info.sections.how.text3') !!}</p>
            <p>{!! __('blasting_info.sections.how.text4') !!}</p>
            <p>{!! __('blasting_info.sections.how.text5') !!}</p>
        </div>

        <!-- DRY ICE BLASTING ADVANTAGES -->
        <div class='info mt-5'>
            <h1 class='info_header'>{!! __('blasting_info.title.advantages') !!}</h1>

            <p>
                {!! __('blasting_info.advantages.details') !!}
            </p>

            <div id="t1" class="mt-4">{!! __('blasting_info.sections.benefits.title.noWaste') !!}</div>
            <p>{!! __('blasting_info.sections.benefits.details.noWaste') !!}</p>

            <div id="t1" class="mt-4">{!! __('blasting_info.sections.benefits.title.eco') !!}</div>
            <p>{!! __('blasting_info.sections.benefits.details.eco') !!}</p>

            <div id="t1" class="mt-4">{!! __('blasting_info.sections.benefits.title.nonConductive') !!}</div>
            <p>{!! __('blasting_info.sections.benefits.details.nonConductive') !!}</p>

            <div id="t1" class="mt-4">{!! __('blasting_info.sections.benefits.title.cold') !!}</div>
            <p>{!! __('blasting_info.sections.benefits.details.cold') !!}</p>

            <div id="t1" class="mt-4">{!! __('blasting_info.sections.benefits.title.lessAbrasive') !!}</div>
            <p>{!! __('blasting_info.sections.benefits.details.lessAbrasive') !!}</p>

            <div id="t1" class="mt-4">{!! __('blasting_info.sections.benefits.title.inPlace') !!}</div>
            <p>{!! __('blasting_info.sections.benefits.details.inPlace') !!}</p>

            <div id="t1" class="mt-4">{!! __('blasting_info.sections.benefits.title.foodSafe') !!}</div>
            <p>{!! __('blasting_info.sections.benefits.details.foodSafe') !!}</p>

            <div id="t1" class="mt-4">{!! __('blasting_info.sections.benefits.title.fast') !!}</div>
            <p>{!! __('blasting_info.sections.benefits.details.fast') !!}</p>
        </div>
    </div>
@endsection
