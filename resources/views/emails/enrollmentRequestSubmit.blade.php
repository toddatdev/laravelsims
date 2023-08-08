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
        <h3>{{$enrollmentRequest->event->DisplayEventNameShort}} {{ trans('labels.enrollment.new_request_email_subject') }}</h3>
        {{--If no one has permission to add this person add alert for an email sent to site help--}}
        @isset($enrollmentRequest->no_approvers)
            <h2 style="color:red">{{$enrollmentRequest->no_approvers}}</h2>
        @endisset
        {{--links to event dashboard and rending enrollments to add them--}}
        <p><a href="http://{{ Session::get('url_root') }}/courseInstance/events/event-dashboard/{{$enrollmentRequest->event->id}}/waitlist">{{ trans('labels.enrollment.add_to_event') }}</a> (<a href="https://{{ Session::get('url_root') }}/mycourses/waitlist">{{ trans('labels.enrollment.view_all_requests') }}</a>)</p>
        <table>
            {{--Requestor's Name--}}
            <tr>
                <td>{{ trans('labels.enrollment.name') }}:</td>
                <td>{!! $enrollmentRequest->user->NameEmail !!}</td>
            </tr>
            {{--Event Name/Date/Time--}}
            <tr>
                <td>{{ trans('labels.enrollment.event') }}:</td>
                <td>{{$enrollmentRequest->event->DisplayEventNameShort}}</td>
            </tr>
            {{--Requested Role--}}
            <tr>
                <td>{{ trans('labels.enrollment.role') }}:</td>
                <td>{{ $enrollmentRequest->role->name }}</td>
            </tr>
            {{--Comments - only display is not null--}}
            @isset($enrollmentRequest->request_notes)
                <tr>
                    <td>{{ trans('labels.enrollment.comments') }}:</td>
                    <td>{{ $enrollmentRequest->request_notes }}</td>
                </tr>
            @endif
            {{--Request Created At--}}
            <tr>
                <td>{{ trans('labels.enrollment.requested') }}:</td>
                <td>{{ $enrollmentRequest->DisplayCreatedAt }}</td>
            </tr>
        </table>

    </body>
