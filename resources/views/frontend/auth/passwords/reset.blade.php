@extends('frontend.layouts.public')

@section('content')

    <section class="content">
        {{ Form::open(['route' => 'frontend.auth.password.reset', 'class' => 'form-horizontal']) }}

        <div class="row justify-content-center">

                <div class="card mt-20" style="width: 600px;">

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="card-header">
                        <h3 class="card-title">{{ trans('labels.frontend.passwords.reset_password_box_title') }}</h3>
                    </div>

                    <div class="card-body">

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            {{ Form::label('email', trans('validation.attributes.frontend.email'), ['class' => 'col-md-4 control-label']) }}
                            <div class="col-md-6">
                                <p class="form-control-static">{{ $email }}</p>
                                {{ Form::hidden('email', $email, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.frontend.email')]) }}
                            </div><!--col-md-6-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ Form::label('password', trans('validation.attributes.frontend.password'), ['class' => 'col-md-4 control-label']) }}
                            <div class="col-md-6">
                                {{ Form::password('password', ['class' => 'form-control', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.frontend.password')]) }}
                            </div><!--col-md-6-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ Form::label('password_confirmation', trans('validation.attributes.frontend.password_confirmation'), ['class' => 'col-md-4 control-label']) }}
                            <div class="col-md-6">
                                {{ Form::password('password_confirmation', ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.password_confirmation')]) }}
                            </div><!--col-md-6-->
                        </div><!--form-group-->

                        <div class="form-group row text-right">
                            <div class="col-md-10">
                                {{ Form::submit(trans('labels.frontend.passwords.reset_password_button'), ['class' => 'btn btn-primary']) }}
                            </div><!--col-md-6-->
                        </div><!--form-group-->

                    </div><!-- card body -->
                </div><!-- card -->
            </div><!-- col-md-8 -->

    {{ Form::close() }}
    </section>
@endsection
