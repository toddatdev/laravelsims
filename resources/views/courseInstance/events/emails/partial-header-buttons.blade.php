<!-- large resolutions menu -->
<div class="float-right">
    {{--<a href="{{'/courseInstance/main/confirmation/'.Request::segment(4)}}" id="return-event" class="btn btn-primary btn-xs">{{ trans('navs.frontend.event-emails.focus') }}</a>--}}
    @if(!empty($create))
        {{ link_to_route('event.email.create', trans('menus.backend.siteEmails.btn'), ['event_id' => Request::segment(4)], ['class' => 'btn btn-success btn-sm', 'id'=> 'create-email']) }}
    @endif
</div>
