
@extends('website.layouts.main')

@section('content')
    <div class="container">
        <h1 class='info_header'>Dry Ice Blasting Manuals test</h1>
        <table>
            <tr>
                <td width='350px'>ColdJet 100</td>
                <td rowspan='2'><img src='{{asset('website/images/cj100.png')}}'></td>
            </tr><tr><td valign="top">
                    <a href="{{asset('website/downloads/cj100_training.wmv')}}" target='_blank'>Training</a><br>
                    <a href="{{asset('website/downloads/cj100_parts_05-16-12.pdf')}}" target='_blank'>Parts</a></td>
            </tr><tr>
                <td>ColdJet 75</td>
                <td rowspan='2'><img src='{{asset('website/images/cj75.png')}}'></td>
            </tr><tr><td valign="top">
                    <a href='website/downloads/cj75_manual.pdf' target='_blank'>Manual</a><br>
                    <a href='website/downloads/cj75_training.wmv' target='_blank'>Training</a><br>
                    <a href='website/downloads/cj75_parts.pdf' target='_blank'>Parts</a><br>
                    <a href='website/downloads/cj75_parts_applicator.pdf' target='_blank'>Applicator Parts</a></td>

            </tr>
        </table>
    </div>
@endsection
