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
        <h3>{{ trans('labels.scheduling.request_under_review') }}</h3>
        <h4>{{ trans('labels.scheduling.request_confirm_text') }}</h4>
        <p><a href="http://{{ Session::get('url_root') }}/myScheduleRequest/pending">{{ trans('labels.scheduling.view_my_pending') }}</a></p>
        <table>
            <tr>
                <td>{{ trans('labels.scheduling.course') }}:</td>
                <td>{{ $scheduleRequest->course->name }} ({{ $scheduleRequest->course->abbrv }})</td>
            </tr>
            <tr>
                <td>{{ trans('labels.scheduling.event_date') }}:</td>
                <td>{{ Carbon\Carbon::parse($scheduleRequest->start_time)->format('l, F d, Y') }}</td>
            </tr>
            <tr>
                <td>{{ trans('labels.general.time') }}:</td>
                <td>{{ date_create($scheduleRequest->start_time)->format('g:ia') .' - '. date_create($scheduleRequest->end_time)->format('g:ia') }}</td>
            </tr>
            <tr>
                <td>{{ trans('labels.calendar.location') }}:</td>
                <td>{{ $scheduleRequest->location->building->abbrv }} {{ $scheduleRequest->location->abbrv }}</td>
            </tr>
            <tr>
                <td>{{ trans('labels.scheduling.rooms') }}:</td>
                <td>{{ $scheduleRequest->num_rooms }}</td>
            </tr>
            <tr>
                <td>{{ trans('labels.scheduling.participants') }}:</td>
                <td>{{ $scheduleRequest->class_size }}</td>
            </tr>
            <tr>
                <td>{{ trans('labels.scheduling.sim_spec') }}:</td>
                <td>{{ $scheduleRequest->SimSpecialistNeededText() }}</td>
            </tr>
            <tr>
                <td>{{ trans('labels.scheduling.request_note') }}:</td>
                <td>{!! $scheduleRequest->notes !!}</td>
            </tr>
            <tr>
                <td>{{ trans('labels.scheduling.requested_by') }}:</td>
                <td>{!! $scheduleRequest->requestedBy() !!}</td>
            </tr>
        </table>
        {{--<p>{!! $scheduleRequest->email_content !!}</p>--}}
        {{--<p>no schedulers text: {{$scheduleRequest->no_schedulers}}</p>--}}
        {{-- <a href="{{ $scheduleRequest->email_url }}">{{ trans('labels.scheduling.view_request') }}</a> --}}
    </body>
