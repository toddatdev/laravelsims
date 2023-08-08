<!-- large resolutions menu -->
<div class="pull-right d-none d-lg-block">
    <a href="/admin/access/role?type=1" id="site" class="btn btn-primary btn-sm">{{ trans('menus.backend.access.roles.site_roles') }}</a> <!-- trans('menus.backend.access.roles.site_roles') -->
    <a href="/admin/access/role?type=2" id="course" class="btn btn-warning btn-sm">{{ trans('menus.backend.access.roles.course_roles') }}</a>
    <a href="/admin/access/role?type=3" id="event" class="btn btn-danger btn-sm">{{ trans('menus.backend.access.roles.event_roles') }}</a>
    @if(!empty($create))
        {{ link_to_route('role.create.site', trans('menus.backend.access.roles.create'), [], ['class' => 'btn btn-success btn-sm', 'id'=> 'create-role']) }}
    @endif
</div>

<!--  mobile menu -->
<div class="pull-right d-lg-none">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            {{ trans('menus.backend.access.roles.main') }} <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li id="site-mobile"><a href="/admin/access/role?type=site">{{ trans('menus.backend.access.roles.site_roles') }}</a></li>
            <li id="course-mobile"><a href="/admin/access/role?type=course">{{ trans('menus.backend.access.roles.course_roles') }}</a></li>
            <li id="event-mobile"><a href="/admin/access/role?type=event">{{ trans('menus.backend.access.roles.event_roles') }}</a></li>
            @if(!empty($create))
                {{ link_to_route('role.create.site', trans('menus.backend.access.roles.create'), [], ['class' => 'btn btn-success btn-sm', 'id'=> 'create-role']) }}
            @endif
        </ul>
    </div>
</div>