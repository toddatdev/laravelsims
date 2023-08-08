@extends('frontend.layouts.app')

@section ('title', trans('labels.eventEmails.creating'))

@section('page-header')
    <h4><a href="{{ url('/courseInstance/events/event-dashboard/'. $event->id .'/email') }}">{{ trans('navs.frontend.event.dashboard')}}</a> > {{ trans('labels.eventEmails.creating') }}</h4>
@endsection

@section('content')
    {!! Form::open(['url' => 'courseInstance/events/event-dashboard/'.$event_id.'/emails', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) !!}
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $event->DisplayEventName }}</h3>

                    </div>
                    <div class="card-body">
                        @include('courseInstance.events.emails.partials.email', ['submitButton' => trans('buttons.general.crud.create')])
                    </div>
                </div>
            </div>
        </div>
    </section>
    {!! Form::close() !!}
@endsection