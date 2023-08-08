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
        <h3>{{ trans('labels.scheduling.request_approved') }}</h3>
        <h4>{{ trans('labels.scheduling.request_approved_text', ['eventName'=>$event->DisplayEventNameShort]) }}</h4>
        <p><a href="http://{{ Session::get('url_root') }}/courseInstance/events/event-dashboard/{{$event->id}}">{{ trans('labels.scheduling.request_approved_link') }}</a></p>
        <table>
            <tr>
                <td>{{ trans('labels.event.course') }}:</td>
                <td>{{ $event->CourseNameAndAbbrv }}</td>
            </tr>
            <tr>
                <td>{{ trans('labels.event.class_date') }}:</td>
                <td>{{ $event->DisplayDateStartEndTimes }}</td>
            </tr>
            <tr>
                <td>{{ trans('labels.event.initial_meeting_room')}}:</td>
                <td>{{ $event->DisplayIMR }}</td>
            </tr>
            <tr>
                <td>{{ trans('labels.event.event_rooms') }}:</td>
                <td>{{ $event->eventRooms() }}</td>
            </tr>
            {{--hide public comments when value is null--}}
            @isset($event->public_comments)
                <tr>
                    <td>{{ trans('labels.event.public_notes') }}:</td>
                    <td>{{ $event->public_comments }}</td>
                </tr>
            @endisset
        </table>
    </body>
