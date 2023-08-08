<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-secondary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('frontend.index') }}" class="brand-link">
{{--        <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"--}}
{{--             style="opacity: .8">--}}
        <span class="brand-text font-weight-light">{{ Session::get('site_abbrv') }} {{ trans('menus.backend.sidebar.dashboard') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ $logged_in_user->picture }}" class="img-circle elevation-2 bg-white" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('frontend.user.account') }}" class="d-block">{{ link_to('/account', access()->user()->full_name) }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                {{--Sites Menu - Only Displayed to Admins--}}
                @permission(2)
                    <li class="nav-item treeview">
                        <a href="{{ route('all_sites') }}" class="nav-link {{ active_class(Active::checkUriPattern('sites/*')) }}">
                            <i class="nav-icon fas fa-university"></i>
                            <p>{{ trans('menus.backend.site.title') }}</p>
                        </a>
                    </li>
                @endauth

                {{-- Site Management Menu Option --}}
                @permissions(['client-manage-site-options','client-manage-site-email', 'manage_course_fees'])

                    <li class="nav-item has-treeview {{ active_class(Active::checkUriPattern('*site/*'), 'menu-open') }}">
                        <a href="#" class="nav-link {{ active_class(Active::checkUriPattern('*site/*')) }}">
                            <i class="nav-icon fas fa-globe"></i>
                            <p>
                                {{ trans('menus.backend.site.site') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            {{-- Client Manage Site Email--}}
                            @permission('client-manage-site-email')
                                <li class="nav-item">
                                    <a href="{{ route('emails.index') }}" class="nav-link {{ active_class(Active::checkUriPattern('*emails*')) }}">
                                        <i class="fas fa-envelope nav-icon"></i>
                                        <p>{{ trans('menus.backend.site.email') }}</p>
                                    </a>
                                </li>
                            @endauth

                            {{-- Client Manage Site Options--}}
                            @permission('client-manage-site-options')
                                <li class="nav-item">
                                    <a href="{{ route('list_site_options', ['site' => Session::get('site_id')]) }}"
                                       class="nav-link {{ active_class(Active::checkUriPattern('*options*')) }}">
                                        <i class="fa fa-cog nav-icon"></i>
                                        <p>{{ trans('menus.backend.site.options') }}</p>
                                    </a>
                                </li>
                            @endauth

                            {{-- Client Payments Report--}}
                            @permission('manage_course_fees')
                            <li class="nav-item">
                                <a href="{{ route('authNetTransactionsReport') }}"
                                   class="nav-link {{ active_class(Active::checkUriPattern('*authNetTransactionsReport*')) }}">
                                    <i class="fa fa-search-dollar nav-icon"></i>
                                    <p>{{ trans('menus.backend.site.payments') }}</p>
                                </a>
                            </li>
                            @endauth

                        </ul>
                    </li>
                @endauth

                {{--Building Management Menu Option--}}
                @permission(5)
                    <li class="nav-item treeview">
                        <a href="{{ route('active_buildings') }}" class="nav-link {{ active_class(Active::checkUriPattern('buildings/*')) }}">
                            <i class="nav-icon fas fa-building"></i>
                            <p>{{ trans('menus.backend.building.title') }}</p>
                        </a>
                    </li>
                @endauth

                {{--Location Management Menu Option--}}
                @permission(7)
                    <li class="nav-item treeview">
                        <a href="{{ route('active_locations') }}" class="nav-link {{ active_class(Active::checkUriPattern('locations/*')) }}">
                            <i class="nav-icon fas fa-map-marker"></i>
                            <p>{{ trans('menus.backend.location.title') }}</p>
                        </a>
                    </li>
                @endauth

                {{-- Resource Management Menu Option --}}
                @permission('manage-resources')

                <li class="nav-item has-treeview {{ active_class(Active::checkUriPattern('*resources/*'), 'menu-open') }}">
                    <a href="#" class="nav-link {{ active_class(Active::checkUriPattern('*resources/*')) }}">
                        <i class="nav-icon fas fa-medkit"></i>
                        <p>
                            {{ trans('menus.backend.resource.title') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        {{-- Manage Resource--}}
                        @permission('manage-resources')
                            <li class="nav-item">
                                <a href="{{ route('active_resources') }}" class="nav-link {{ active_class(Active::checkUriPattern('*resources/active*')) }}">
                                    <i class="fas fa-stretcher nav-icon"></i>
                                    <p>{{ trans('menus.backend.resource.manage') }}</p>
                                </a>
                            </li>
                        @endauth

                        {{-- Manage Resource Categories --}}
                        @permission('manage-resources')
                            <li class="nav-item">
                                <a href="{{ route('resource_category_index') }}"
                                   class="nav-link {{ active_class(Active::checkUriPattern('*resourceCategory*')) }}">
                                    <i class="fa fa-notes-medical nav-icon"></i>
                                    <p>{{ trans('menus.backend.resourceCategory.manage') }}</p>
                                </a>
                            </li>
                        @endauth
                    </ul>
                </li>
                @endauth

                {{--Course Management Menu Option, permissions: manage-courses, manage-templates, course-options, course_categories --}}
                @permissions([4,12,13,14])
                    <li class="nav-item treeview">
                        <a href="{{ route('active_courses') }}" class="nav-link {{ active_class(Active::checkUriPattern('courses/*')) }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>{{ trans('menus.backend.course.title') }}</p>
                        </a>
                    </li>
                @endauth

                {{-- permissions, manage-users, manage-roles  --}}
                @permissions([3,6])

                <li class="nav-item has-treeview {{ active_class(Active::checkUriPattern('admin/access/*'), 'menu-open') }}">
                    <a href="#" class="nav-link {{ active_class(Active::checkUriPattern('admin/access/*')) }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            {{ trans('menus.backend.access.title') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        {{-- Manage Users --}}
                        @permission(3)
                        <li class="nav-item">
                            <a href="{{ route('admin.access.user.index') }}" class="nav-link {{ active_class(Active::checkUriPattern('admin/access/user*')) }}">
                                <i class="fas fa-user nav-icon"></i>
                                <p>{{ trans('labels.backend.access.users.management') }}</p>
                            </a>
                        </li>
                        @endauth

                        {{-- Manage Roles --}}
                        @permission(6)
                        <li class="nav-item">
                            <a href="{{ route('admin.access.role.index') }}"
                               class="nav-link {{ active_class(Active::checkUriPattern('admin/access/role*')) }}">
                                <i class="fas fa-user-cog nav-icon"></i>
                                <p>{{ trans('labels.backend.access.roles.management') }}</p>
                            </a>
                        </li>
                        @endauth
                    </ul>
                </li>
                @endauth

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

{{--mitcks 2020-10-21 leaving this here as example if log viewer becomes functional--}}
{{--            --}}{{-- Only show this if you are a SIMS Administrator --}}
{{--            @role(1)--}}
{{--            <li class="{{ active_class(Active::checkUriPattern('admin/log-viewer*')) }} treeview">--}}
{{--                <a href="#">--}}
{{--                    <i class="fa fa-list"></i>--}}
{{--                    <span>{{ trans('menus.backend.log-viewer.main') }}</span>--}}
{{--                    <i class="fa fa-angle-left pull-right"></i>--}}
{{--                </a>--}}
{{--                <ul class="treeview-menu {{ active_class(Active::checkUriPattern('admin/log-viewer*'), 'menu-open') }}" style="display: none; {{ active_class(Active::checkUriPattern('admin/log-viewer*'), 'display: block;') }}">--}}
{{--                    <li class="{{ active_class(Active::checkUriPattern('admin/log-viewer')) }}">--}}
{{--                        <a href="{{ route('log-viewer::dashboard') }}">--}}
{{--                            <i class="fa fa-circle-o"></i>--}}
{{--                            <span>{{ trans('menus.backend.log-viewer.dashboard') }}</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}

{{--                    <li class="{{ active_class(Active::checkUriPattern('admin/log-viewer/logs')) }}">--}}
{{--                        <a href="{{ route('log-viewer::logs.list') }}">--}}
{{--                            <i class="fa fa-circle-o"></i>--}}
{{--                            <span>{{ trans('menus.backend.log-viewer.logs') }}</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </li>--}}
{{--            @endauth--}}

