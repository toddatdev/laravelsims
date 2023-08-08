@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.users.management') . ' | ' . trans('labels.backend.access.users.create'))

@section('page-header')
    <h4>
        {{ trans('labels.backend.access.users.management') }}
    </h4>
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.access.user.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post']) }}
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('labels.backend.access.users.create') }}</h3>
                        <div class="float-right">
                            @include('backend.access.includes.partials.user-header-buttons')
                        </div>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="form-group row">
                            {{ Form::label('first_name', trans('validation.attributes.backend.access.users.first_name'), ['class' => 'col-lg-2 control-label text-md-right required']) }}

                            <div class="col-lg-10">
                                {{ Form::text('first_name', null, ['class' => 'form-control', 'maxlength' => '50', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.backend.access.users.first_name')]) }}
                            </div><!--col-lg-10-->
                        </div><!--form control-->

                        <div class="form-group row">
                            {{ Form::label('middle_name', trans('validation.attributes.backend.access.users.middle_name'),
                             ['class' => 'col-lg-2 control-label text-md-right']) }}

                            <div class="col-lg-10">
                                {{ Form::text('middle_name', null, ['class' => 'form-control', 'maxlength' => '50', 'placeholder' => trans('validation.attributes.backend.access.users.middle_name')]) }}
                            </div><!--col-lg-10-->
                        </div><!--form control-->

                        <div class="form-group row">
                            {{ Form::label('last_name', trans('validation.attributes.backend.access.users.last_name'),
                             ['class' => 'col-lg-2 control-label text-md-right required']) }}

                            <div class="col-lg-10">
                                {{ Form::text('last_name', null, ['class' => 'form-control', 'maxlength' => '50', 'required' => 'required', 'placeholder' => trans('validation.attributes.backend.access.users.last_name')]) }}
                            </div><!--col-lg-10-->
                        </div><!--form control-->

                        <div class="form-group row">
                            {{ Form::label('phone', trans('validation.attributes.backend.access.users.phone'), ['class' => 'col-lg-2 control-label text-md-right']) }}

                            <div class="col-lg-10">
                                {{ Form::text('phone', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.users.phone')]) }}
                            </div><!--col-lg-10-->
                        </div><!--form control-->

                        <div class="form-group row">
                            {{ Form::label('email', trans('validation.attributes.backend.access.users.email'), ['class' => 'col-lg-2 control-label required text-md-right']) }}

                            <div class="col-lg-10">
                                {{ Form::email('email', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'placeholder' => trans('validation.attributes.backend.access.users.email')]) }}
                            </div><!--col-lg-10-->
                        </div><!--form control-->

                        <div class="form-group row">
                            {{ Form::label('password', trans('validation.attributes.backend.access.users.password'), ['class' => 'col-lg-2 control-label required text-md-right']) }}

                            <div class="col-lg-10">
                                {{ Form::password('password', ['class' => 'form-control required', 'required' => 'required', 'placeholder' => trans('validation.attributes.backend.access.users.password')]) }}
                            </div><!--col-lg-10-->
                        </div><!--form control-->

                        <div class="form-group row">
                            {{ Form::label('password_confirmation', trans('validation.attributes.backend.access.users.password_confirmation'), ['class' => 'col-lg-2 control-label required text-md-right']) }}

                            <div class="col-lg-10">
                                {{ Form::password('password_confirmation', ['class' => 'form-control required', 'required' => 'required', 'placeholder' => trans('validation.attributes.backend.access.users.password_confirmation')]) }}
                            </div><!--col-lg-10-->
                        </div><!--form control-->

                        <div class="form-group row">
                            {{ Form::label('status', trans('validation.attributes.backend.access.users.active'), ['class' => 'col-lg-2 control-label text-md-right']) }}

                            <div class="col-lg-1">
                                {{ Form::checkbox('status', '1', true) }}
                            </div><!--col-lg-1-->
                        </div><!--form control-->

                        <div class="form-group row">
                            {{ Form::label('confirmed', trans('validation.attributes.backend.access.users.confirmed'), ['class' => 'col-lg-2 control-label text-md-right']) }}

                            <div class="col-lg-1">
                                {{ Form::checkbox('confirmed', '1', true) }}
                            </div><!--col-lg-1-->
                        </div><!--form control-->

                        <div class="form-group row">
                            <label class="col-lg-2 control-label text-md-right">{{ trans('validation.attributes.backend.access.users.send_confirmation_email') }}<br/>
                                <small>{{ trans('strings.backend.access.users.if_confirmed_off') }}</small>
                            </label>

                            <div class="col-lg-1">
                                {{ Form::checkbox('confirmation_email', '1') }}
                            </div><!--col-lg-1-->
                        </div><!--form control-->

                        <div class="form-group row">
                             {{ Form::label('associated_roles', trans('validation.attributes.backend.access.users.associated_roles'), ['class' => 'text-md-right col-lg-2 control-label']) }}

                            <div class="col-lg-8">
                                @if (count($roles) > 0)
                                    @foreach($roles as $role)
                                        <div><input type="checkbox" value="{{ $role->id }}" name="assignees_roles[{{ $role->id }}]" id="role-{{ $role->id }}" {{ is_array(old('assignees_roles')) && in_array($role->id, old('assignees_roles')) ? 'checked' : '' }} /> <label for="role-{{ $role->id }}">{{ $role->name }}</label>
                                        <a href="#" data-role="role_{{ $role->id }}" class="show-permissions small">
                                            (
                                                <span class="show-text">{{ trans('labels.general.show') }}</span>
                                                <span class="hide-text d-none">{{ trans('labels.general.hide') }}</span>
                                                {{ trans('labels.backend.access.users.permissions') }}
                                            )
                                        </a></div>

                                        <div class="permission-list d-none callout" data-role="role_{{ $role->id }}">
                                            @if ($role->all)
                                                {{ trans('labels.backend.access.users.all_permissions') }}<br/><br/>
                                            @else
                                                @if (count($role->permissions) > 0)
                                                    <div class="small">
                                                    @foreach ($role->permissions as $perm)
                                                        {{ trans('labels.permissions.'. $perm->name) }}<br/>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    {{ trans('labels.backend.access.users.no_permissions') }}<br/><br/>
                                                @endif
                                            @endif
                                        </div><!--permission list-->
                                    @endforeach
                                @else
                                    {{ trans('labels.backend.access.users.no_roles') }}
                                @endif
                            </div><!--col-lg-3-->
                        </div><!--form control-->
                    </div><!-- /.card-body -->
                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('admin.access.user.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
                        </div><!--pull-left-->
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-md']) }}
                        </div><!--pull-right-->
                    </div><!-- /.card-footer -->
                </div><!-- /.card -->
            </div>
        </div>
    </section>
    {{ Form::close() }}
@endsection

@section('after-scripts')
    {{ Html::script('js/backend/access/users/script.js') }}
@endsection
