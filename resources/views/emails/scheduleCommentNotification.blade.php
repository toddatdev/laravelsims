<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title></title>
        <style type="text/css">
            .indented {
                padding-left: 50pt;
                padding-right: 50pt;
                color: blue;
            }
        </style>
    </head>

    <body>

        <!-- if it's an event, you access the event related model instead of schedule request. -->
        @if ($scheduleComment->isEvent)
        <h3>{{ trans('labels.scheduling.new_comment_on') }} {{ $scheduleComment->courseInstance->course->abbrv }} {{ trans('labels.scheduling.event_on') }} {{ $scheduleComment->start_str }}</h3>
        <p><b>{{ $scheduleComment->email_author }}</b> {{ trans('labels.scheduling.commented_at') }} {{ $scheduleComment->email_timestamp}}</p>
        <p class="indented">{!! $scheduleComment->email_comment !!}</p>
        <p><a href="{{ $scheduleComment->email_url }}">{{ trans('labels.scheduling.view_comments') }}</a></p>
        @else
        <h3>{{ trans('labels.scheduling.new_comment_on') }} {{ $scheduleComment->course->abbrv }}  {{ trans('labels.scheduling.schedule_request_for') }} {{ $scheduleComment->start_str }}</h3>
        <p><b>{{ $scheduleComment->email_author }}</b> {{ trans('labels.scheduling.commented_at') }} {{ $scheduleComment->email_timestamp}}</p>
        <p class="indented">{!! $scheduleComment->email_comment !!}</p>
        <p><a href="{{ $scheduleComment->email_url }}">{{ trans('labels.scheduling.view_comments') }}</a></p>
        @endif

    </body>
</html>
