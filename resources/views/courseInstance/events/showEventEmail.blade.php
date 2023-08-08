@extends('frontend.layouts.app')

@section ('title', trans('navs.frontend.event-emails.view_sent_email'))

@section('page-header')
    <h4><a href="{{ url('/courseInstance/events/event-dashboard/'. $sentEmail->eventEmails->event_id .'/email') }}">{{ trans('navs.frontend.event.dashboard')}}</a> > {{ trans('navs.frontend.event-emails.view_sent_email') }}</h4>
@endsection

@section('content')

    <div class = "panel panel-default">

        <div class = "panel-heading">
            <h5>{{ $sentEmail->eventEmails->event->DisplayEventName }}</h5>
        </div>

        <div class="panel-body">
            <table class="table table-striped table-hover">
                <tr>
                    <th>{{ trans('labels.eventEmails.sent_at')}}</th>
                    <td>{!! $sentEmail->DisplayCreatedAt !!} </td>
                </tr>
                <tr>
                    <th>{{ trans('labels.eventEmails.to')}}</th>
                    <td>{!! $sentEmail->to !!} </td>
                </tr>
                <tr>
                    <th>{{ trans('labels.eventEmails.cc')}}</th>
                    <td>{!! $sentEmail->cc !!} </td>
                </tr>
                <tr>
                    <th>{{ trans('labels.eventEmails.bcc')}}</th>
                    <td>{!! $sentEmail->bcc !!} </td>
                </tr>
                <tr>
                    <th>{{ trans('labels.eventEmails.subject')}}</th>
                    <td>{!! $sentEmail->subject !!} </td>
                </tr>
                <tr>
                    <th>{{ trans('labels.eventEmails.body')}}</th>
                    <td>{!! $sentEmail->body !!} </td>
                </tr>
                <tr>
                    <th>{{ trans('labels.eventEmails.id_num')}}</th>
                    <td>{!! $sentEmail->mailgun_id !!} </td>
                </tr>

            </table>
        </div>
    </div>

@endsection

@section('after-scripts')

@endsection

