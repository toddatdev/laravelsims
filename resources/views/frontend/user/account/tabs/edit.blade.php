{{ Form::model($logged_in_user, ['route' => 'frontend.user.profile.update', 'role'=>'form', 'class' => 'form-horizontal', 'method' => 'PATCH', 'files' => true, 'enctype' => 'multipart/form-data']) }}

    <div class="form-group row">
        {{ Form::label('first_name', trans('validation.attributes.frontend.first_name'),
        ['class' => 'col-md-4 col-xs-12 control-label required text-md-right']) }}
        <div class="col-md-6">
            {{ Form::text('first_name', null,
            ['class' => 'form-control', 'required' => 'required', 'autofocus' => 'autofocus', 'maxlength' => '30', 'placeholder' => trans('validation.attributes.frontend.first_name')]) }}
        </div>
    </div>

    <div class="form-group row">
        {{ Form::label('middle_name', trans('validation.attributes.frontend.middle_name'),
        ['class' => 'col-md-4 control-label text-md-right']) }}
        <div class="col-md-6">
            {{ Form::text('middle_name', null,
            ['class' => 'form-control', 'autofocus' => 'autofocus', 'maxlength' => '30', 'placeholder' => trans('validation.attributes.frontend.middle_name')]) }}
        </div>
    </div>

    <div class="form-group row">
        {{ Form::label('last_name', trans('validation.attributes.frontend.last_name'),
        ['class' => 'col-md-4 col-xs-12 control-label required text-md-right']) }}
        <div class="col-md-6">
            {{ Form::text('last_name', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '50', 'placeholder' => trans('validation.attributes.frontend.last_name')]) }}
        </div>
    </div>

    <div class="form-group row">
        {{ Form::label('phone', trans('validation.attributes.frontend.phone'), ['class' => 'col-md-4 control-label text-md-right']) }}
        <div class="col-md-4">
            {{ Form::text('phone', null, ['class' => 'form-control', 'maxlength' => '50', 'placeholder' => trans('validation.attributes.frontend.phone')]) }}
        </div>

        <div class="col-md-4">
            <!-- modal button -->
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#phoneModal">{{ trans('validation.attributes.frontend.why') }}</button>
            <!-- modal -->
            <div class="modal fade" id="phoneModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">{{ trans('validation.attributes.frontend.why_phone_ques') }}</h4>
                        </div>
                        <div class="modal-body">
                            <p>{!! trans('validation.attributes.frontend.why_phone_answer', ['site_abbrv' => Session::get('site_abbrv'), 'site_email' => Session::get('site_email') ]) !!}</p>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        {{ Form::label('', trans('validation.attributes.frontend.profile_picture'), ['class' => 'col-md-4 control-label text-md-right']) }}
        <div class="col-md-4">
            <div class="input-group">
                <label class="input-group-btn">
                    <span class="btn btn-primary">
                        Browse&hellip; {{ Form::file('image', ['style' => 'display:none']) }}
                    </span>
                </label>
                <input type="text" id="filename" class="form-control" readonly>
            </div>
            <small>Max file size is 2 megabytes (jpeg, png, jpg, gif, svg). </small>
        </div>
    </div>


    @if ($logged_in_user->canChangeEmail())
        <div class="form-group row">
            {{ Form::label('email', trans('validation.attributes.frontend.email'), ['class' => 'col-md-4 control-label required text-md-right']) }}
            <div class="col-md-6">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> {{  trans('strings.frontend.user.change_email_notice') }}
                </div>

                {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '191', 'placeholder' => trans('validation.attributes.frontend.email')]) }}
            </div>
        </div>
    @endif

    <div class="form-group row">
        <div class="col-md-6 col-md-offset-4">
            {{ Form::submit(trans('labels.general.buttons.update'), ['class' => 'btn btn-primary', 'id' => 'update-profile']) }}
        </div>
    </div>

{{ Form::close() }}

{{-- if site is set up so that users can change their own emails, let them know to contact their admins --}}
@if (!$logged_in_user->canChangeEmail())
    <div class="card text-center">
        <div class="card-body">
            <h5 class="card-title">{{trans('validation.attributes.frontend.email')}} :</h5>
            <div class="card-text">If you need to change your email, please contact your program's administration</div>
        </div>
    </div>
@endif

@section('after-scripts')
@parent
    <script>

        $(function() {

            // attach the `fileselect` event to all file inputs on the page, then log to input group
            $(document).on('change', ':file', function() {
                var input = $(this),
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [label]);
            });

            $(':file').on('fileselect', function(event, label) {
                var input = $(this).parents('.input-group').find(':text'),
                    log = label;
                if( input.length ) {
                    input.val(log);
                }
            });
            
        });

    </script>
@endsection