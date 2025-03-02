<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" dir="{{ backpack_theme_config('html_direction') }}">

<head>
    @include(backpack_view('inc.head'))
</head>

<body class="{{ backpack_theme_config('classes.body') }}" bp-layout="vertical-light">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap');

    :root {
        --tblr-primary: #0256c5;
        --tblr-primary-rgb: 2, 86, 197;
    }

    body {
        overflow: hidden;
        background-color: #F5F6FA;
        color: black;
        font-family: Nunito Sans;
    }
    aside.navbar-vertical.navbar-expand-lg {
        margin-top: 110px;
    }
    nav.navbar.navbar-expand-lg {
        background: rgba(2, 86, 197, 1);
        height: 50px;
    }
    .navbar-brand {
        font-weight: 700;
        font-size: 32px;
        line-height: 48px;
        letter-spacing: -0.11px;
    }
    aside .nav-item {
        margin-top: 5px;
        margin-bottom: 5px;
    }
    aside  a.nav-link {
        font-size: 16px !important;
        font-weight: 600 !important;
        color: white;
    }
    aside  a.nav-link.active{
        background: linear-gradient(90deg, #454B5A 0%, #141C26 100%);
    }
    aside  a.nav-link i {
        font-size: 19px !important;
        font-weight: 700 !important;
        color: white;
    }


    h1 {
        font-size: 36px;
        font-weight: 900;
    }
    .navbar-nav svg{
        width: 15px;
    }
    .navbar-expand-lg.navbar-vertical~.navbar, .navbar-expand-lg.navbar-vertical~.page-wrapper {
        max-width: 100vw;
        max-height: 100vh;
        overflow-y: scroll;
        padding-bottom: 120px;
    }
    thead {
        background: #1a0f2a;
    }
    #crudTable thead tr th {
        color: white;
    }
    #crudTable tr {
        border-collapse: separate;
        border-spacing: 5em;
    }


    /*table*/
    .row .table-header {
        font-weight: 500;
    }

    .row .table-head-wrapper {
        overflow: hidden;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .row table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0 15px;
    }

    .row table thead tr {
        --bs-bg-opacity: 1 !important;
        background-color: #221e26 !important;
        color: var(--tblr-secondary-text-emphasis);
        text-transform: capitalize;
        padding: 10px 0;
    }

    .row table thead tr th {
        color: white;
        text-transform: capitalize;
        font-size: 14px;
        font-weight: 500;
    }

    .row table th,
    .row table td {
        font-size: 14px;
        padding: 12px 15px;
        vertical-align: middle;
    }

    .row table tbody tr {
        background-color: white;
        color: var(--tblr-body-color);
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        padding: 10px 5px;
    }

    .row table tbody tr td {
        padding: 15px;
    }

    .row table tbody tr:hover {
        background-color: var(--tblr-secondary-bg-subtle);
    }

    .row .table.recurring {
        margin-top: 38px;
    }
    label {
        color: rgba(91, 98, 107, 1);
    }
    .btn-add {
        border-radius: 10px;
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
    }

    @media (min-width: 1200px) {
        .container-xxl,
        .container-xl,
        .container-lg,
        .container-md,
        .container-sm,
        .container {
            max-width: 100%;
        }
    }

    @media (min-width: 1400px) {
        .container-xxl,
        .container-xl,
        .container-lg,
        .container-md,
        .container-sm,
        .container {
            max-width: 1390px;
        }
    }
    @media (min-width: 1600px) {
        .container-xxl,
        .container-xl,
        .container-lg,
        .container-md,
        .container-sm,
        .container {
            max-width: 90%;
        }
    }


</style>

{{--
@include(backpack_view('layouts.partials.light_dark_mode_logic'))
--}}

<div div class="page-10000">
    @include(backpack_view('layouts._horizontal.header_container'))
    @include(backpack_view('layouts._vertical_dark.menu_container'))

    <div class="page-wrapper">

        <div class="page-body">
            <main class="{{ backpack_theme_config('options.useFluidContainers') ? 'container-fluid' : 'container-xl' }}">

                @yield('before_breadcrumbs_widgets')
                @includeWhen(isset($breadcrumbs), backpack_view('inc.breadcrumbs'))
                @yield('after_breadcrumbs_widgets')
                @yield('header')

                <div class="container-fluid animated fadeIn">
                    @yield('before_content_widgets')
                    @yield('content')
                    @yield('after_content_widgets')
                </div>
            </main>
        </div>

        @include(backpack_view('inc.footer'))
    </div>
</div>

@yield('before_scripts')
@stack('before_scripts')

@include(backpack_view('inc.scripts'))
@include(backpack_view('inc.theme_scripts'))

@yield('after_scripts')
@stack('after_scripts')

</body>
</html>
