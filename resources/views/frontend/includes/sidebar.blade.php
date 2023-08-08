<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-secondary elevation-4">

    <!-- Brand Logo -->
    <a href="{{ route('frontend.index') }}" class="brand-link text-center">
        <img alt="{{ trans('navs.frontend.dashboard') }}"
             src={{ URL::to('https://'.config('filesystems.disks.s3.bucket').'.s3.amazonaws.com/site-'.Session::get('site_id').'/banner-logo.png')}}>
        <span class="brand-text font-weight-light"></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- My Account -->
        @if($logged_in_user)
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ $logged_in_user->picture }}" class="img-circle elevation-2 bg-white" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('frontend.user.account') }}" class="d-block">{{ link_to('/account', access()->user()->full_name) }}</a>
            </div>
        </div>
        @endif

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                {{--Dashboard--}}
                <li class="nav-item treeview">
                    <a href="{{ route('frontend.user.dashboard') }}" class="nav-link {{ active_class(Active::checkUriPattern(['dashboard*', '*event-dashboard*'])) }}">
                        <i class="nav-icon fad fa-house-user"></i>
                        <p>{{ trans('navs.frontend.dashboard') }}</p>
                    </a>
                </li>

                {{--Manage Courses--}}
                @permissions(['schedule-request', 'course-schedule-request', 'add-people-to-events', 'course-add-people-to-events', 'event-add-people-to-events', 'Course Manage Course Emails'])

                    <li class="nav-item has-treeview {{ active_class(Active::checkUriPattern(['*mycourses', '*scheduleRequest/create*', '*myScheduleRequest*', '*/catalog/users*', '*courseInstanceEmails*', '*course/content*', '*/waitlist', 'event/users/move/*']), 'menu-open') }}">
                        <a href="#" class="nav-link {{ active_class(Active::checkUriPattern(['*mycourses', '*scheduleRequest/create*', '*myScheduleRequest*', '*/catalog/users*', '*courseInstanceEmails*', '*course/content*', '*/waitlist', 'event/users/move/*'])) }}">
                            <i class="nav-icon fad fa-books"></i>
                            <p>
                                {{ trans('navs.frontend.course.manage_courses')  }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            {{-- My Courses --}}
                            <li class="nav-item">
                                <a href="{{ url('mycourses')  }}" class="nav-link {{ active_class(Active::checkUriPattern(['*mycourses', '*/catalog/users*', '*courseInstanceEmails*', '*course/content*'])) }}">
                                    <i class="fad fa-book-user nav-icon text-orange"></i>
                                    <p>{{ trans('navs.frontend.course.my_courses') }}</p>
                                </a>
                            </li>

                            {{-- Enrollment Requests --}}
                            @permissions(['add-people-to-events', 'course-add-people-to-events', 'event-add-people-to-events'])
                                <li class="nav-item">
                                    <a href="{{ url('mycourses/waitlist') }}"
                                       class="nav-link {{ active_class(Active::checkUriPattern(['*/waitlist', 'event/users/move/*'])) }}">
                                        <i class="fad fa-users-medical nav-icon text-blue"></i>
                                        <p>
                                            {{ trans('navs.frontend.course.enrollment-requests') }}
                                            @php
                                                //getting count here so it's always available in sidebar
                                                $countEnrollmentRequests = \App\Models\CourseInstance\EventUser::MyWaitlistRequests()->count();
                                            @endphp
                                            @if($countEnrollmentRequests != 0)
                                                <span class="right badge badge-danger">{{$countEnrollmentRequests}}</span>
                                            @endif
                                        </p>
                                    </a>
                                </li>
                            @endauth

                            {{-- Schedule Requests --}}
                            @permissions(['schedule-request', 'course-schedule-request'])
                                {{-- Create Schedule Requests --}}
                                <li class="nav-item">
                                    <a href="{{ url('scheduleRequest/create')  }}" class="nav-link {{ active_class(Active::checkUriPattern('*scheduleRequest/create')) }}">
                                        <i class="fad fa-notes-medical nav-icon text-green"></i>
                                        <p>{{ trans('navs.frontend.scheduling.add_request') }}</p>
                                    </a>
                                </li>

                                {{-- Pending Schedule Requests --}}
                                <li class="nav-item">
                                    <a href="{{ url('myScheduleRequest/pending') }}"
                                       class="nav-link {{ active_class(Active::checkUriPattern('*myScheduleRequest/pending*')) }}">
                                        <i class="fad fa-clipboard-list-check nav-icon text-purple"></i>
                                        <p>
                                            {{ trans('navs.frontend.scheduling.pending') }}
                                            @php
                                                $scheduleRequestController = new App\Http\Controllers\CourseInstance\ScheduleRequestController;
                                                $userPendingRequestCount = $scheduleRequestController->userPendingRequestCount();
                                            @endphp
                                            @if($userPendingRequestCount != 0)
                                                <span class="right badge badge-danger">{{$userPendingRequestCount}}</span>
                                            @endif
                                        </p>
                                    </a>
                                </li>

                            @endauth

                        </ul>
                    </li>
                @endauth

                {{--Scheduling--}}
                @permission('scheduling')

                    <li class="nav-item has-treeview {{ active_class(Active::checkUriPattern(['*calendar*', '*courseInstance/main/create*', '*scheduleRequest/pending', '*courseInstance/events/deleted*']), 'menu-open') }}">
                    <a href="#" class="nav-link {{ active_class(Active::checkUriPattern(['*calendar*', '*courseInstance/main/create*', '*scheduleRequest/pending', '*courseInstance/events/deleted*'])) }}">
                        <i class="nav-icon fad fa-calendar-edit"></i>
                        <p>
                            {{ trans('navs.frontend.scheduling.scheduling')  }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        {{-- Unresolved Comments --}}
                        @php
                            // Object that will be used to pass info for unresolved button in scheduling menu
                            $unresolvedObj = (object) [
                                'count'     => 0,
                                'startDate' => null,
                                'endDate'   => null,
                            ];
                            $unresolvedEvents = \App\Models\CourseInstance\Event::where('resolved', 0);
                            $unresolvedObj->startDate = date_create($unresolvedEvents->min('start_time'))->format('Y-m-d');
                            $unresolvedObj->endDate   = date_create($unresolvedEvents->max('start_time'))->format('Y-m-d');
                            $unresolvedObj->count     = $unresolvedEvents->count();
                        @endphp
                        @if($unresolvedObj->count)
                            <li class="nav-item">
                                <a href="{{ url('/calendar?date='.$unresolvedObj->startDate)  }}" class="nav-link {{ active_class(Active::checkUriPattern('*calendar*')) }}">
                                    <i class="fad fa-comments nav-icon text-blue"></i>
                                    <p>
                                        {{ trans('navs.frontend.scheduling.comments') }}
                                        <span class="right badge badge-danger">{{$unresolvedObj->count}}</span>
                                    </p>
                                </a>
                            </li>
                        @endif

                        {{-- Create Event --}}
                        <li class="nav-item">
                            <a href="{{ url('courseInstance/main/create') }}"
                               class="nav-link {{ active_class(Active::checkUriPattern('*courseInstance/main/create*')) }}">
                                <i class="fad fa-calendar-plus nav-icon text-green"></i>
                                <p>
                                    {{ trans('navs.frontend.scheduling.add_class') }}
                                </p>
                            </a>
                        </li>

                        {{-- Schedule Requests --}}
                        <li class="nav-item">
                            <a href="{{ url('scheduleRequest/pending')  }}" class="nav-link {{ active_class(Active::checkUriPattern('*scheduleRequest/pending')) }}">
                                <i class="fad fa-calendar-check nav-icon text-info"></i>
                                <p>
                                    {{ trans('navs.frontend.scheduling.pending') }}
                                    @php
                                        $scheduleRequestController = new App\Http\Controllers\CourseInstance\ScheduleRequestController;
                                        $pendingRequestCount = $scheduleRequestController->pendingRequestCount();
                                    @endphp
                                    @if($pendingRequestCount != 0)
                                        <span class="right badge badge-danger">{{$pendingRequestCount}}</span>
                                    @endif
                                </p>
                            </a>
                        </li>

                        {{-- Deleted Events --}}
                        <li class="nav-item">
                            <a href="{{ url('courseInstance/events/deleted') }}"
                               class="nav-link {{ active_class(Active::checkUriPattern('*courseInstance/events/deleted*')) }}">
                                <i class="fad fa-trash-restore nav-icon text-orange"></i>
                                <p>
                                    {{ trans('navs.frontend.scheduling.deleted_events') }}
                                </p>
                            </a>
                        </li>

                    </ul>
                </li>
                @endauth

                {{--Reports--}}
                @permission('site-report-creation')
                    <li class="nav-item has-treeview {{ active_class(Active::checkUriPattern(['reports*']), 'menu-open') }}">
                        <a href="#" class="nav-link {{ active_class(Active::checkUriPattern(['reports*'])) }}">
                            <i class="nav-icon fad fa-file-chart-line"></i>
                            <p>
                                {{ trans('navs.frontend.reports.reports')  }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            {{-- Class Activity Report --}}
                            <li class="nav-item">
                                <a href="{{ url('reports-event-activities') }}"
                                   class="nav-link {{ active_class(Active::checkUriPattern('reports-event-activities*')) }}">
                                    <i class=" nav-icon fad fa-analytics text-primary"></i>
                                    <p>
                                        {{ trans('navs.frontend.reports.activity') }}
                                    </p>
                                </a>
                            </li>
                            {{-- Event Roster Report --}}
                            <li class="nav-item">
                                <a href="{{ url('reports-event-rosters') }}"
                                   class="nav-link {{ active_class(Active::checkUriPattern('reports-event-rosters*')) }}">
                                    <i class=" nav-icon fas fa-users-class text-success"></i>
                                    <p>
                                        {{ trans('navs.frontend.reports.roster') }}
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>

                @endauth

                {{--Adminstration--}}
                @permission('view-backend')
                    <li class="nav-item treeview">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            <i class="nav-icon fad fa-users-cog"></i>
                            <p>{{ trans('navs.frontend.user.administration') }}</p>
                        </a>
                    </li>
                @endauth

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>


