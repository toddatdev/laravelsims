@extends ('backend.layouts.app')
@section ('title', trans('labels.backend.access.roles.management') . ' | ' . trans('labels.backend.access.roles.create'))


@section('page-header')
    <h4>
        {{ trans('labels.backend.access.roles.management') }}
    </h4>
@endsection

@section('content')

    <section class="content">
        {{ Form::open(['route' => 'admin.access.role.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-role']) }}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        @if($type == '2')
                            <h3 class="card-title">{{ trans('labels.backend.access.roles.create_course') }}</h3>
                        @elseif($type == '3')
                            <h3 class="card-title">{{ trans('labels.backend.access.roles.create_event') }}</h3>
                        @else
                            <h3 class="card-title">{{ trans('labels.backend.access.roles.create_site') }}</h3>
                        @endif
                        <div class="float-right">
                            @include('backend.access.includes.partials.role-header-buttons')
                        </div>
                    </div>

                    <div class="card-body">

                        <!-- name -->
                        <div class="form-group row">
                            {{ Form::label('name', trans('labels.backend.access.roles.name'), ['class' => 'col-lg-2 control-label required']) }}
                            <div class="col-lg-10">
                                {{ Form::text('name', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.backend.access.roles.name')]) }}
                            </div>
                        </div>

                        {{--help url is decided by role type 1 = site, 2 = course, 3 = event--}}
                        @php
                            if($type == '2')
                            {
                                $helpButtonString = '<a rel="noopener noreferrer" target="_blank" href="https://sims.atlassian.net/wiki/spaces/S3/pages/4554757/Course+Permissions"><i class="fas fa-question-circle"></i></a>';
                            }
                            elseif($type == '3')
                            {
                                $helpButtonString = '<a rel="noopener noreferrer" target="_blank" href="https://sims.atlassian.net/wiki/spaces/S3/pages/4653071/Event+Permissions"><i class="fas fa-question-circle"></i></a>';
                            }
                            else
                            {
                                $helpButtonString = '<a rel="noopener noreferrer" target="_blank" href="https://sims.atlassian.net/wiki/spaces/S3/pages/4521990/Site+Permissions"><i class="fas fa-question-circle"></i></a>';
                            }
                        @endphp

                        <!-- permissions list -->
                        <div class="form-group row">
                            {{--form label last parameter is $escape_html which is set to false to allow html for $helpButtonString--}}
                            {{ Form::label('associated-permissions', trans('labels.backend.access.roles.associated_permissions'). ' ' . $helpButtonString, ['class' => 'col-lg-2 control-label required'], false) }}
                            <div class="col-lg-10">
                                <div id="available-permissions" class="mt-20">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            @if ($permissions->count())
                                                @foreach ($permissions as $perm)
                                                    <input type="checkbox" name="permissions[{{ $perm->id }}]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}" {{ is_array(old('permissions')) && in_array($perm->id, old('permissions')) ? 'checked' : '' }} />
                                                    <label for="perm_{{ $perm->id }}">{{ trans('labels.permissions.'. $perm->name) }}</label><br/>
                                                @endforeach
                                            @else
                                                <p>There are no available permissions.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    {{--When a checkbox is unchecked, no value is returned from the form, therefore on edit when it is
                       unchecked, nothing is changed.  To "fix" this a hidden field is added prior to the checkbox that will pass
                       a zero value when unchecked. This will also make the default value zero on create.--}}
                    {{ Form::hidden('learner',0) }}

                    {{--learner checkbox - only displayed for event roles--}}
                    @if($type == '3')

                        <div class="form-group row">
                            {{ Form::label('learner', trans('labels.backend.access.roles.learner'), ['class' => 'col-lg-2 control-label']) }}
                            <div class="col-lg-10">
                                @if( isset($role) )
                                    @if($role->learner == "1")
                                        {{ Form::checkbox('learner', 1, true, ['class' => 'form-check-input']) }}
                                    @else
                                        {{ Form::checkbox('learner', 1, false, ['class' => 'form-check-input']) }}
                                    @endif
                                @else
                                    {{ Form::checkbox('learner', 1, false, ['class' => 'form-check-input']) }}
                                @endif
                            </div><!--col-lg-10-->
                        </div><!--form-group-->

                    @endif

                    <!-- hidden sort and hidden type to pass on create , sort is a defunct feature that we now just auto increment here and never use -->
                    {{ Form::hidden('sort', $permissions->count()+1) }}
                    {{ Form::hidden('type', $type) }}

                    @if($type == '2')
                        <h4 class="text-center">Click <a rel="noopener noreferrer" target="_blank" href="https://sims.atlassian.net/wiki/spaces/S3/pages/4554757/Course+Permissions">here</a> for help on Course Permissions</h4>
                    @elseif ($type == '3')
                        <h4 class="text-center">Click <a rel="noopener noreferrer" target="_blank" href="https://sims.atlassian.net/wiki/spaces/S3/pages/4653071/Event+Permissions">here</a> for help on Event Permissions</h4>
                    @else
                        <h4 class="text-center">Click <a rel="noopener noreferrer" target="_blank" href="https://sims.atlassian.net/wiki/spaces/S3/pages/4521990/Site+Permissions">here</a> for help on Site Permissions</h4>
                    @endif
                </div>
                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('admin.access.role.index', trans('buttons.general.cancel'), ['type'=>$type], ['class' => 'btn btn-warning']) }}
                        </div>
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </section>
@endsection
