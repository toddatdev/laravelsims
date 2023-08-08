@extends('frontend.layouts.app')

@section ('title', trans('menus.backend.course.assign') . ' | ' . $course->name.' ('.$course->abbrv .')')

@section('before-styles')
    {{ Html::style("/css/jquery.typeahead.min.css") }}
@endsection

@section('page-header')

{{--    to setup session variable for breadcumbs--}}
    @if(strpos(url()->previous(), 'courseInstanceEmails') == false) {{-- do not reset when returning from credit or edit--}}
        @if (strpos(url()->previous(), 'mycourses') !== false)
            <?php session(['breadcrumbLevel1' => 'mycourses']); ?>
        @elseif (strpos(url()->previous(), '/courses/') !== false)
            <?php session(['breadcrumbLevel1' => 'courses']); ?>
        @endif
    @endif

    <div class="row">
        <div class="col-lg-9">
            <h4>{{trans('menus.backend.course.assign')}}</h4>
        </div><!-- /.col -->
        <div class="col-lg-3">
            <ol class="breadcrumb float-sm-right">
                @if (Session::get('breadcrumbLevel1') == 'mycourses')
                    <li class="breadcrumb-item">{{ link_to('/mycourses', trans('navs.frontend.course.my_courses'), ['class' => '']) }}</li>
                @elseif (Session::get('breadcrumbLevel1') == 'courses')
                    <li class="breadcrumb-item">{{ link_to('/courses/active?id='.$course->id, trans('menus.backend.course.title'), ['class' => '']) }}</li>
                @endif
                <li class="breadcrumb-item active">{{ $course->abbrv }}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('content')

    <section class="content">
        {{ Form::open(['route' => 'course.catalog.user.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'course-user']) }}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $course->name }} ({{ $course->abbrv }})</h3>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            {{ Form::label('abbrv', trans('labels.courses.course_role'), ['class' => 'col-lg-2 control-label required text-md-right']) }}
                            <div class="col-lg-5">
                                {{ Form::select('role_id', $courseRoles, $role_id, ['id' => 'role_id', 'class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
                            </div>
                        </div>

                        <div class="form-group row">
                            {{ Form::label('q', trans('labels.courses.user_search'), ['class' => 'col-lg-2 control-label required text-md-right']) }}
                            <div class="col-lg-5">
                                <div class="js-result-container"></div>
                                <div class="typeahead__container">
                                    <div class="typeahead__field">
                                        <div class="typeahead__query">
                                            <input placeholder="{{ trans('labels.general.start_typing') }}" class="js-typeahead form-control" name="q" type="search" autofocus autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="typeahead__button">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="user_id" name="user_id" value="">
                        <input type="hidden" id="course_id" name="course_id" value="{{ $course->id }}">

                    </div>

                    <div class="card-footer">
                        <div class="float-left">
                            <div class="pull-left">
                                @if (Session::get('breadcrumbLevel1') == 'mycourses')
                                    {{ link_to('/mycourses', trans('buttons.general.cancel'), ['class' => 'btn btn-warning btn-md']) }}
                                @elseif (Session::get('breadcrumbLevel1') == 'courses')
                                    {{ link_to('/courses/active?id='.$course->id, trans('buttons.general.cancel'), ['class' => 'btn btn-warning btn-md']) }}
                                @endif
                            </div>
                        </div><!--pull-left-->
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.add_user'), ['class' => 'btn btn-success btn-md']) }}
                        </div><!--pull-right-->
                    </div><!-- /.card-footer -->

                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="users-table" class="table table-condensed table-hover" width="100%">
                                <thead>
                                <tr>
                                    <th>{{ trans('labels.general.name') }}</th>
                                    <th>{{ trans('labels.general.role') }}</th>
                                    <th>{{ trans('labels.general.email') }}</th>
                                    <th>{{ trans('labels.general.actions') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div><!--table-responsive-->
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </section>

@endsection

@section('after-scripts')
{{ Html::script("/js/jquery.typeahead.min.js") }}
{{ Html::script("/js/sweetalert/sweetalert.min.js") }}

<script>

    $(function() {

        // load DataTable
        dt = $('#users-table').DataTable({
            ajax: {
                url: '{!! url("courseuserstable.data") !!}',
                data: function (d) {
                    d.course_id = '{{ $course->id }}';
                }
            },
            language: {searchPlaceholder: "Search..."},
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],

            buttons: [
            {
                extend: 'excelHtml5',
                footer: false,
                title: '{{$course->name}}',
                exportOptions: {
                    columns: [1,0,2]
                }
            },
            {
                extend: 'pdf',
                footer: false,
                title: '{{$course->name}}',
                exportOptions: {
                    columns: [1,0,2]
                }
            },
            {
                extend: 'copy',
                footer: false,
                title: '{{$course->name}}',
                exportOptions: {
                    columns: [1,0,2]
                }
            }         
            ],

            // This is needed for bootstrap 4 buttons to be inline with search
            dom:"<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",

            responsive: true,

            columns: [
                {data: 'name', name: 'name'},
                {data: 'role', name: 'role'},
                {data: 'email', name: 'email'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false},
                {data: 'id', name: 'id', visible: false},
                {data: 'first_name', name: 'first_name', searchable:true}, //for search
                {data: 'last_name', name: 'last_name', searchable:true}, //for search

            ],

            columnDefs: [
                { 'visible': false, "targets": [1,4,5,6] },  // grouping column, role name only and search columns hidden
            ],

            order: [[ 1, 'asc' ], [0, 'asc']],   //Set your sort order (first column is index 0),

            rowGroup: {
                dataSrc: 'role'
            },
        });

    });


    // load typeahead
    typeof $.typeahead === 'function' && $.typeahead({

        input: '.js-typeahead',
        minLength: 2,
        maxItem: 10, 
        order: "asc",
        dynamic: true,
        backdrop: {
            "background-color": "#fff"
        },
        template: function (query, item) {
    
            return '<span class="row">' + '<span class="display_user ml-2">@{{name}} <small>(@{{email}})</small></span></span>'
        },
        emptyTemplate: "no result for @{{query}}",
        source: {
            user: {
                display: ["name", "email"],
                ajax: function (query) {
                    return {
                        type: "GET",
                        url: "{!! url('courseusers.data') !!}",
                        path: "data.user",
                        data: {
                            q: query
                        },
                        callback: {
                            done: function (data) {
                                return data;
                            }
                        }
                    }
                }
            }
        },
        callback: {
            onClick: function (node, a, item, event) {
                $('#user_id').val(item.id);
                // item.id, item.name, item.email
            },
            onSendRequest: function (node, query) {
                console.log('request is sent')
            },
            onReceiveRequest: function (node, query) {
                console.log('request is received')
            }
        },
        debug: true
    });

    //mitcks: set the name of the user being deleted to use in sweet alert
    $('#users-table').on('click', 'tbody tr', function () {
        var row = dt.rows($(this)).data();
        registeredUser = row[0]['name'];
        registeredUserRole = row[0]['role'];
    } );

    // delete item  / sweet alert
    $("body").on("click", "a[name='delete']", function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var $table = $('#users-table').DataTable();

        var data_row = $table.row($(this).closest('tr')).data();

        swal({
            title: "{{ trans('alerts.backend.courseusers.deletePart1')}}" + registeredUser
                    + "{{ trans('alerts.backend.courseusers.deletePart2', ['course' => $course->abbrv])}}" + " " + registeredUserRole
                    + "{{ trans('alerts.backend.courseusers.deletePart3')}}",
            icon: "warning",
            buttons: true,
            showCancelButton: true,
            dangerMode: true,
            confirmButtonColor: "#DD6B55",
        })
        .then(function(isConfirmed) {
            if (isConfirmed) {
                window.location.href = href;
            }
        });
    });

</script>
@endsection


<!-- need to find somewhere better to put this -->
@section('after-styles')
<style>
    .typeahead__container {
        font-family: 'Open Sans', sans-serif !important;
        font-weight: 400 !important;
        font-size: 14px !important;
    }

</style>
@endsection