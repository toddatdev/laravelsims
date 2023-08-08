@extends('frontend.layouts.app')

<style>
    td.details-control {
        background: url('{{URL::asset('img/frontend/details_open.png')}}') no-repeat center center;
        cursor: pointer;
    }
    tr.details td.details-control {
        background: url('{{URL::asset('img/frontend/details_close.png')}}') no-repeat center center;
    }
</style>

@section ('title', trans('labels.scheduling.scheduling') . ' | ' . trans('labels.scheduling.pending_requests'))

{{ Html::style("css/jquery-ui/jquery-ui.css") }}
{{ Html::style("css/jquery-ui/jquery-ui-timepicker-addon.css") }}


@section('content')

    <div class="box-header with-border">
        <h4 class="box-title pull-left">{{ trans('labels.scheduling.scheduling') . ' - ' . trans('labels.scheduling.pending_requests') }}</h4>
        <div class="box-tools pull-right">
            @include('courseInstance.scheduleRequest.partial-header-buttons')
        </div>
    </div><!-- /.box-header -->
    <div class="row">

        <div class="col-xs-12">


            <div class="panel panel-default">

                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="requests-table" class="table table-condensed table-hover">
                            <thead>
                            <tr>
                                <th></th>
                                <th>{{ trans('labels.scheduling.course') }}</th>
                                <th>{{ trans('labels.calendar.location') }}</th>
                                <th>{{ trans('labels.scheduling.event_date') }}</th>
                                <th>{{ trans('labels.general.time') }}</th>
                                <th>{{ trans('labels.scheduling.sim_spec') }}</th>
                                <th>{{ trans('labels.calendar.event_group') }}</th>
                                <th>{{ trans('labels.scheduling.status') }}</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>{{ trans('labels.general.actions') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div><!--table-responsive-->
                </div>

            </div>
        </div>
    </div>

@stop

@section('after-styles')

@endsection

@section('after-scripts')

    <script>

        // details format
        function format (d) {
                    return  'Rooms: '+d.num_rooms+'<br>'+
                            'Participants: '+d.class_size+'<br>'+
                            'Request Note: '+d.notes+'<br>'+
                            'Requested By: '+d.requested_by+'<br>'+
                            'Date Requested: '+d.requested_date+'<br>';
        }

        $(function() {
            var dt = $('#requests-table').DataTable({

                processing: true,
                ajax: '{!! url("pendingRequest.data") !!}',

                bFilter: true,
                buttons: false,

                //customize no data message
                language: {emptyTable: "{{trans('labels.scheduling.no_data_today')}}"},

                //Set the format for table and surrounding functionality
                dom: '<"col-sm-12"<"col-sm-4"l><"col-sm-4"><"col-sm-4"f>><r>tip',
                // dom: 'lfrtip', //"Classic" button layout

                columns: [
                    {
                        class:          'details-control',
                        orderable:      false,
                        data:           null,
                        defaultContent: ''
                    },
                    { data: 'course.abbrv', name: 'course.abbrv' },
                    { data: 'location.abbrv', name: 'location.abbrv' },
                    { data: 'date', name: 'date'},
                    { data: 'time', name: 'time'},
                    { data: 'sim.spec.needed', name:'sim.spec.needed'},
                    { data: 'group', name: 'group'},
                    { data: 'status', name: 'status'},
                    // Put in the action buttons on the rightmost column
                    { data: 'actions', name: 'actions', orderable: false, searchable: false},
                    // hidden columns for details
                    { data: 'num_rooms', name: 'num_rooms', visible: false },
                    { data: 'class_size', name: 'class_size', visible: false },
                    { data: 'notes', name: 'notes', visible: false },
                    { data: 'requested_by', name: 'requested_by', visible: false },
                    { data: 'requested_date', name: 'requested_date', visible: false },
                ]
            });

            // Array to track the ids of the details displayed rows
            var detailRows = [];
        
            $('#requests-table tbody').on( 'click', 'tr td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = dt.row( tr );
                var idx = $.inArray( tr.attr('id'), detailRows );
        
                if ( row.child.isShown() ) {
                    tr.removeClass( 'details' );
                    row.child.hide();
        
                    // Remove from the 'open' array
                    detailRows.splice( idx, 1 );
                }
                else {
                    tr.addClass( 'details' );
                    row.child( format( row.data() ) ).show();
        
                    // Add to the 'open' array
                    if ( idx === -1 ) {
                        detailRows.push( tr.attr('id') );
                    }
                }
            } );
        
            // On each draw, loop over the `detailRows` array and show any child rows
            dt.on( 'draw', function () {
                $.each( detailRows, function ( i, id ) {
                    $('#'+id+' td.details-control').trigger( 'click' );
                } );
            } );


        });

    </script>


@endsection
