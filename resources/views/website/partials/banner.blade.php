<div class="layout" id="header">
    <div id="icon">
        <img style="border: none;" src="{{ asset('website/images/logo.gif') }}">
    </div>
    <div class="frost" id="frost_header"></div>
    <img style="padding: 2px;" src="{{ asset('website/images/iceberg.png') }}">
</div>

<div class='layout' id='buttons'>
    <ul class='dropdown'>
        <li><a href='{{ url('/') }}'>Iceberg</a></li>

        <li><a href='{{ url('/') }}'>Dry Ice</a>
            <ul class='sub-menu'>
                <li><a href='{{ url('/dryice_uses') }}'>Uses</a></li>
                <li><a href='{{ url('/dryice_safety') }}'>Safety</a></li>
                <li><a href='{{ url('/order') }}'><font color='#F00'>BUY NOW</font></a></li>
            </ul>
        </li>

        <li><a href='{{ url('/') }}'>Cleaning</a>
            <ul class='sub-menu'>
                <li><a href='{{ url('/blasting_info') }}'>Info</a></li>
                <li><a href='{{ url('/blasting_examples') }}'>Examples</a></li>
                <li><a href='{{ url('/blasting_manuals') }}'>Manuals</a></li>
                <li><a href='{{ url('/blasting_services') }}'>Services</a></li>
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
                    <span class='online' style='text-shadow: 1px 1px 1px #000'><b>ONLINE</b></span>
                </a>
                <ul class='sub-menu'>
                    <!-- Logout -->
                    <form id="logout-form" action="{{ $logoutRoute }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Log Out
                        </a>
                    </li>

                    <!-- Dashboard -->

                    @if ($admin)
                        <li><a href="{{ url('/admin/dashboard') }}"><font color='#0C0'>Dashboard</font></a></li>
                        <li><a href="{{ url('/admin/orders') }}">Order List</a></li>
                    @elseif ($customer)
                        <li><a href="{{ url('/order') }}">Place Order</a></li>
                        <li><a href="{{ url('/dryice_uses') }}">Usage Info</a></li>
                    @endif
                </ul>
            </li>
        @else
            <li><a href="{{ url('/login') }}">Login</a></li>
        @endif

        <li><a href='{{ url('/contact') }}'>Contact</a></li>
    </ul>
</div>
