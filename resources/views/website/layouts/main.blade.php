<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Iceberg Dry Ice' }}</title>
    <!-- Add your CSS files here -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @yield('styles')
    @include('website.partials.header')
</head>
<body>

@include('website.partials.banner')

<div class="layout" id="content_bg">
    <div id="content">
        @yield('content')
    </div>
</div>

@include('website.partials.footer')

@yield('scripts')

</body>
</html>
