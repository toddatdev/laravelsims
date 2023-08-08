<!-- large resolutions menu -->
<div class="pull-right d-none d-lg-block">
    <a href="/admin/site/emails?type=1" id="site" class="btn btn-primary btn-sm">{{ trans('menus.backend.siteEmails.site') }}</a>
    <a href="/admin/site/emails?type=2" id="course" class="btn btn-warning btn-sm">{{ trans('menus.backend.siteEmails.course') }}</a>
    <a href="/admin/site/emails?type=3" id="event" class="btn btn-danger btn-sm">{{ trans('menus.backend.siteEmails.event') }}</a>
    @if(!empty($create))
        {{ link_to_route('email.create.site', trans('menus.backend.siteEmails.btn'), [], ['class' => 'btn btn-success btn-sm', 'id'=> 'create-email']) }}
    @endif
</div>

<!--  mobile menu -->
<div class="pull-right d-lg-none">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Emails <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li id="site-mobile" ><a href="/admin/site/emails?type=1">{{ trans('menus.backend.siteEmails.site') }}</a></li>
            <li id="course-mobile" ><a href="/admin/site/emails?type=2">{{ trans('menus.backend.siteEmails.course') }}</a></li>
            <li id="event-mobile" ><a href="/admin/site/emails?type=3">{{ trans('menus.backend.siteEmails.event') }}</a></li>
            @if(!empty($create))
                <li>{{ link_to_route('email.create.site', trans('menus.backend.siteEmails.btn'), [], ['id'=> 'create-email-mobile']) }} </li>
            @endif
        </ul>
    </div>
</div>