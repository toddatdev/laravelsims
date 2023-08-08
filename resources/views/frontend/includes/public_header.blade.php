<!-- Navbar -->
<nav class="main-header navbar navbar-expand-md navbar-white navbar-light">
    <div class="container">
        <a href="/dashboard">
            <img alt="{{ trans('navs.frontend.dashboard') }}" src={{ URL::to('https://'.config('filesystems.disks.s3.bucket').'.s3.amazonaws.com/site-'.Session::get('site_id').'/banner-logo.png')}}>
        </a>

        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

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
                    <a href="{{ route('default.calendar') }}" class="nav-link">{{ trans('navs.frontend.scheduling.calendar') }}</a>
                </li>

                <li class="nav-item">
                    {{ link_to_route('catalog', trans('navs.frontend.scheduling.course_catalog'), [],
                        ['class' => 'nav-link']) }}
                </li>

                @if (! $logged_in_user)
                    {{-- If they are configured to allow open registration put the registration link in there.  --}}
                    @if( App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(3) == "Y")
                        <li class="nav-item">
                            <a href="{{ route('frontend.auth.register') }}" class="nav-link">{{ trans('navs.frontend.register') }}</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('frontend.auth.login') }}" class="nav-link">{{ trans('navs.frontend.login') }}</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('frontend.user.dashboard') }}" class="nav-link">{{ trans('navs.frontend.dashboard') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('frontend.auth.logout') }}" class="nav-link">{{ trans('navs.general.logout') }}</a>
                    </li>
                @endif
            </ul>
        </div>
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

