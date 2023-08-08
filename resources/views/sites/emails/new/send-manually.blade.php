@extends('backend.layouts.app')

@section ('title', trans('navs.frontend.site-emails.send_manually'))

@section('page-header')
    <div class="row">
        <div class="col-lg-6">
            <h4>{{ trans('navs.frontend.site-emails.send_manually') }}</h4>
        </div><!-- /.col -->
        <div class="col-lg-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">{{ link_to_route('emails.index', trans('navs.frontend.site-emails.manage_site'), ['type' => app('request')->input('type')], ['class' => '']) }}</li>
                <li class="breadcrumb-item active">{{ trans('navs.frontend.site-emails.send_manually') }}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}
            </strong>
        </div>
    @endif

@endsection

@section('content')
    {!! Form::model($email, ['class' => 'form-horizontal','method' => 'POST', 'action' => ['Site\SiteEmailsController@sendNow']]) !!}
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title" id="heading">{{$email->label}}</h3>
                    </div>
                    <div class="card-body">
                        {{-- Hidden Site Email ID --}}
                        {{ Form::hidden('site_email_id', $email->id) }}

                        <!-- Email Subject -->
                        <div class="form-group row">
                            {!! Form::label('subject', trans('labels.siteEmails.subject'), ['class' => 'text-lg-right col-lg-2 control-label required']) !!}
                            <div class="col-lg-10">
                                {!! Form::input('subject', 'subject', null, ['id' => 'subject', 'class' => 'form-control', 'placeholder' => trans('labels.siteEmails.subject')]) !!}
                            </div>
                        </div>

                        <!-- Email Body -->
                        <div class="form-group row">
                            {!! Form::label('body', trans('labels.siteEmails.body'), ['class' => 'text-lg-right col-lg-2 control-label required']) !!}
                            <div class="col-lg-10">
                                {{ Form::textarea('body', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <!-- TO -->
                        <div class="form-group row">
                            {{ Form::label('to_roles', trans('labels.siteEmails.to'), ['class' => 'text-lg-right col-lg-2 control-label']) }}
                            <div class="col-lg-3">
                                {{ Form::select('to_roles[]', $permissions_types, null, ['class' => 'form-control', 'multiple']) }}
                            </div>
                            {{ Form::label('to_other', trans('labels.siteEmails.other_to'), ['class' => 'col-lg-2 text-lg-right control-label']) }}
                            <div class="col-lg-5">
                                {{ Form::input('to_other', 'to_other', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <!-- CC -->
                        <div class="form-group row">
                            {{ Form::label('cc_roles', trans('labels.siteEmails.cc'), ['class' => 'text-lg-right col-lg-2 control-label']) }}
                            <div class="col-lg-3">
                                {{ Form::select('cc_roles[]', $permissions_types, null, ['class' => 'form-control', 'multiple']) }}
                            </div>
                            {{ Form::label('cc_other', trans('labels.siteEmails.other_cc'), ['class' => 'col-lg-2 text-lg-right control-label']) }}
                            <div class="col-lg-5">
                                {{ Form::input('cc_other', 'cc_other', null, ['class' => 'form-control']) }}
                            </div>
                        </div>
        
                        <!-- BCC -->
                        <div class="form-group row">
                            {{ Form::label('bcc_roles', trans('labels.siteEmails.bcc'), ['class' => 'text-lg-right col-lg-2 control-label']) }}
                            <div class="col-lg-3">
                                {{ Form::select('bcc_roles[]', $permissions_types, null, ['class' => 'form-control', 'multiple']) }}
                            </div>
                            {{ Form::label('bcc_other', trans('labels.siteEmails.other_bcc'), ['class' => 'col-lg-2 text-lg-right control-label']) }}
                            <div class="col-lg-5">
                                {{ Form::input('bcc_other', 'bcc_other', null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('emails.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-sm']) }}
                        </div>
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.backend.siteEmails.send_now'), ['class' => 'btn btn-success btn-sm']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {!! Form::close() !!}

    {{-- TINY MCE V5 --}}
    {{ Html::script("/js/tinymce/tinymce.min.js") }}

    {{-- jQuery --}}
    {{ Html::script("js/jquery.js") }}

    <script type="text/javascript">
        $(function () {
            // Subject tinyMCE
            tinymce.init({
                selector: "#subject",
                menubar: false,
                browser_spellcheck: true,
                toolbar: 'undo redo',
                statusbar: false,
                inline: false,
                height: "100",
                resize: false,
                formats: {
                    // Changes the default format for h1 to have a class of heading
                    h1: { block: 'h1', classes: 'heading' }
                },
                setup: function (editor) {
                    editor.on('keydown', function(e) {
                        if (e.keyCode == 13) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }
                    });
                },
            });

            // Body tinyMCE
            tinymce.init({
                selector: "textarea",
                menubar: false,
                browser_spellcheck: true,
                toolbar: 'undo redo |  formatselect | bold italic underline backcolor forecolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link',
                statusbar: false,
                plugins: [
                    'link',
                ],
            });
        });
    </script>
@endsection