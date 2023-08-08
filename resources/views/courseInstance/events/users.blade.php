@extends('frontend.layouts.app')

@section ('title', trans('menus.frontend.event.assign'). ' | ' . $event->courseInstance->course->name.' ('.$event->courseInstance->course->abbrv .') ' . date_create($event->start_time)->format('m/d/Y g:iA'))

@section('before-styles')
    {{ Html::style("/css/jquery.typeahead.min.css") }}
@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">

            <div class="panel panel-default">
                <div class="panel-heading"><h4 id="heading">{{ trans('menus.frontend.event.assign')}}</h4></div>

                <div class="panel-body">

                    {{ Form::open(['route' => 'event.user.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'event-user']) }}

                    <div class="form-group">
                        {{ Form::label('name', trans('labels.event.course'), ['class' => 'col-lg-2 control-label']) }}
                        <div class="col-lg-5">
                            <h5>{{ $event->courseInstance->course->name }} ({{ $event->courseInstance->course->abbrv }})</h5>
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('datetime', trans('labels.event.class_date'), ['class' => 'col-lg-2 control-label']) }}
                        <div class="col-lg-5">
                            <h5>{{ Carbon\Carbon::parse($event->start_time)->format('l, F d, Y') }} ({{ date_create($event->start_time)->format('g:ia')}} - {{ date_create($event->end_time)->format('g:ia') }})</h5>
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('role_id', trans('labels.events.event_role'), ['class' => 'col-lg-2 control-label']) }}
                        <div class="col-lg-5">
                            {{-- When directed from /pending set the role_id for the user --}}
                            @if(isset($enroll_request))
                                {{ Form::select('role_id', $eventRoles, $enroll_request->role_id, ['id' => 'role_id', 'class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
                            @else
                                @if ($role_id = Session::get('role_id'))
                                    {{ Form::select('role_id', $eventRoles, $role_id, ['id' => 'role_id', 'class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
                                @else
                                    {{ Form::select('role_id', $eventRoles, null, ['id' => 'role_id', 'class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
                                @endif
                            @endif

                        </div>
                    </div>

                    <div class="form-group">
                    {{ Form::label('q', trans('labels.courses.user_search'), ['class' => 'col-lg-2 control-label']) }}
                    
                        <div class="col-lg-5">
                            <div class="js-result-container"></div>
                            <div class="typeahead__container">
                                <div class="typeahead__field">
                                    <div class="typeahead__query">
                                        <input id="user" placeholder="{{ trans('labels.general.start_typing') }}" class="js-typeahead form-control" name="q" type="search" autofocus autocomplete="off">
                                    </div>
                                </div>
                                <div class="typeahead__button">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <input type="hidden" id="user_id" name="user_id" value="">
                <input type="hidden" id="event_id" name="event_id" value="{{ $event->id }}">

                <div class="panel-footer clearfix">
                    {{-- If Directed from /pending and has $enroll_request make the cancel button go back to that page --}}
                    @if(isset($enroll_request))
                        <div class="pull-left">
                            {{ link_to_route('pending', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
                        </div>
                    @else
                        <div class="pull-left">
                            {{ link_to_route('frontend.user.dashboard', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
                        </div>
                    @endif

                    <div class="pull-right">
                        {{ Form::submit(trans('buttons.general.add_user'), ['class' => 'btn btn-success btn-lg', 'id' => 'add-user']) }}
                    </div>
                </div>

                {{ Form::close() }}

            </div>

            <!-- datatable panel/table -->
            <div class="panel panel-default">

                <div class="panel-body">
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
                </div>

            </div> <!-- /panel end -->

        </div>

    </div>

    <!-- modal link / modal -->
    <a id="modal-link" data-toggle="modal" href="/#/" data-target="#modal" style="display:none">Modal Link</a>
    
    <div class="modal" id="modal" tabindex="-1" role="dialog" labelledby="remoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content" >
                <form action="" method="post">
                    <div class="modal-body" >
                    <h3 class="push-down-20" >{{ trans('labels.general.loading') }}</h3>
                        <div class="progress progress-striped active">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

<!-- sets title string for the export of datatable below -->
@php($title_string = $event->courseInstance->course->name.' ('.$event->courseInstance->course->abbrv .') ' . date_create($event->start_time)->format('m/d/Y g:iA'))

@section('after-scripts')
{{ Html::script("/js/jquery.typeahead.min.js") }}
{{ Html::script("/js/sweetalert/sweetalert.min.js") }}

<script>

    $(function() {

        // modal refresh fix
        $(document).on('hidden.bs.modal', function (e) {
            $(e.target).removeData('bs.modal')
            .find(".modal-content").html('<form action="" method="post"><div class="modal-body" ><h3 class="push-down-20" >Loading..</h3><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%"></div></div></div></form>');
        });

        // load DataTable
        dt = $('#users-table').DataTable({
            ajax: {
                url: '{!! url("eventuserstable.data") !!}',
                data: function (d) {
                    d.event_id = '{{ $event->id }}';
                }
            },
            language: {search: "", searchPlaceholder: "Search..."},
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            

            buttons: [
            {
                extend: 'excelHtml5',
                title: '{{ $title_string }}',
                footer: false,
                exportOptions: {
                    columns: [1,2,3]
                }
            },
            {
                extend: 'pdf',
                title: '{{ $title_string }}',
                footer: false,
                exportOptions: {
                    columns: [1,2,3]
                }
            },
            {
                extend: 'copy',
                title: '{{ $title_string }}',
                footer: false,
                exportOptions: {
                    columns: [1,2,3]
                }
            }         
            ],

            // set the format for table and surrounding functionality
            dom: '<"col-sm-12"<"col-sm-4"l><"col-sm-4"B><"col-sm-4"f>><r>tip',

            select: 'single',
            paging: false,

            columns: [
                {data: 'id', name: 'id', visible: false},
                {data: 'role', name: 'role'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false},
            ],
            order: [[ 1, 'asc' ], [2, 'asc'], [3, 'asc']],   // sort order (first column is index 0)
            columnDefs: [
                { "visible": false, "targets": 1 }  // grouping column invisible
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
                        url: "{!! url('eventusers.data') !!}",
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

    // IF directed from /pending fill the User search input for the user and set the "Add User" button focus
    @if(isset($enroll_request))
        // Set User ID
        let user_id = '{{$enroll_request->user->id}}';
        $('#user_id').val(user_id);

        // Set User Seach input name
        let user = '{{ $enroll_request->user->first_name }} ' + '{{ $enroll_request->user->last_name }}';
        $('#user').val(user);

        // Set Button Focus
        $('#add-user').focus();        
    @endif



    // view item / sweet alert
    // front end uses a newer version of sweet alert, - this one has a .then promise as opposed to course/users.blade.php
    $("body").on("click", "a[name='info']", function(e) {
        var link = $(this);
        var title = link.attr('data-trans-title') ? link.attr('data-trans-title') : "Username";
        var created = link.attr('data-trans-created') ? link.attr('data-trans-created') : "User info failed to load?";
        var edited = link.attr('data-trans-edited') ? link.attr('data-trans-edited') : "User info failed to load?";
        e.preventDefault();
        swal({
            title: title,
            text: '{{ trans('labels.general.added_by') }}: '+ created +'\n',
            icon: "info",
            dangerMode: false,
        }).then(function(confirmed) {
        });
    });    

    // delete item / sweet alert
    $("body").on("click", "a[name='delete']", function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        swal({
            title: "{{ trans('alerts.frontend.eventusers.delete') }}",
            icon: "warning",
            buttons: true,
            showCancelButton: true,
            dangerMode: true,
            confirmButtonColor: "#DD6B55",
        }).then(function(confirmed) {
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
        font-family: 'Open Sans', sans-serif !important;
        font-weight: 400 !important;
        font-size: 14px !important;
    }

</style>
@endsection


