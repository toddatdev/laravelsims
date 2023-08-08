<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <div class="collapse navbar-collapse order-3" id="navbarCollapse">

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">

            {{-- Show the Language drop down menu if site_option 8 ("Multi-lingual") is set. -jl 2020-10-14 14:55 --}}
            @if( App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(8) == "Y")
                @if (config('locale.status') && count(config('locale.languages')) > 1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ trans('menus.language-picker.language') }}
                        </a>
                        @include('includes.partials.lang')
                    </li>
                @endif
            @endif

            <li class="nav-item">
                <a href="{{ route('default.calendar') }}" class="nav-link">
                    <span class="d-none d-md-block">{{ trans('navs.frontend.scheduling.calendar') }}</span>
                    <i class="fad fa-2x fa-calendar d-md-none d-sm-block"></i>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('catalog') }}" class="nav-link">
                    <span class="d-none d-md-block">{{ trans('navs.frontend.scheduling.course_catalog') }}</span>
                    <i class="fad fa-2x fa-books d-md-none d-sm-block"></i>
                </a>
            </li>


            @if (! $logged_in_user)
                @if( App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(3) == "Y")
                    <li class="nav-item">
                        <a href="{{ route('frontend.auth.register') }}" class="nav-link">{{ trans('navs.frontend.register') }}</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('frontend.auth.login') }}" class="nav-link">{{ trans('navs.frontend.login') }}</a>
                </li>
            @else
    {{--            Hiding for now - dashboard can be accessed via sidebar--}}
    {{--            <li class="nav-item d-none d-sm-inline-block">--}}
    {{--                <a href="{{ route('frontend.user.dashboard') }}" class="nav-link">{{ trans('navs.frontend.dashboard') }}</a>--}}
    {{--            </li>--}}
                <li class="nav-item">
                    <a href="{{ route('frontend.auth.logout') }}" class="nav-link">{{ trans('navs.general.logout') }}</a>
                </li>
            @endif
        </ul>
    </div>
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

