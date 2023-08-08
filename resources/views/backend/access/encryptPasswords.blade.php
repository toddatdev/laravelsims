@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.users.management') . ' | ' . trans('labels.backend.access.users.edit'))

@section('page-header')
    <h4>
        {{ trans('labels.backend.access.users.management') }}
    </h4>
@endsection

@section('content')
    <section class="content">

        {{ Form::open(['route' => ['admin.access.user.encrypt-password.post'], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'patch']) }}

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Encrypt Passwords</h3>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            {{ Form::label('start_id', 'Starting ID', ['class' => 'col-lg-2 control-label text-md-right']) }}
                            <div class="col-lg-1">
                                {{ Form::number('start_id', null, ['class' => 'form-control', 'required' => 'required', 'min' => '0']) }}
                            </div><!--col-lg-10-->
                        </div>
                        <div class="form-group row">
                            {{ Form::label('end_id', 'Ending ID', ['class' => 'col-lg-2 control-label text-md-right']) }}
                            <div class="col-lg-1">
                                {{ Form::number('end_id', null, ['class' => 'form-control', 'required' => 'required', 'min' => '0']) }}
                            </div><!--col-lg-10-->
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('admin.access.user.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
                        </div><!--pull-left-->
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-md']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}

    </section>

@endsection

@section('after-scripts')
@endsection
