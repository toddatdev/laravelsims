<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        @if (! $logged_in_user)

            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('frontend.auth.login') }}" class="nav-link">{{ trans('navs.frontend.login') }}</a>
            </li>

        @else
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('frontend.user.dashboard') }}" class="nav-link">{{ trans('navs.frontend.dashboard') }}</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('frontend.auth.logout') }}" class="nav-link">{{ trans('navs.general.logout') }}</a>
            </li>
        @endif
    </ul>
</nav>
<!-- /.navbar -->
{{--<header class="main-header">--}}

{{--    <a href="{{ route('frontend.index') }}" class="logo">--}}
{{--        <!-- mini logo for sidebar mini 50x50 pixels -->--}}
{{--        <span class="logo-mini">--}}
{{--           {{ substr(app_name(), 0, 1) }}--}}
{{--        </span>--}}

{{--        <!-- logo for regular state and mobile devices -->--}}
{{--        <span class="logo-lg">--}}
{{--            {{ Session::get('site_abbrv') }}--}}
{{--        </span>--}}
{{--    </a>--}}

{{--    <nav class="navbar navbar-static-top" role="navigation">--}}
{{--        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">--}}
{{--            <span class="fas fa-bars"></span>--}}
{{--            <span class="sr-only">{{ trans('labels.general.toggle_navigation') }}</span>--}}
{{--        </a>--}}

{{--        <div class="col-xs-5 text-center">--}}
{{--            {{ link_to('/dashboard', trans('navs.frontend.dashboard')) }}--}}
{{--        </div>--}}

{{--        <div class="navbar-custom-menu">--}}
{{--            <ul class="nav navbar-nav">--}}
{{--                <li>--}}
{{--                    {{ link_to('/dashboard', trans('navs.frontend.dashboard')) }}--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                   {{ link_to('/account', access()->user()->full_name) }}--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </div><!-- /.navbar-custom-menu -->--}}
{{--    </nav>--}}
{{--</header>--}}

