{{-- Close content divs --}}
</div>
</div>

<div class="layout" id="footer">
    <div class="frost" id="frost_footer"></div>

    <div id="footer-text">
        <a href='https://www.facebook.com/icebergdryice/' target='_blank'>
            <img alt='Facebook' src='{{ asset('website/images/fb_icon_34.gif') }}'>
        </a>
        <a href='https://twitter.com/icebergdryice/' target='_blank'>
            <img alt='Twitter' src='{{ asset('website/images/twitter_icon_34.gif') }}'>
        </a>
        <a href='https://instagram.com/icebergdryice/' target='_blank'>
            <img alt='Instagram' src='{{ asset('website/images/instagram.png') }}'>
        </a>
{{--        @if(Auth::check() && Auth::user()->is_admin)--}}
{{--            <a href='{{ url('https://www.icebergshipping.com/pages/admin.php') }}'>--}}
{{--                <img src='{{ asset('images/background/iceberg_logo_34.jpg') }}'>--}}
{{--            </a>--}}
{{--        @endif--}}
    </div>

    <div id="footer-text"><b>(604) 524-0609</b></div>

    <div id="footer-text" style='text-decoration: underline;'>
        <a href="mailto:admin@icebergdryice.com?subject=Web Inquiry"><b>EMAIL</b></a>
    </div>

    <div id="footer-text">
        <span id="siteseal">
            <script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=vkvmFPv1K1kvmKWvaga8NPaHHRRVc4dKaQSzHBjJVTAzKxaNJi5pQivJ0hlD"></script>
        </span>
    </div>
</div>

{{-- Include scripts --}}
@yield('scripts')
<script>
    function changeLanguage(locale) {
        window.location.href = "{{ url('/') }}/change-language/" + locale;
    }
</script>
