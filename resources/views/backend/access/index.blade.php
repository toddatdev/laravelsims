@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.users.management'))

@section('after-styles')
@endsection

@section('page-header')
    <h4>
        {{ trans('labels.backend.access.users.management') }}
    </h4>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('labels.backend.access.users.active') }}</h3>
                        <div class="float-right">
                            @include('backend.access.includes.partials.user-header-buttons')
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="users-table" class="table dt-responsive nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th data-priority="1">{{ trans('labels.backend.access.users.table.last_name') }}</th>
                                        <th data-priority="2">{{ trans('labels.backend.access.users.table.first_name') }}</th>
                                        <th>{{ trans('labels.backend.access.users.table.email') }}</th>
                                        <th>{{ trans('labels.backend.access.users.table.confirmed') }}</th>
                                        <th>{{ trans('labels.backend.access.users.table.roles') }}</th>
                                        <th data-priority="3">{{ trans('labels.general.actions') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!--table-responsive-->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div>
        </div>
    </section>

{{-- Hiding the Recent history for now -jl 2019-07-17 15:36 --}}
{{--     <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('history.backend.recent_history') }}</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            {!! history()->renderType('User') !!}
        </div><!-- /.box-body -->
    </div><!--box box-success-->
 --}}
@endsection

@section('after-scripts')

    <script>
        $(function () {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("admin.access.user.get") }}',
                    type: 'post',
                    data: {status: 1, trashed: false}
                },
                columns: [
                    {data: 'last_name', name: '{{config('access.users_table')}}.last_name'},
                    {data: 'first_name', name: '{{config('access.users_table')}}.first_name'},
                    {data: 'email', name: '{{config('access.users_table')}}.email'},
                    {data: 'confirmed', name: '{{config('access.users_table')}}.confirmed'},
                    {data: 'roles', name: '{{config('access.roles_table')}}.name', sortable: false},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false}
                ],
                order: [[0, "asc"],[1, "asc"]],
                searchDelay: 500,

            });
        });
    </script>
@endsection
