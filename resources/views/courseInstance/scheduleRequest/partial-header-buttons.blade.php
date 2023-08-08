<div class="pull-right d-none d-xl-block">
@permissions(['schedule-request', 'course-schedule-request'])
        @if(Request::is('myScheduleRequest/*'))
            <a href="/scheduleRequest/create" class="btn btn-success btn-sm">{{ trans('menus.frontend.scheduling.add-request') }}</a>
        @endif
    @endauth
    <a href="#" id="pending" class="btn editButton btn-sm">{{ trans('menus.frontend.scheduling.view-pending') }}</a>
    <a href="#" id="approved" class="btn btn-success btn-sm">{{ trans('menus.frontend.scheduling.view-approved') }}</a>
    <a href="#" id="denied" class="btn deleteButton btn-sm">{{ trans('menus.frontend.scheduling.view-denied') }}</a>
    <a href="#" id="all" class="btn infoButton btn-sm">{{ trans('menus.frontend.scheduling.view-all') }}</a>
</div><!--pull right-->

<div class="pull-right d-xl-none">
        <div class="dropdown">
            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Schedule Request Tasks
            <span class="caret"></span></button>
            <ul class="dropdown-menu">
                @permissions(['schedule-request', 'course-schedule-request'])
                    @if(Request::is('myScheduleRequest/*'))
                        <li class="pending" id="create"><a href="/scheduleRequest/create">{{ trans('menus.frontend.scheduling.add-request') }}</a></li>
                    @endif
                @endauth
                <li class="pending" id="pending-mobile"><a href="#">{{ trans('menus.frontend.scheduling.view-pending') }}</a></li>
                <li class="approved" id="approved-mobile"><a href="#">{{ trans('menus.frontend.scheduling.view-approved') }}</a></li>
                <li class="denied" id="denied-mobile"><a href="#">{{ trans('menus.frontend.scheduling.view-denied') }}</a></li>
                <li class="all" id="all-mobile"><a href="#">{{ trans('menus.frontend.scheduling.view-all') }}</a></li>
            </ul>
        </div>
</div><!--pull right-->

<div class="clearfix"></div>