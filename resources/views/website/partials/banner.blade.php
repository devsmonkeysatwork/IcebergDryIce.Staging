<div class="layout" id="header">
    <div id="icon">
        <img style="border: none;" src="{{ asset('website/images/logo.gif') }}">
    </div>
    <div class="frost" id="frost_header"></div>
    <img style="padding: 2px;" src="{{ asset('website/images/iceberg.png') }}">
</div>

<div class='layout' id='buttons'>
    <ul class='dropdown'>
        <li><a href='{{ url('/') }}'>{{ __('nav_iceberg') }}</a></li>

        <li><a href='{{ url('/') }}'>{{ __('nav_dryice') }}</a>
            <ul class='sub-menu'>
                <li><a href='{{ url('/dryice_uses') }}'>{{ __('nav_uses') }}</a></li>
                <li><a href='{{ url('/dryice_safety') }}'>{{ __('nav_safety') }}</a></li>
                <li><a href='{{ url('/order') }}'><font color='#F00'>{{ __('nav_buy_now') }}</font></a></li>
            </ul>
        </li>

        <li><a href='{{ url('/') }}'>{{ __('nav_cleaning') }}</a>
            <ul class='sub-menu'>
                <li><a href='{{ url('/blasting_info') }}'>{{ __('nav_info') }}</a></li>
                <li><a href='{{ url('/blasting_examples') }}'>{{ __('nav_examples') }}</a></li>
                <li><a href='{{ url('/blasting_manuals') }}'>{{ __('nav_manuals') }}</a></li>
                <li><a href='{{ url('/blasting_services') }}'>{{ __('nav_services') }}</a></li>
            </ul>
        </li>

        @php
            $admin = Auth::guard('web')->user();
            $customer = Auth::guard('customer')->user();
        @endphp

        @if ($admin || $customer)
            @php
                $onlineLink = $admin ? url('/admin/dashboard') : url('/customer/dashboard');
                $logoutRoute = $admin ? route('logout.custom') : route('logout.custom');
            @endphp

            <li>
                <a href="{{ $onlineLink }}">
                    <span class='online' style='text-shadow: 1px 1px 1px #000'><b>{{ __('nav_online') }}</b></span>
                </a>
                <ul class='sub-menu'>
                    <!-- Logout -->
                    <form id="logout-form" action="{{ $logoutRoute }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('nav_logout') }}
                        </a>
                    </li>

                    <!-- Dashboard -->

                    @if ($admin)
                        <li><a href="{{ url('/admin/dashboard') }}"><font color='#0C0'>{{ __('nav_dashboard') }}</font></a></li>
                        <li><a href="{{ url('/admin/orders') }}">{{ __('nav_order_list') }}</a></li>
                    @elseif ($customer)
                        <li><a href="{{ url('/order') }}">{{ __('nav_place_order') }}</a></li>
                        <li><a href="{{ url('/dryice_uses') }}">{{ __('nav_usage_info') }}</a></li>
                        <li><a href="{{ route('customer.dashboard') }}">{{ __('nav_my_dashboard') }}</a></li>
                        <li><a href="{{ route('customer.orders') }}">{{ __('nav_my_orders') }}</a></li>
                    @endif
                </ul>
            </li>
        @else
            <li><a href="{{ url('/login') }}">{{ __('nav_login') }}</a></li>
        @endif

        <li><a href='{{ url('/contact') }}'>{{ __('nav_contact') }}</a></li>
        <li>
            <div class="language-switcher" style="width: 100px;">
                <select id="language-select" onchange="changeLanguage(this.value)">
                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                    <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>Français</option>
                </select>
            </div>
        </li>
    </ul>
</div>
