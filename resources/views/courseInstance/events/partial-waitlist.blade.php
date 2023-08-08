@section('before-styles')
    {{ Html::style("/css/jquery.typeahead.min.css") }}

    <style>
        /*center buttons and bump down so not so close to bottom border*/
        .dataTables_wrapper .dt-buttons {
            float:none;
            text-align:center;
            padding-top: 10px;
        }

        /*hides sorting arrows*/
        table.dataTable thead .sorting,
        table.dataTable thead .sorting_asc,
        table.dataTable thead .sorting_desc {
            background : none;
        }

    </style>

@endsection

    <p>
        {{--event full notice--}}
        @if($event->isFull())
            <span class="event-full">{{ trans('labels.event.full') }}</span>
        @endif
        {{--Class Size/Enrollment/Waitlist/ParkingLot Counts--}}
        {!! $event->DisplayEventUserCounts !!}
    </p>

    <div class="table-responsive">
        <table id="waitlist-table" class="table table-condensed table-hover indent_first_child" width="100%">
            <thead>
            <tr>
                <th>{{ trans('labels.general.role') }}</th>
                <th>{{ trans('labels.general.name') }}</th>
                <th>{{ trans('labels.scheduling.req_date') }}</th>
                <th>{{ trans('labels.event.notes') }}</th>
                <th>{{ trans('labels.general.actions') }}</th>
                <th>{{ trans('labels.backend.access.users.table.first_name') }}</th> {{--this header is only here for data export--}}
                <th>{{ trans('labels.backend.access.users.table.last_name') }}</th> {{--this header is only here for data export--}}
                <th>{{ trans('labels.backend.access.users.table.email') }}</th> {{--this header is only here for data export--}}
                <th>{{ trans('labels.general.role') }}</th> {{--this header is only here for data export--}}
            </tr>
            </thead>
        </table>
    </div><!--table-responsive-->


{{--DISPLAY ALL USER EMAILS AND CLICK/COPY--}}
<span id = "allWaitListEmails" style="display: none">{{ $event->emailsByRoleAndStatus(0,3) }}</span>
<p class="text-center"><a href="#" onclick="copyToClipboard('#allWaitListEmails')">{{ trans('labels.event.email_all') }}</a></p>




@section('after-scripts')


@endsection

@section('after-styles')

@endsection
