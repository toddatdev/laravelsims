@extends('frontend.layouts.public')

@section('content')
    {{ Form::open(['route' => 'frontend.auth.login.post', 'class' => 'form-horizontal']) }}
    <section class="content">
        <div class="row justify-content-center">
            <div class="card mt-20" style="width: 600px;">
                <div class="card-header">
                    <h3 class="card-title">{{ trans('labels.frontend.auth.login_box_title') }}</h3>
                </div>
                <!-- /.card-header -->

                <div class="card-body">

                    <div class="input-group mb-3">
                        {{ Form::email('email', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.frontend.email')]) }}
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        {{ Form::password('password', ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.password')]) }}
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <label for="remember">
                                    {{ Form::checkbox('remember') }} {{ trans('labels.frontend.auth.remember_me') }}
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4 text-right">
                            {{ Form::submit(trans('labels.frontend.auth.login_button'), ['class' => 'btn btn-primary']) }}
                        </div>
                        <!-- /.col -->
                    </div>
                    <div class="row row text-center">
                        <div class="col-12">
                            {{ link_to_route('frontend.auth.password.reset', trans('labels.frontend.passwords.forgot_password')) }}
                        </div>
                        <div class="col-12">
                        <br><button type="button" class="btn btn-link btn-sm" data-toggle="modal" data-target="#privacy_policy">Privacy Policy</button>
                        </div>
                    </div>

                    <!-- Modal for the Privacy Policy-->
                    <div class="modal fade" id="privacy_policy">
                      <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="modal-title">Privacy Policy</h4>
                              <button type="button" class="close" data-dismiss="modal">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          <div class="modal-body">
                            {!! Storage::disk('s3')->get('/site-'.SESSION::get('site_id').'/privacy-policy.html') !!}
                          </div>
                          <div class="modal-footer justify-content-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>

                      </div>
                    </div> <!-- end of the Modal-->
                </div>
            </div>
        </div>

        <input type="hidden" name="timezone" id="timezone">
        {{ Form::close() }}

    </section><!-- row -->

@endsection

@section('after-scripts')

    <script>
        $(document).ready(function () {
            var timezone = moment.tz.guess();
            $('#timezone').val(timezone);
        });
    </script>




@endsection