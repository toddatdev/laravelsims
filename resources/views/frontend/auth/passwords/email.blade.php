@extends('frontend.layouts.public')

@section('content')

    <section class="content">

        {{ Form::open(['route' => 'frontend.auth.password.email.post', 'class' => 'form-horizontal']) }}

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

                    <div class="input-group mb-3">
                        {{ Form::email('email', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.frontend.email')]) }}
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row text-right">
                        <div class="col-lg-12">
                            {{ Form::submit(trans('labels.frontend.passwords.send_password_reset_link_button'), ['class' => 'btn btn-primary']) }}
                        </div>
                    </div>

                    {{ Form::close() }}

                </div><!-- card body -->
            </div><!-- card -->
        </div><!-- row -->
    </section>
@endsection