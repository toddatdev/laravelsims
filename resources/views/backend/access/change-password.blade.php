@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.users.management') . ' | ' . trans('labels.backend.access.users.change_password'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.access.users.management') }}
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => ['admin.access.user.change-password.post', $user], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'patch']) }}
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('labels.backend.access.users.change_password_for', ['user' => $user->name]) }}</h3>
                        <div class="float-right">
                            @include('backend.access.includes.partials.user-header-buttons')
                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">

                        <div class="form-group row">
                            {{ Form::label('password', trans('validation.attributes.backend.access.users.password'), ['class' => 'col-lg-2 control-label text-md-right', 'placeholder' => trans('validation.attributes.backend.access.users.password')]) }}

                            <div class="col-lg-10">
                                {{ Form::password('password', ['class' => 'form-control', 'required' => 'required', 'autofocus' => 'autofocus']) }}
                            </div><!--col-lg-10-->
                        </div><!--form control-->

                        <div class="form-group row">
                            {{ Form::label('password_confirmation', trans('validation.attributes.backend.access.users.password_confirmation'), ['class' => 'col-lg-2 control-label text-md-right', 'placeholder' => trans('validation.attributes.backend.access.users.password_confirmation')]) }}

                            <div class="col-lg-10">
                                {{ Form::password('password_confirmation', ['class' => 'form-control', 'required' => 'required']) }}
                            </div><!--col-lg-10-->
                        </div><!--form control-->
                    </div><!-- /.card-body -->
                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('admin.access.user.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
                        </div><!--pull-left-->
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-md']) }}
                        </div><!--pull-right-->
                    </div><!-- /.card-footer -->
                </div><!-- /.card -->
            </div>
        </div>
    </section>
    {{ Form::close() }}
@endsection
