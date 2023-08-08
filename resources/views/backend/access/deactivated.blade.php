@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.users.management') . ' | ' . trans('labels.backend.access.users.deactivated'))

@section('after-styles')
@endsection

@section('page-header')
    <h4>
        {{ trans('labels.backend.access.users.management') }}
    </h4>
@endsection

@section('content')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('labels.backend.access.users.deactivated') }}</h3>
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
                                    <th>{{ trans('labels.backend.access.users.table.created') }}</th>
                                    <th>{{ trans('labels.backend.access.users.table.last_updated') }}</th>
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
@endsection

@section('after-scripts')

    <script>
        $(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("admin.access.user.get") }}',
                    type: 'post',
                    data: {status: 0, trashed: false}
                },
                columns: [
                    {data: 'last_name', name: '{{config('access.users_table')}}.last_name'},
                    {data: 'first_name', name: '{{config('access.users_table')}}.first_name'},
                    {data: 'email', name: '{{config('access.users_table')}}.email'},
                    {data: 'confirmed', name: '{{config('access.users_table')}}.confirmed'},
                    {data: 'roles', name: '{{config('access.roles_table')}}.name', sortable: false},
                    {data: 'created_at', name: '{{config('access.users_table')}}.created_at'},
                    {data: 'updated_at', name: '{{config('access.users_table')}}.updated_at'},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false}
                ],
                order: [[1, "asc"], [2, "asc"]],
                searchDelay: 500,

            });
        });

        //popovers on FA images
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover();
        });
    </script>
@endsection
