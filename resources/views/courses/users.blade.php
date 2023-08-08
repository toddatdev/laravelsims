@extends('backend.layouts.app')

@section ('title', trans('menus.backend.course.title') . ' | ' . trans('menus.backend.course.assign'))

@section('before-styles')
    {{ Html::style("/css/jquery.typeahead.min.css") }}
@endsection

@section('page-header')
    <h1>
        {{ trans('menus.backend.course.title') }}
        <small>{{ trans('menus.backend.course.assign') }}</small>
    </h1>
@endsection

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $course->name }} ({{ $course->abbrv }})</h3>
            
            <div class="box-tools pull-right">
                {{ link_to_route('active_courses', trans('menus.backend.course.focus'), ['id'=>$course->id], ['class' => 'btn btn-primary btn-xs']) }}
            </div>
        </div>

        <!-- success message -->
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <strong>{{ $message }}</strong>
            </div>
        @endif

        <!-- warning message -->
        @if ($message = Session::get('danger'))
            <div class="alert alert-danger alert-block">
                <strong>{{ $message }}</strong>
            </div>
        @endif

        <div class="box-body">
        
                {{ Form::open(['route' => 'course.user.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'course-user']) }}

                <div class="form-group">
                    {{ Form::label('abbrv', trans('labels.courses.course_role'), ['class' => 'col-lg-2 control-label required']) }}
                    <div class="col-lg-10">
                        @if ($role_id = Session::get('role_id'))
                        {{ Form::select('role_id', $courseRoles, $role_id, ['id' => 'role_id', 'class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
                        @else
                        {{ Form::select('role_id', $courseRoles, null, ['id' => 'role_id', 'class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
                        @endif
                    </div>
                </div>

                <div class="form-group">
                {{ Form::label('q', trans('labels.courses.user_search'), ['class' => 'col-lg-2 control-label required']) }}
                
                    <div class="col-lg-10">
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

                <div class="form-group">
                    {{ Form::label('', ' ', ['class' => 'col-lg-2 control-label']) }}
                    <div class="col-lg-10">
                        {{ Form::submit(trans('buttons.general.add_user'), ['class' => 'btn btn-success']) }}
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="users-table" class="table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{ trans('labels.general.role') }}</th>
                                <th>{{ trans('labels.general.name') }}</th>
                                <th>{{ trans('labels.general.email') }}</th>
                                <th>{{ trans('labels.general.actions') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div><!--table-responsive-->
    

            </form>

        </div>

    </div><!--/.box-success -->

@endsection

@section('after-scripts')
{{ Html::script("/js/jquery.typeahead.min.js") }}


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
            language: {search: "", searchPlaceholder: "Search..."},
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],

            buttons: [
            {
                extend: 'excelHtml5',
                footer: false,
                title: '{{$course->name}}',
                exportOptions: {
                    columns: [1,2,3]
                }
            },
            {
                extend: 'pdf',
                footer: false,
                title: '{{$course->name}}',
                exportOptions: {
                    columns: [1,2,3]
                }
            },
            {
                extend: 'copy',
                footer: false,
                title: '{{$course->name}}',
                exportOptions: {
                    columns: [1,2,3]
                }
            }         
            ],

            // set the format for table and surrounding functionality
            dom: '<"col-sm-12"<"col-sm-4"l><"col-sm-4"B><"col-sm-4"f>><r>tip',

            columns: [
                {data: 'id', name: 'id', visible: false},
                {data: 'role', name: 'role'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false},
            ],
            order: [[ 1, 'asc' ], [2, 'asc'], [3, 'asc']],   //Set your sort order (first column is index 0),
            columnDefs: [
                { "visible": false, "targets": 1 }  //Make the grouping column invisible.
            ],
            displayLength: 25,

            drawCallback: function ( settings ) {
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last = null;
                api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last != group ) {
                        $(rows).eq( i ).before(
                            '<tr class="group"><td colspan="7">'+group+'</td></tr>'
                        );
                        last = group;
                    }
                });
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
    
            return '<span class="row">' + '<span class="display_user">@{{name}} <small>(@{{email}})</small></span></span>'
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

    // delete item  / sweet alert
    $("body").on("click", "a[name='delete']", function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        swal({
            title: "{{ trans('alerts.backend.courseusers.delete').' '.$course->abbrv.'?' }}",
            icon: "warning",
            buttons: true,
            showCancelButton: true,
            dangerMode: true,
            confirmButtonColor: "#DD6B55",
        }, function(confirmed) {
        if (confirmed)
            window.location = href;
        });
    });

</script>
@endsection


<!-- need to find somewhere better to put this -->
@section('after-styles')
<style>
    .typeahead__container {
        font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
        font-weight: 400 !important;
        font-size: 14px !important;
    }

</style>
@endsection