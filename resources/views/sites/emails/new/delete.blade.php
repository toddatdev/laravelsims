@extends('backend.layouts.app')

@section ('title', 'Delete Email')

@section('page-header')
    <div class="row">
        <div class="col-lg-6">
            <h4>{{ trans('navs.frontend.site-emails.delete') }}</h4>
        </div><!-- /.col -->
        <div class="col-lg-6">
            <ol class="breadcrumb float-sm-right">
                @if (app('request')->input('type') == 2)
                    <li class="breadcrumb-item">{{ link_to_route('emails.index', trans('navs.frontend.course-emails.manage_course'), ['type' => app('request')->input('type')], ['class' => '']) }}</li>
                    <li class="breadcrumb-item active">{{ trans('navs.frontend.course-emails.delete_email') }}</li>
                @elseif (app('request')->input('type') == 3)
                    <li class="breadcrumb-item">{{ link_to_route('emails.index', trans('navs.frontend.event-emails.manage_event'), ['type' => app('request')->input('type')], ['class' => '']) }}</li>
                    <li class="breadcrumb-item active">{{ trans('navs.frontend.event-emails.delete_email') }}</li>
                @endif
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('content')
    {!! Form::model($email, ['class' => 'form-horizontal','method' => 'POST', 'action' => ['Site\SiteEmailsController@remove']]) !!}
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title" id="heading">{{$email->label}}</h3>
                    </div>
                    <div class="card-body">

                        {{ Form::hidden('site_email_id', $email->id) }}

                        <div class="form-group row">
                            {{-- Option 1 --}}
                            @if(isset($eventEmail))
                                {!! Form::label('option_1', trans('labels.eventEmails.only_delete_template'), ['class' => 'text-md-right col-5 control-label']) !!}
                            @else
                                {!! Form::label('option_1', trans('labels.eventEmails.only_delete_template'), ['class' => 'text-md-right col-5 control-label']) !!}
                            @endif
                            <div class="col-7">
                                <div class="row">
                                    <div class="col-1">
                                        {{ Form::radio('option', '1', false, ['class' => 'form-radio-input']) }}
                                    </div>
                                    <div class="col-11">
                                        {{ Form::label('option_1-msg', trans('labels.eventEmails.only_delete_template_msg'), ['class' => 'radio-inline']) }}
                                    </div>
                                </div>
                            </div>

                            {{-- Option 2 --}}
                            @if(isset($eventEmail))
                                {!! Form::label('option_2', trans('labels.eventEmails.unedited_course_emails', ['course_count'=>$uneditCount, 'event_count'=>$uneditEventCount]), ['class' => 'text-md-right col-5 control-label']) !!}
                            @else
                                {!! Form::label('option_2', trans('labels.courseEmails.unedited_course_emails', ['course_count'=>$uneditCount]), ['class' => 'text-md-right col-5 control-label']) !!}
                            @endif
                            <div class="col-7">
                                <div class="row">
                                    <div class="col-1">
                                        {{ Form::radio('option', '2', false, ['class' => 'form-radio-input']) }}
                                    </div>
                                    <div class="col-11">
                                        {{ Form::label('option_2-msg', trans('labels.eventEmails.unedited_course_emails_msg'), ['class' => 'radio-inline']) }}
                                    </div>
                                </div>
                            </div>

                            {{-- Option 3 --}}
                            @if(isset($eventEmail))
                                {!! Form::label('option_3', trans('labels.eventEmails.delete_all', ['all_count'=>$allCount, 'all_event_count'=>$allEventCount]), ['class' => 'text-md-right col-5 control-label']) !!}
                            @else
                                {!! Form::label('option_3', trans('labels.courseEmails.delete_all', ['course_count'=>$allCount]), ['class' => 'text-md-right col-5 control-label']) !!}
                            @endif
                            <div class="col-7">
                                <div class="row">
                                    <div class="col-1">
                                        {{ Form::radio('option', '3', false, ['class' => 'form-radio-input']) }}
                                    </div>
                                    <div class="col-11">
                                        {{ Form::label('option_3-msg', trans('labels.eventEmails.delete_all_msg'), ['class' => 'radio-inline']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Type for Return --}}
                        {{ Form::hidden('type', app('request')->input('type')) }}
                    </div>
                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('emails.index', trans('buttons.general.cancel'), ['type' => app('request')->input('type')], ['class' => 'btn btn-warning btn-md']) }}
                        </div>
                        <div class="float-right">
                            {{ Form::submit('Delete', ['class' => 'btn btn-success btn-md']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection