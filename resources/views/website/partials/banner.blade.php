<div class="layout" id="header">
    <div id="icon">
        <img style="border: none;" src="{{ asset('website/images/logo.gif') }}">
    </div>
    <div class="frost" id="frost_header"></div>
    <img style="padding: 2px;" src="{{ asset('website/images/iceberg.png') }}">
</div>

<div class='layout' id='buttons'>
    <ul class='dropdown'>
        <li><a href='{{ url('/') }}'>{{ __('iceberg') }}</a></li>

        <li><a href='{{ url('/') }}'>{{ __('dry_ice') }}</a>
            <ul class='sub-menu'>
                <li><a href='{{ url('/dryice_uses') }}'>{{ __('uses') }}</a></li>
                <li><a href='{{ url('/dryice_safety') }}'>{{ __('safety') }}</a></li>
                <li><a href='{{ url('/order') }}'><font color='#F00'>{{ __('buy_now') }}</font></a></li>
            </ul>
        </li>

        <li><a href='{{ url('/') }}'>{{ __('cleaning') }}</a>
            <ul class='sub-menu'>
                <li><a href='{{ url('/blasting_info') }}'>{{ __('info') }}</a></li>
                <li><a href='{{ url('/blasting_examples') }}'>{{ __('examples') }}</a></li>
                <li><a href='{{ url('/blasting_manuals') }}'>{{ __('manuals') }}</a></li>
                <li><a href='{{ url('/blasting_services') }}'>{{ __('services') }}</a></li>
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
                    <span class='online' style='text-shadow: 1px 1px 1px #000'><b>{{ __('online') }}</b></span>
                </a>
                <ul class='sub-menu'>
                    <!-- Logout -->
                    <form id="logout-form" action="{{ $logoutRoute }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('log_out') }}
                        </a>
                    </li>

                    <!-- Dashboard -->

                    @if ($admin)
                        <li><a href="{{ url('/admin/dashboard') }}"><font color='#0C0'>{{ __('dashboard') }}</font></a></li>
                        <li><a href="{{ url('/admin/orders') }}">{{ __('order_list') }}</a></li>
                    @elseif ($customer)
                        <li><a href="{{ url('/order') }}">{{ __('place_order') }}</a></li>
                        <li><a href="{{ url('/dryice_uses') }}">{{ __('usage_info') }}</a></li>
                        <li><a href="{{ route('customer.dashboard') }}">{{ __('my_dashboard') }}</a></li>
                        <li><a href="{{ route('customer.orders') }}">{{ __('my_orders') }}</a></li>
                    @endif
                </ul>
            </li>
        @else
            <li><a href="{{ url('/login') }}">{{ __('login') }}</a></li>
        @endif

        <li><a href='{{ url('/contact') }}'>{{ __('contact') }}</a></li>
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
