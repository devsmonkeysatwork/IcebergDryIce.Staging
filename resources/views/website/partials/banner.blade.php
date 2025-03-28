<div class="layout" id="header">
    <!-- DEFAULT -->
    <div id="icon"><img style="border: none;" src="{{asset('website/images/logo.gif')}}"></div>
    <div class="frost" id="frost_header"></div>
    <img style="padding: 2px;" src="{{asset('website/images/iceberg.png')}}">
</div>

<div class='layout' id='buttons'>
    <ul class='dropdown'>
        <li><a href='{{ url('/') }}'>Iceberg</a></li>
        <li><a href='{{ url('/') }}'>Dry Ice</a>
            <ul class='sub-menu'>
                <!-- <li><a href='{{ url('/dryice/dryice_info') }}'>Info</a></li> -->
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
                <!-- <li><a href='{{ url('/pages/forsale/forsale_blasting') }}'>SALES</a></li> -->
            </ul>
        </li>

        @if(PHP_VERSION == '5.5.38')
            <li><a href='#'><font color='#900'>OFFLINE</font></a></li>
        @elseif(!Auth::check())
            <li><a href='{{ url('/admin') }}'>Login</a></li>
        @else
            @php
                $online_link = url('/pages/online/orders_list');
                if(Auth::user()->is_admin)
                    $online_link = url('/pages/online/admin');
            @endphp
            <li><a href='{{ $online_link }}'><span class='online' style='text-shadow: 1px 1px 1px #000'><b>ONLINE</b></span></a>
                <ul class='sub-menu'>
                    <li><a href='{{ url('/pages/online/logout') }}'>Log Out</a></li>
                    <li><a href='{{ url('/pages/online/orders_list') }}'>Order List</a></li>
                    <li><a href='{{ url('/pages/online/order_edit') }}'>New Order</a></li>
                    @if(Auth::user()->is_admin)
                        <!-- <li><a href='{{ url('/pages/sales/forsale_list') }}'><font color='#900'>One-offs</font></a></li> -->
                        <li><a href='{{ url('/pages/online/admin') }}'><font color='#C00'>ADMIN</font></a></li>
                        <li><a href='{{ url('/pages/sales/forsale_list') }}'><font color='#00C'>Online</font></a></li>
                        <li><a href='{{ url('/pages/reports/dashboard') }}'><font color='#0C0'>Dashboard</font></a></li>
                    @endif
                </ul>
            </li>
        @endif
        <li><a href='{{ url('/contact') }}'>Contact</a></li>
    </ul>
</div>
