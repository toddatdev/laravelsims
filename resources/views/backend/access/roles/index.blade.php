@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.roles.management'))

@section('after-styles')
@endsection

@section('page-header')
    <h1>{{ trans('labels.backend.access.roles.management') }}</h1>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 id="heading" class="card-title">{{ trans('labels.backend.access.roles.management') }}</h3>
                        @php($create = true)
                        <div class="float-right">
                            @include('backend.access.includes.partials.role-header-buttons')
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="roles-table" class="table table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ trans('labels.backend.access.roles.table.role') }}</th>
                                        <th>{{ trans('labels.backend.access.roles.table.permissions') }}</th>
                                        <th>{{ trans('labels.backend.access.roles.table.number_of_users') }}</th>
                                        <th>{{ trans('labels.general.actions') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!--table-responsive-->
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Hiding Recent History for now -jl 2019-07-17 15:37 --}}
{{--     <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('history.backend.recent_history') }}</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            {!! history()->renderType('Role') !!}
        </div><!-- /.box-body -->
    </div><!--box box-success-->
 --}}
    @if(Session::has('type'))
        <input type="hidden" id="role_type" value ="{{Session::get('type')}}">
    @endif
    
@endsection


@section('after-scripts')

    <script>

        var dt;

        $(function() {

            // GET view= from url, if exists, display that role type's datatable
            // 1 = site, 2 = course, 3 = event
            let role_type = "{{ app('request')->input('type') }}";
                        
            if (role_type == '') {
                role_type = $('#role_type').val();
            }

            if (role_type == '1') {
                siteRoles();
            } else if (role_type == '2') {
                courseRoles();
            } else if (role_type == '3') {
                eventRoles();
            } else {
                role_type = '1';
                siteRoles(); // default site roles
            }

            // first id is normal buttons, second is mobile
            $('#site, #site-mobile').click(function(e) {
                e.preventDefault();
                role_type = '1';
                siteRoles();
            });

            $('#course, #course-mobile').click(function(e) {
                e.preventDefault();
                role_type = '2';
                courseRoles();
            });

            $('#event, #event-mobile').click(function(e) {
                e.preventDefault();
                role_type = '3';
                eventRoles();
            });


            function siteRoles() {

                goTo('site', 'Site Role Management', '/admin/access/role?type='+role_type);
                $('#heading').text("Site Role Management");
                if (!dt) {
                    init();
                } else {
                    dt.clear();
                    dt.destroy();
                    dt = null;
                    init();
                }
            }

            function courseRoles() {
                goTo('course', 'Course Role Management', '/admin/access/role?type='+role_type);
                $('#heading').text("Course Role Management");
                if (!dt) {
                    init();
                } else {
                    dt.clear();
                    dt.destroy();
                    dt = null;
                    init();
                }
            }

            function eventRoles() {
                goTo('event', 'Event Role Management', '/admin/access/role?type='+role_type);
                $('#heading').text("Event Role Management");
                if (!dt) {
                    init();
                } else {
                    dt.clear();
                    dt.destroy();
                    dt = null;
                    init();
                }
            }

            // initialize the roles datatables
            function init() {
                $('#create-role').attr("href", 'role/create/'+role_type);
                dt = $('#roles-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{!! url("roleTable.data") !!}',
                        data: function (d) {
                            d.type = role_type;
                        }
                    },
                    language: {search: "", searchPlaceholder: "Search..."},
                    lengthMenu: [ [25, 50, -1], [25, 50, "All"] ],
                    buttons: ['excelHtml5', 'pdf', 'copy'],

                    //Set the format for table and surrounding functionality
                    dom: '<"top"fB>rt<"bottom"lp><"clear">',

                    columns: [
                        {data: 'name', name: '{{config('access.roles_table')}}.name'},
                        {data: 'permissions', name: '{{config('access.permissions_table')}}.display_name', sortable: false},
                        {data: 'users', name: 'users', searchable: false, sortable: false},
                        {data: 'actions', name: 'actions', searchable: false, sortable: false}
                    ],
                    order: [[3, "asc"]],
                    searchDelay: 500
                });
            }


            // goto function allows history to be displayed when clicking around the ajax types - that way links can be preserved.
            function goTo(page, title, url) {
            if ("undefined" !== typeof history.pushState) {
                history.pushState({page: page}, title, url);
            } else {
                window.location.assign(url);
            }
            }


        });
    </script>
@endsection