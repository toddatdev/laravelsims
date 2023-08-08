@section('before-styles')
    {{ Html::style("/css/jquery.typeahead.min.css") }}

    <style>
        /*center buttons and bump down so not so close to bottom border*/
        .dataTables_wrapper .dt-buttons {
            float:none;
            text-align:center;
            padding-top: 10px;
        }

        .typeahead__container {
            font-family: 'Open Sans', sans-serif !important;
            font-weight: 400 !important;
            font-size: 14px !important;
        }

        /*mitcks 2020-03-19 commenting this out because arrows make sense on email tables*/
        /*hides sorting arrows*/
        /*table.dataTable thead .sorting,*/
        /*table.dataTable thead .sorting_asc,*/
        /*table.dataTable thead .sorting_desc {*/
        /*background : none;*/
        /*}*/

    </style>

@endsection

        {{--Class Size/Enrollment/Waitlist/ParkingLot Counts--}}
        <p>
            @if($event->isFull())
                <span class="event-full">{{ trans('labels.event.full') }}</span>
            @endif
            {!! $event->DisplayEventUserCounts !!}
        </p>

        {{ Form::open(['route' => 'event.user.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'event-user']) }}

        <div class="form-group row">
            {{ Form::label('role_id', trans('labels.events.event_role'), ['class' => 'col-lg-2 control-label text-md-right']) }}
            <div class="col-lg-5">
                @if ($role_id = Session::get('role_id'))
                    {{ Form::select('role_id', $eventRoles, $role_id, ['id' => 'role_id', 'class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
                @else
                    {{ Form::select('role_id', $eventRoles, null, ['id' => 'role_id', 'class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
                @endif
            </div>
        </div>

        <div class="form-group row">
            {{ Form::label('q', trans('labels.courses.user_search'), ['class' => 'col-lg-2 control-label text-md-right']) }}

            <div class="col-lg-5">
                <div class="js-result-container"></div>
                <div class="typeahead__container">
                    <div class="typeahead__field">
                        <div class="typeahead__query">
                            <input placeholder="{{ trans('labels.general.start_typing') }}" class="js-typeahead form-control
                            @if (!$event->hasSiteCourseEventPermissions(['add-people-to-events'], ['course-add-people-to-events'], ['event-add-people-to-events']))
                                disabled
                            @endif
                            " name="q" type="search" autofocus autocomplete="off">
                        </div>
                    </div>
                    <div class="typeahead__button"></div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="pull-right">
                    {{ Form::hidden('user_id', '', array('id' => 'user_id')) }}
                    {{ Form::hidden('event_id', $event->id, array('id' => 'event_id')) }}
                    {{ Form::hidden('tab', 'roster', array('id' => 'tab')) }}

                    {{-- Also, don't have server send back 500 error if user being searched is not found, use 404  --}}
                    @if ($event->hasSiteCourseEventPermissions(['add-people-to-events'], ['course-add-people-to-events'], ['event-add-people-to-events']))
                        {{ Form::submit(trans('buttons.general.add_user'), ['class' => 'btn btn-success btn-md']) }}
                    @else
                        {{ Form::submit(trans('buttons.general.add_user'), ['class' => 'btn btn-success btn-md disabled']) }}
                    @endif
                </div>
            </div>
        </div>

        {{ Form::close() }}
        <div class="table-responsive">
            <table id="users-table" class="table table-condensed table-hover indent_first_child" width="100%">
                <thead>
                    <tr>
                        <th>{{ trans('labels.general.name') }}</th>
                        <th>{{ trans('labels.general.role') }}</th>
                        <th>{{ trans('labels.events.attendance') }}</th>
                        <th>{{ trans('labels.general.actions') }}</th>
                        <th>{{ trans('labels.backend.access.users.table.first_name') }}</th> {{--this header is only here for data export--}}
                        <th>{{ trans('labels.backend.access.users.table.last_name') }}</th> {{--this header is only here for data export--}}
                        <th>{{ trans('labels.backend.access.users.table.email') }}</th> {{--this header is only here for data export--}}
                        <th>{{ trans('labels.general.role') }}</th> {{--this header is only here for data export--}}
                    </tr>
                </thead>
            </table>
        </div>

{{--DISPLAY ALL USER EMAILS AND CLICK/COPY--}}
<span id = "allEmails" style="display: none;">{{ $event->emailsByRoleAndStatus(0,1) }}</span>
<p class="text-center"><a href="#" onclick="copyToClipboard('#allEmails')">{{ trans('labels.event.email_all') }}</a></p>


<!-- sets title string for the export of datatable below -->
@php($title_string = $event->courseInstance->course->name.' ('.$event->courseInstance->course->abbrv .') ' . date_create($event->start_time)->format('m/d/Y g:iA'))



@section('after-scripts')
    {{ Html::script("/js/jquery.typeahead.min.js") }}

    <script>

        $(function() {


            //****************************
            // ROSTER DataTable section
            dt = $('#users-table').DataTable({
                ajax: {
                    url: '{!! route('event.users.table') !!}',
                    data: function (d) {
                        d.event_id = '{{ $event->id }}';
                    }
                },

                // set the format for table and surrounding functionality
                //dom: '<"col-sm-12"<"col-sm-4"l><"col-sm-4"B><"col-sm-4"f>><r>tip', //buttons at top
                dom: '<"top"f>rt<"bottom"B><"clear">', //buttons at bottom, no paging
                paging: false,

                language: {searchPlaceholder: "{!!trans('labels.general.search_placeholder')!!}", search: ""},
                searching: true,

                responsive: true,
                columns: [
                    {data: 'name', name: 'name', searchable:false}, //has full name and email
                    {data: 'role', name: 'role', searchable:false}, //has role name and email copy link
                    {data: 'chbx_attend', name: 'chbx_attend', searchable:false}, //attendance checkbox
                    {data: 'actions', name: 'actions', orderable: false, searchable:false},
                    {data: 'firstName', name: 'firstName', searchable:true}, //for search only, hidden below
                    {data: 'lastName', name: 'lastName', searchable:true}, //for search only, hidden below
                    {data: 'email', name: 'email', searchable:true}, //for search only, hidden below
                    {data: 'roleNameOnly', name: 'roleNameOnly', searchable:false} //for data export, hidden below
                ],

                columnDefs: [
                    { 'visible': false, "targets": [1,4,5,6,7] },  // role (grouping column) and search columns hidden
                    {
                        "targets": 2, // centers the attendance button column
                        "className": "text-center",
                    },
                ],

                order: [[1, 'asc'],[5, 'asc'],[4, 'asc'] ], //role, lastname, firstname

                rowGroup: {
                    dataSrc: 'role'
                },

                buttons: [
                    // { extend: 'edit',   editor: this.editor },
                    {
                        extend: 'excelHtml5',
                        title: '{{ $title_string }}',
                        footer: false,
                        exportOptions: {
                            columns: [3,4,5,6]
                        }
                    },
                    {
                        extend: 'pdf',
                        title: '{{ $title_string }}',
                        footer: false,
                        exportOptions: {
                            columns: [3,4,5,6]
                        }
                    },
                    {
                        extend: 'copy',
                        title: '{{ $title_string }}',
                        footer: false,
                        exportOptions: {
                            columns: [3,4,5,6]
                        }
                    }
                ],
            });

            //****************************
            // WAITLIST DataTable section
            waitlistTable = $('#waitlist-table').DataTable({
                ajax: {
                    url: '{!! route('event.waitlist.table') !!}',
                    data: function (d) {
                        d.event_id = '{{ $event->id }}';
                    }
                },
                // set the format for table and surrounding functionality
                //dom: '<"col-sm-12"<"col-sm-4"l><"col-sm-4"B><"col-sm-4"f>><r>tip', //buttons at top
                dom: '<"top"f>rt<"bottom"B><"clear">', //buttons at bottom, no paging
                paging: false,

                language: {searchPlaceholder: "{!!trans('labels.general.search_placeholder')!!}", search: ""},
                searching: true,

                responsive: true,
                columns: [
                    {data: 'name', name: 'name', searchable:false}, // has full name and email
                    {data: 'role', name: 'role', searchable:false}, // used for grouping header, has role name and email copy link
                    {data: 'request_date', name: 'request_date', searchable:true},
                    {data: 'request_notes', name: 'request_notes', searchable:true},
                    {data: 'actions', name: 'actions', orderable: false, searchable:false},
                    {data: 'firstName', name: 'firstName', searchable:true}, //for search only
                    {data: 'lastName', name: 'lastName', searchable:true}, //for search only
                    {data: 'email', name: 'email', searchable:true}, //for search only
                    {data: 'roleNameOnly', name: 'roleNameOnly', searchable:false}, //for data export
                    {data: 'created_at', name: 'created_at', searchable:true}, //for date sort

                ],

                columnDefs: [
                    { 'visible': false, "targets": [1,5,6,7,8,9] },  // role (group header), roleNameOnly and search columns hidden
                ],

                order: [[1, 'asc'],[9, 'asc'] ], //by role for grouping, then by date desc

                rowGroup: {
                    dataSrc: 'role'
                },

                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: '{{ $title_string }}',
                        footer: false,
                        exportOptions: {
                            columns: [5,6,7,8,3]
                        }
                    },
                    {
                        extend: 'pdf',
                        title: '{{ $title_string }}',
                        footer: false,
                        exportOptions: {
                            columns: [5,6,7,8,3]
                        }
                    },
                    {
                        extend: 'copy',
                        title: '{{ $title_string }}',
                        footer: false,
                        exportOptions: {
                            columns: [5,6,7,8,3]
                        }
                    }
                ],
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

                return '<span class="row">' + '<span class="ml-2 display_user">@{{name}}</span></span>'
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


        //this is for function below to mark attendance
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // MARK ATTENDANCE
        $("body").on("click", "[name='mark_attendance']", function(e) {

            e.preventDefault();

            //these are set where the mark_attendance button is created in the EventUser model
            var eventUserId = $(this).data('event_user_id');
            var action = $(this).data('action');

            // need to set variable for the button here outside of ajax so it can be updated inside the ajax success
            var button = this;

            // console.log('eventUserId: '+eventUserId);
            // console.log('action: '+action);

            $.ajax({
                url: '{{ url('updateAttendance') }}',
                method: 'post',
                data: {
                        event_user_id: eventUserId,
                        action: action
                      },
                success:function(response){
                    if(response.success){
                        //change image for button and data property (the data property is in case they click again)
                        if(action=='turn_on')
                        {
                            // alert('I need to show green button');
                            $(button).html("<i class='text-success fad fa-toggle-on fa-2x text-success'>");
                            $(button).data('action', "turn_off");
                            $(button).attr('data-tooltip', "{{trans('labels.events.unmark_attend')}}")
                        }
                        else
                        {
                            // alert('I need to show gray button');
                            $(button).html("<i class='text-secondary fad fa-toggle-off fa-2x'></i>");
                            $(button).data('action', "turn_on");
                            $(button).attr('data-tooltip', "{{trans('labels.events.mark_attend')}}")
                        }
                        //alert(response.message) //Message from controller
                    }else{
                        //alert("Error")
                    }
                },
                error:function(error){
                    console.log(error)
                }
            });
        });


        // DELETE ENROLLED USER FROM ROSTER TAB SWEET ALERT
        $("body").on("click", "a[name='delete_roster']", function(e) {
            //console.log('delete click')
            e.preventDefault();
            var href = $(this).attr('href');

            //these are set where the delete button is created in the EventUser model
            var fullname = $(this).data('fullname');
            var eventdate = $(this).data('eventdate');

            //set alert content here so it can contain html
            var content = document.createElement('div');
            content.innerHTML = "{{ trans('alerts.frontend.eventusers.deleteConfirmPart1') }}"+'<h4 style="font-weight:bolder; color:blue;">'+ fullname +'</h4>' + " {{ trans('alerts.frontend.eventusers.deleteConfirmPart2') }}" + eventdate;

            swal({
                title: "{{ trans('alerts.general.confirm_delete') }}",
                content: content, //this is set above so it can include html
                icon: "warning",
                buttons: true,
                dangerMode: true,
                }).then(function(confirmed) {
                    if (confirmed)
                        window.location = href;
                });
        });

        // DELETE USER FROM WAITLIST TAB SWEET ALERT (this function is OK here instead of partial-waitlist because if a user has access to one, they have access to both)
        $("body").on("click", "a[name='delete_wailist']", function(e) {
            e.preventDefault();
            var href = $(this).attr('href');

            //these are set where the delete button is created in the EventUser model
            var fullname = $(this).data('fullname');
            var eventdate = $(this).data('eventdate');

            var content = document.createElement('div');
            content.innerHTML = "{{ trans('alerts.frontend.eventusers.deleteConfirmPart1') }}"+'<h4 style="font-weight:bolder; color:blue;">'+ fullname +'</h4>' + " {{ trans('alerts.frontend.eventusers.myWaitlistDeleteConfirmPart2') }}" + eventdate;

            swal({
                title: "{{ trans('alerts.general.confirm_delete') }}",
                content: content, //this is set above so it can include html
                icon: "warning",
                buttons: true,
                dangerMode: true,
                }).then(function(confirmed) {
                    if (confirmed)
                        window.location = href;
                });
        });

        // PARK USER USER SWEET ALERT (this function is OK here instead of partial-waitlist because if a user has access to one, they have access to both)
        $("body").on("click", "a[name='park_button']", function(e) {
            e.preventDefault();
            var href = $(this).attr('href');

            //these are set where the delete button is created in the EventUser model
            var fullname = $(this).data('fullname');
            var eventdate = $(this).data('eventdate');
            var waitlisttext = $(this).data('waitlisttext');

            var content = document.createElement('div');
            content.innerHTML = "{{ trans('alerts.frontend.eventusers.parkConfirmPart1') }}"+'<h4 style="font-weight:bolder; color:blue;">'+ fullname +'</h4>' + " {{ trans('alerts.frontend.eventusers.parkConfirmPart2') }}" + waitlisttext + eventdate;

            swal({
                title: "{{ trans('labels.event.parkingLot') }}",
                content: content, //this is set above so it can include html
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then(function(confirmed) {
                if (confirmed)
                    window.location = href;
            });
        });


        //used for the email strings to click and copy
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }

        }); //function


    </script>

@endsection

<!-- need to find somewhere better to put this -->
@section('after-styles')

@endsection
