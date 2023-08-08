@extends('frontend.layouts.app')

@section ('title', trans('navs.frontend.scheduling.deleted_events'))

@section('after-styles')

@stop

@section('page-header')

    {{--used for alert messages--}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! $message !!}</strong>
        </div>
    @endif

@endsection

@section('content')

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('navs.frontend.scheduling.deleted_events')}}</h3>
                    </div>

                    <div class="card-body">

                         <table id="deleted-events-table" class="dataTables table table-condensed table-hover" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ trans('labels.scheduling.course') }}</th>
                                    <th>{{ trans('labels.scheduling.event_date') }}</th>
                                    <th>{{ trans('labels.calendar.location') }}</th>
                                    <th>{{ trans('labels.event.event_deleted_by') }}</th>
                                    <th>{{ trans('labels.event.event_deleted_on') }}</th>
                                    <th>{{ trans('labels.general.actions') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('after-scripts')

    {{ Html::script("/js/sweetalert/sweetalert.min.js") }}

    <script>

        $(document).ready(function() {

            // Build DELETED EVENTS DataTable
            deletedEventsTable = $('#deleted-events-table').DataTable({
                //this ajax call gets the data from a URL/route defined in web.php
                ajax: {
                    url: '{!! route('deleted.events.table') !!}',
                    data: function (d) {
                    }
                },
                // set the format for table and surrounding functionality
                dom: '<"top"f>rt<"bottom"lp><"clear">',

                paging: true,

                //Add an "All" to the "Show entries" pulldown menu
                "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],

                //this adds the word "Search..." as placeholder in search box, and "No data available" empty message
                language: {emptyTable: "{!!trans('labels.general.no_data')!!}",
                    searchPlaceholder: "{!!trans('labels.general.search_placeholder')!!}",
                    search: ""},
                searching: true,

                //this collapses the table for small screens
                responsive: true,

                //setup columns, grouping column should be first, note the array starts with 0 when referencing columns
                columns: [
                    {data: 'courseNameAndAbbrv', name: 'courseNameAndAbbrv'},
                    {data: 'eventDateTime', name: 'eventDateTime'},
                    {data: 'location', name: 'location'},
                    {data: 'deletedBy', name: 'deletedBy'},
                    {data: 'deletedOn', name: 'deletedOn'},
                    {data: 'restoreButton', name: 'restoreButton'},
                    {data: 'deleted_at', name: 'deleted_at'},
                    {data: 'start_time', name: 'start_time'},

                ],

                //order by deleted_at desc by default
                order: [[ 6, "desc" ]],

                columnDefs:[
                    { 'orderData':[6], 'targets': [4] }, //sort deletedOn (which is a formatted string) by deleted_at
                    { 'orderData':[7], 'targets': [1] }, //sort eventDateTime (which is a formatted string) by start_time
                    {
                        'targets': [6, 7], //hide the deleted_at and start_time columns which are only used for sorting
                        'visible': false,
                        'searchable': false
                    },
                ],

            });

        });

        // RESTORE EVENT SWEET ALERT
        // restore_event is the name of the button defined in Event model
        $("body").on("click", "a[name='restore_event']", function(e) {

            e.preventDefault();
            var href = $(this).attr("href");

            //these are set where the delete button is created in the EventUser model
            var eventdate = $(this).data('eventdate');

            //set alert content here so it can contain html
            var content = document.createElement('div');
            content.innerHTML = "{{ trans('alerts.frontend.scheduling.confirm_restore_text') }}"+'<h4 style="font-weight:bolder; color:blue;">'+ eventdate +'</h4>';

            swal({
                title: "{{ trans('alerts.general.confirm_restore') }}",
                content: content, //this is set above so it can include html
                icon: "warning",
                buttons: true,
                showCancelButton: true,
                dangerMode: true,
                confirmButtonColor: "#DD6B55",
            }).then(function(confirmed) {
                if (confirmed)
                    window.location = href;
            });
        })

    </script>
@stop