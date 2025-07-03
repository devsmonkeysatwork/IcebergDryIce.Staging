<aside data-menu-theme={{ $theme ?? 'system' }} class="{{ backpack_theme_config('classes.sidebar') ?? 'navbar navbar-vertical '.(($right ?? false) ? 'navbar-right' : '').' navbar-expand-lg navbar-'.($theme ?? 'light') }} @if(backpack_theme_config('options.sidebarFixed')) navbar-fixed @endif">
    <div class="container-fluid">
        <ul class="nav navbar-nav d-flex flex-row align-items-center justify-content-between w-100 d-block d-lg-none">
            @include(backpack_view('layouts.partials.mobile_toggle_btn'), ['forceWhiteLabelText' => true])
            <div class="d-flex flex-row align-items-center">
                @include(backpack_view('inc.topbar_left_content'))
                @include(backpack_view('inc.topbar_right_content'))
            </div>
    {{--        @include(backpack_view('inc.menu_user_dropdown'))--}}
        </ul>

        <div class="collapse navbar-collapse" id="mobile-menu">
            <ul class="navbar-nav pt-lg-3">
                <h4 class="text-white mx-4 d-none d-lg-block">ADMIN MENU</h4>
                @include(backpack_view('inc.sidebar_content'))
                @include(backpack_view('layouts._vertical.sidebar_content_top'))
            </ul>
        </div>
    </div>
</aside>
