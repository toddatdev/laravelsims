@extends('frontend.layouts.app')

@section ('title', trans('labels.scheduling.scheduling') . ' | ' . trans('labels.scheduling.confirmation'))

@section('content')

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="pull-left">{{ trans('labels.scheduling.scheduling') }} - {{ trans('menus.frontend.scheduling.recurrence_group') }}</h3>
                        <h5>{{$courseInstance->Course->name}} ({{$courseInstance->Course->abbrv}})</h5>
                    </div>
                    <div class="card-body">

                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }} </strong>
                            </div>
                        @endif

                        <table id="events-table" class="table table-condensed table-hover table-striped table-responsive nowrap" width="100%">
                            <thead class="small">
                            <th>{{ trans('labels.general.date') }}</th>
                            <th>{{ trans('labels.general.time') }}</th>
                            <th></th> {{--images--}}
                            <th>{{ trans('labels.calendar.initial_meeting_room') }}</th>
                            <th>Conflicts</th>
                            <th>Dashboard</th> {{--actions--}}
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('after-scripts')

<script>
    $(function() {

        eventsTable = $('#events-table').DataTable({
            lengthChange: false,
            paging: false,
            info: false,
            sDom: '',
            ajax: {
                url: '{!! url('courseInstanceEventsTable.data') !!}', // uses same calendar day view datatable
                data: function (d) {
                    d.course_instance_id = '{{ $courseInstance->id }}';
                }
            },

            language: {emptyTable: "{{trans('labels.calendar.no_data_today')}}"},

            responsive: true,

            columns: [
                { data: 'date', name: 'date' },

                { data: 'time', name: 'time' },

                { data: 'images', name: 'images', orderable: false, searchable: false },

                { data: 'building_location_room', name: 'building_location_room', "render": function (data, type, full) {
                        return '<span data-toggle="tooltip" title="' + full.event_rooms + '">' + data + '</span>';
                    }
                },
                { data: 'conflicts', name: 'conflicts' },

                { data: 'actions', name: 'actions', orderable: false, searchable: false, width: "90px" },
                {data: 'start_time', name: 'start_time', visible:false}, //for date sort
                {data: 'end_time', name: 'end_time', visible:false}, //for date sort
            ],

            columnDefs: [
                {
                    targets: [4,5],
                    className: 'dt-body-center'
                },
                {
                    targets: [4,5],
                    className: 'dt-head-center'
                },
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: -1 },
                { orderData: [5,6], targets: 0} //order by hidden columns 5 and 6 instead of formatted column 0
            ],


        });

    });

</script>

@endsection