{{--@if($auth ?? true)--}}
{{--    <li class="nav-separator"></li>--}}
{{--    @if (backpack_auth()->guest())--}}
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link" href="{{ route('backpack.auth.login') }}">--}}
{{--                <i class="nav-icon la la-sign-in-alt d-block d-lg-none d-xl-block"></i> <span>{{ trans('backpack::base.login') }}</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--    @else--}}
{{--        <li class="nav-item dropdown d-none d-lg-block">--}}
{{--            <a class="nav-link dropdown-toggle d-none" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="true">--}}
{{--                <span class="avatar avatar-sm rounded-circle me-2">--}}
{{--                    <img class="avatar avatar-sm rounded-circle bg-transparent" src="{{ backpack_avatar_url(backpack_auth()->user()) }}"--}}
{{--                         alt="{{ backpack_auth()->user()->name }}" onerror="this.style.display='none'"--}}
{{--                         style="margin: 0;position: absolute;left: 0;z-index: 1;">--}}
{{--                    <span class="avatar avatar-sm rounded-circle backpack-avatar-menu-container text-center">--}}
{{--                        {{ backpack_user()->getAttribute('name') ? mb_substr(backpack_user()->name, 0, 1, 'UTF-8') : 'A' }}--}}
{{--                    </span>--}}
{{--                </span>--}}
{{--                {{ backpack_user()->name }}--}}
{{--            </a>--}}
{{--            <div class="nav-item" data-bs-popper="static">--}}
{{--                <hr>--}}
{{--                <h4 class="text-white mx-4 d-none d-lg-block">SETTINGS</h4>--}}
{{--                @if(config('backpack.base.setup_my_account_routes'))--}}
{{--                    <a class="nav-link" href="{{ route('backpack.account.info') }}">--}}
{{--                        <i class="nav-icon la la-user d-block"></i>--}}
{{--                        {{ trans('backpack::base.my_account') }}--}}
{{--                    </a>--}}
{{--                @endif--}}
{{--                <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: inline;">--}}
{{--                    @csrf--}}
{{--                    <a  class="nav-link text-danger border-0 bg-transparent" style="cursor: pointer;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">--}}
{{--                        <i class="nav-icon la la-sign-out-alt d-block me-3"></i>--}}
{{--                        {{ trans('backpack::base.logout') }}--}}
{{--                    </a>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </li>--}}
{{--    @endif--}}

{{--@endif--}}


@php
    $admin = Auth::guard('web')->user();
    $customer = Auth::guard('customer')->user();
@endphp

@if($auth ?? true)
    <li class="nav-separator"></li>

{{--     Check if user is authenticated with web guard (admin)--}}
    @if (!$admin)
{{--         Check if user is authenticated with customer guard--}}
        @if (!$customer)
{{--             Both guards are guest - show login options--}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login.form') }}">
                    <i class="nav-icon la la-sign-in-alt d-block d-lg-none d-xl-block"></i>
                    <span>{{ trans('backpack::base.login') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login.form') }}">
                    <i class="nav-icon la la-user d-block d-lg-none d-xl-block"></i>
                    <span>Customer Login</span>
                </a>
            </li>
        @else
{{--             Customer is authenticated but admin is guest--}}
            <li class="nav-item dropdown d-none d-lg-block">
                <a class="nav-link dropdown-toggle d-none" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="true">
                    <span class="avatar avatar-sm rounded-circle me-2">
                        <img class="avatar avatar-sm rounded-circle bg-transparent" src="{{ $customer->avatar ?? asset('images/default-avatar.png') }}"
                             alt="{{ $customer->name }}" onerror="this.style.display='none'"
                             style="margin: 0;position: absolute;left: 0;z-index: 1;">
                        <span class="avatar avatar-sm rounded-circle backpack-avatar-menu-container text-center">
                            {{ $customer->name ? mb_substr($customer->name, 0, 1, 'UTF-8') : 'C' }}
                        </span>
                    </span>
                    {{ $customer->name }} (Customer)
                </a>
                <div class="nav-item" data-bs-popper="static">
                    <hr>
                    <h4 class="text-white mx-4 d-none d-lg-block">CUSTOMER SETTINGS</h4>
                    <a class="nav-link" href="{{ route('customer.profile') }}">
                        <i class="nav-icon la la-user d-block"></i>
                        My Profile
                    </a>
                    <form id="customer-logout-form" action="{{ route('logout.custom') }}" method="POST" style="display: inline;">
                        @csrf
                        <a class="nav-link text-danger border-0 bg-transparent" style="cursor: pointer;" onclick="event.preventDefault(); document.getElementById('customer-logout-form').submit();">
                            <i class="nav-icon la la-sign-out-alt d-block me-3"></i>
                            Logout
                        </a>
                    </form>
                </div>
            </li>

        @endif
    @else
{{--         Admin user is authenticated--}}
        @if (!$customer)
{{--             Only admin user is authenticated--}}
            <li class="nav-item dropdown d-none d-lg-block">
                <a class="nav-link dropdown-toggle d-none" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="true">
                    <span class="avatar avatar-sm rounded-circle me-2">
                        <img class="avatar avatar-sm rounded-circle bg-transparent" src="{{ $admin->avatar ?? asset('images/default-avatar.png') }}"
                             alt="{{ $admin->name }}" onerror="this.style.display='none'"
                             style="margin: 0;position: absolute;left: 0;z-index: 1;">
                        <span class="avatar avatar-sm rounded-circle backpack-avatar-menu-container text-center">
                            {{ $admin->name ? mb_substr($admin->name, 0, 1, 'UTF-8') : 'A' }}
                        </span>
                    </span>
                    {{ $admin->name }} (Admin)
                </a>
                <div class="nav-item" data-bs-popper="static">
                    <hr>
                    <h4 class="text-white mx-4 d-none d-lg-block">ADMIN SETTINGS</h4>
                    @if(config('backpack.base.setup_my_account_routes'))
                        <a class="nav-link" href="{{ route('backpack.account.info') }}">
                            <i class="nav-icon la la-user d-block"></i>
                            {{ trans('backpack::base.my_account') }}
                        </a>
                    @endif
                    <form id="logout-form" action="{{ route('logout.custom') }}" method="POST" style="display: inline;">
                        @csrf
                        <a class="nav-link text-danger border-0 bg-transparent" style="cursor: pointer;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="nav-icon la la-sign-out-alt d-block me-3"></i>
                            {{ trans('backpack::base.logout') }}
                        </a>
                    </form>
                </div>
            </li>
        @else
{{--             Both admin and customer are authenticated--}}
            <li class="nav-item dropdown d-none d-lg-block">
                <a class="nav-link dropdown-toggle d-none" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="true">
                    <span class="avatar avatar-sm rounded-circle me-2">
                        <img class="avatar avatar-sm rounded-circle bg-transparent" src="{{ $admin->avatar ?? asset('images/default-avatar.png') }}"
                             alt="{{ $admin->name }}" onerror="this.style.display='none'"
                             style="margin: 0;position: absolute;left: 0;z-index: 1;">
                        <span class="avatar avatar-sm rounded-circle backpack-avatar-menu-container text-center">
                            {{ $admin->name ? mb_substr($admin->name, 0, 1, 'UTF-8') : 'A' }}
                        </span>
                    </span>
                    {{ $admin->name }} (Admin)
                </a>
                <div class="nav-item" data-bs-popper="static">
                    <hr>
                    <h4 class="text-white mx-4 d-none d-lg-block">ADMIN SETTINGS</h4>
                    @if(config('backpack.base.setup_my_account_routes'))
                        <a class="nav-link" href="{{ route('backpack.account.info') }}">
                            <i class="nav-icon la la-user d-block"></i>
                            {{ trans('backpack::base.my_account') }}
                        </a>
                    @endif
                    <form id="logout-form" action="{{ route('logout.custom') }}" method="POST" style="display: inline;">
                        @csrf
                        <a class="nav-link text-danger border-0 bg-transparent" style="cursor: pointer;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="nav-icon la la-sign-out-alt d-block me-3"></i>
                            Admin Logout
                        </a>
                    </form>
                </div>
            </li>
{{--             Customer dropdown when both are authenticated--}}
            <li class="nav-item dropdown d-none d-lg-block">
                <a class="nav-link dropdown-toggle d-none" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="true">
                    <span class="avatar avatar-sm rounded-circle me-2">
                        <img class="avatar avatar-sm rounded-circle bg-transparent" src="{{ $customer->avatar ?? asset('images/default-avatar.png') }}"
                             alt="{{ $customer->name }}" onerror="this.style.display='none'"
                             style="margin: 0;position: absolute;left: 0;z-index: 1;">
                        <span class="avatar avatar-sm rounded-circle backpack-avatar-menu-container text-center">
                            {{ $customer->name ? mb_substr($customer->name, 0, 1, 'UTF-8') : 'C' }}
                        </span>
                    </span>
                    {{ $customer->name }} (Customer)
                </a>
                <div class="nav-item" data-bs-popper="static">
                    <hr>
                    <h4 class="text-white mx-4 d-none d-lg-block">CUSTOMER SETTINGS</h4>
                    <a class="nav-link" href="{{ route('customer.profile') }}">
                        <i class="nav-icon la la-user d-block"></i>
                        My Profile
                    </a>
                    <form id="customer-logout-form" action="{{ route('logout.custom') }}" method="POST" style="display: inline;">
                        @csrf
                        <a class="nav-link text-danger border-0 bg-transparent" style="cursor: pointer;" onclick="event.preventDefault(); document.getElementById('customer-logout-form').submit();">
                            <i class="nav-icon la la-sign-out-alt d-block me-3"></i>
                            Customer Logout
                        </a>
                    </form>
                </div>
            </li>
        @endif
    @endif

@endif




{{--
    IMPORTANT NOTE!
    @include(backpack_view('inc.topbar_left_content')) in no longer used!
--}}
