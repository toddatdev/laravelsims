<nav class="navbar navbar-default"  style="background-color: {{ App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(1) }}">

    <div class="container-fluid">
        <div class="navbar-header">            
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#frontend-navbar-collapse">
                <span class="sr-only">{{ trans('labels.general.toggle_navigation') }}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a href="/dashboard"><img alt="{{ trans('navs.frontend.dashboard') }}" src={{ URL::to('https://'.config('filesystems.disks.s3.bucket').'.s3.amazonaws.com/site-'.Session::get('site_id').'/banner-logo.png')}}></a>
        </div><!--navbar-header-->

        <div class="collapse navbar-collapse" id="frontend-navbar-collapse">

            <ul class="nav navbar-nav navbar-right">
            {{-- Show the Language drop down menu if site_option 8 ("Multi-lingual") is set. -jl 2020-10-14 14:55 --}}
                @if( App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(8) == "Y")
                     @if (config('locale.status') && count(config('locale.languages')) > 1)
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" 
                                style="color:{{ App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(2) }}">
                                {{ trans('menus.language-picker.language') }}
                                <span class="caret"></span>
                            </a>

                            @include('includes.partials.lang')
                        </li>
                    @endif
                @endif

                <li>{{ link_to_route('default.calendar', trans('navs.frontend.scheduling.calendar'), [], ['class' => active_class(Active::checkRoute('default.calendar')), 'style' => 'color:'.App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(2) ]) }}</li>

                <li>{{ link_to_route('catalog', trans('navs.frontend.scheduling.course_catalog'), [], ['class' => active_class(Active::checkRoute('catalog')), 'style' => 'color:'.App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(2) ]) }}</li>

                @if ($logged_in_user)
                    <li>{{ link_to_route('frontend.user.dashboard', trans('navs.frontend.dashboard'), [], ['class' => active_class(Active::checkRoute('frontend.user.dashboard')), 'style' => 'color:'.App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(2) ]) }}</li>
                @endif

                @if (! $logged_in_user)
                    <li>{{ link_to_route('frontend.auth.login', trans('navs.frontend.login'), [], ['class' => active_class(Active::checkRoute('frontend.auth.login')), 'style' => 'color:'.App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(2) ]) }}</li>

                    {{-- If they are configured to allow open registration put the registration link in there.  --}}
                    @if( App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(3) == "Y")
                        <li>{{ link_to_route('frontend.auth.register', trans('navs.frontend.register'), [], ['class' => active_class(Active::checkRoute('frontend.auth.register')), 'style' => 'color:'.App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(2) ]) }}</li>
                    @endif
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ $logged_in_user->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            @permission('view-backend')
                                <li>{{ link_to_route('admin.dashboard', trans('navs.frontend.user.administration')) }}</li>
                            @endauth
                            <li>{{ link_to_route('frontend.user.account', trans('navs.frontend.user.account'), [], ['class' => active_class(Active::checkRoute('frontend.user.account')), 'style' => 'color:'.App\Models\Site\Site::find(Session::get('site_id'))->getSiteOption(2) ]) }}</li>
                            <li>{{ link_to_route('frontend.auth.logout', trans('navs.general.logout')) }}</li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div><!--navbar-collapse-->
    </div><!--container-->
</nav>