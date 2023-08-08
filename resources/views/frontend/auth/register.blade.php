@extends('frontend.layouts.public')

@section('content')
    <section class="content">
        {{ Form::open(['route' => 'frontend.auth.register.post', 'class' => 'form-horizontal', 'files' => true]) }}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('navs.frontend.register') }}</h3>
                    </div>

                    <div class="card-body">

                        <div class="form-group row">
                            {{ Form::label('first_name', trans('validation.attributes.frontend.first_name'),
                            ['class' => 'col-lg-2 control-label required text-lg-right']) }}
                            <div class="col-lg-6">
                                {{ Form::text('first_name', null,
                                ['class' => 'form-control', 'maxlength' => '30', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.frontend.first_name')]) }}
                            </div>
                        </div>

                        <div class="form-group row">
                            {{ Form::label('middle_name', trans('validation.attributes.frontend.middle_name'),
                            ['class' => 'col-lg-2 control-label text-lg-right']) }}
                            <div class="col-lg-6">
                                {{ Form::text('middle_name', null,
                                ['class' => 'form-control', 'maxlength' => '30', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.frontend.middle_name')]) }}
                            </div>
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ Form::label('last_name', trans('validation.attributes.frontend.last_name'),
                            ['class' => 'col-lg-2 control-label required text-lg-right']) }}
                            <div class="col-lg-6">
                                {{ Form::text('last_name', null,
                                ['class' => 'form-control', 'maxlength' => '50', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.last_name')]) }}
                            </div>
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ Form::label('Phone', trans('validation.attributes.frontend.phone'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
                            <div class="col-lg-6">
                                {{ Form::input('phone', 'phone', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.frontend.phone')]) }}
                            </div>

                            <div class="col-lg-2">
                                <!-- Trigger the modal with a button -->
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#phoneModal">{{ trans('validation.attributes.frontend.why') }}</button>

                                <!-- Modal -->
                                <div class="modal fade" id="phoneModal" role="dialog">
                                <div class="modal-dialog">

                                  <!-- Modal content-->
                                  <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">{{ trans('validation.attributes.frontend.why_phone_ques') }}</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                      {{-- <p>Cuase we do....</p> --}}
                                      <p>{!! trans('validation.attributes.frontend.why_phone_answer', ['site_abbrv' => Session::get('site_abbrv'), 'site_email' => Session::get('site_email') ]) !!}</p>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('validation.attributes.frontend.close') }}</button>
                                    </div>
                                  </div>

                                </div>
                                </div>

                            </div>

                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ Form::label('', trans('validation.attributes.frontend.profile_picture'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <label class="input-group-btn">
                                        <span class="btn btn-primary">
                                            Browse&hellip; {{ Form::file('image', ['style' => 'display:none']) }}
                                        </span>
                                    </label>
                                    <input type="text" id="filename" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            {{ Form::label('email', trans('validation.attributes.frontend.email'), ['class' => 'col-lg-2 control-label required text-lg-right']) }}
                            <div class="col-lg-6">
                                {{ Form::email('email', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.email')]) }}
                            </div>
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ Form::label('password', trans('validation.attributes.frontend.password'), ['class' => 'col-lg-2 control-label required text-lg-right']) }}
                            <div class="col-lg-6">
                                {{ Form::password('password', ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.password')]) }}
                            </div>
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ Form::label('password_confirmation', trans('validation.attributes.frontend.password_confirmation'), ['class' => 'col-lg-2 control-label required text-lg-right']) }}
                            <div class="col-lg-6">
                                {{ Form::password('password_confirmation', ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.password_confirmation')]) }}
                            </div>
                        </div><!--form-group-->

                        @if (config('access.captcha.registration'))
                            <div class="form-group row">
                                <div class="col-lg-6 col-lg-offset-4">
                                    {!! Form::captcha() !!}
                                    {{ Form::hidden('captcha_status', 'true') }}
                                </div>
                            </div><!--form-group-->
                        @endif

                        <div class="form-group row">
                            <div class="col-lg-8 text-lg-right">
                                {{ Form::submit(trans('labels.frontend.auth.register_button'), ['class' => 'btn btn-primary']) }}
                            </div><!--col-md-6-->
                        </div><!--form-group-->

                    </div>

                    <div class="card-footer">
                        <!-- Privacy Policy -->
                        <div class="text-center">
                            <!-- Trigger the modal with a button -->
                            <button type="button" class="btn btn-link btn-lg" data-toggle="modal" data-target="#privacy_policy">Privacy Policy</button>

                            <!-- Modal for the Privacy Policy-->
                            <div class="modal fade" id="privacy_policy" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Privacy Policy</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body text-left">
                                            {{-- Pull the contents of the privacy policy from this sites Amazon S3 file --}}
                                            {!! Storage::disk('s3')->get('/site-'.SESSION::get('site_id').'/privacy-policy.html') !!}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>

                                </div>
                            </div> <!-- end of the Modal-->
                        </div>
                        <!-- End of the Privacy Policy -->
                    </div>

                </div>
            </div>
        </div>
        {{ Form::close() }}
    </section>

@endsection

@section('after-scripts')
    @if (config('access.captcha.registration'))
        {!! Captcha::script() !!}
    @endif

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