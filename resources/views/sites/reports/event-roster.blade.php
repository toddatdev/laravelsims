@extends('frontend.layouts.app')

@section ('title', trans('navs.frontend.reports.roster'))

@section('after-styles')
    {{ Html::style("/css/jquery-ui/jquery-ui.css") }}
    {{ Html::style("/css/jquery-ui/jquery-ui-timepicker-addon.css") }}
    {{ Html::style("/css/jquery.typeahead.min.css") }}
    {{ Html::style("/css/DataTables/datatables.css") }}
    <style>
        span.twitter-typeahead .tt-menu,
        span.twitter-typeahead .tt-dropdown-menu {
            cursor: pointer;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none;
            float: left;
            min-width: 160px;
            padding: 5px 0;
            margin: 2px 0 0;
            list-style: none;
            font-size: 14px;
            text-align: left;
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            background-clip: padding-box;
        }

        span.twitter-typeahead .tt-suggestion {
            display: block;
            padding: 3px 20px;
            clear: both;
            font-weight: normal;
            line-height: 1.42857143;
            color: #333333;
            white-space: nowrap;
        }

        span.twitter-typeahead .tt-suggestion.tt-cursor,
        span.twitter-typeahead .tt-suggestion:hover,
        span.twitter-typeahead .tt-suggestion:focus {
            color: #ffffff;
            text-decoration: none;
            outline: 0;
            background-color: #337ab7;
        }

        .input-group.input-group-lg span.twitter-typeahead .form-control {
            height: 46px;
            padding: 10px 16px;
            font-size: 18px;
            line-height: 1.3333333;
            border-radius: 6px;
        }

        .input-group.input-group-sm span.twitter-typeahead .form-control {
            height: 30px;
            padding: 5px 10px;
            font-size: 12px;
            line-height: 1.5;
            border-radius: 3px;
        }

        span.twitter-typeahead {
            width: 100%;
        }

        .input-group span.twitter-typeahead {
            display: block !important;
            height: 34px;
        }

        .input-group span.twitter-typeahead .tt-menu,
        .input-group span.twitter-typeahead .tt-dropdown-menu {
            top: 32px !important;
        }

        .input-group span.twitter-typeahead:not(:first-child):not(:last-child) .form-control {
            border-radius: 0;
        }

        .input-group span.twitter-typeahead:first-child .form-control {
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group span.twitter-typeahead:last-child .form-control {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }

        .input-group.input-group-sm span.twitter-typeahead {
            height: 30px;
        }

        .input-group.input-group-sm span.twitter-typeahead .tt-menu,
        .input-group.input-group-sm span.twitter-typeahead .tt-dropdown-menu {
            top: 30px !important;
        }

        .input-group.input-group-lg span.twitter-typeahead {
            height: 46px;
        }

        .input-group.input-group-lg span.twitter-typeahead .tt-menu,
        .input-group.input-group-lg span.twitter-typeahead .tt-dropdown-menu {
            top: 46px !important;
        }
    </style>
@stop
@php
    $startOfWeekDate = \Carbon\Carbon::now()->startOfWeek()->subDay()->format('m/d/Y');
    $lastWeekDate = \Carbon\Carbon::now()->startOfWeek()->subWeek()->format('m/d/Y');
    $startOfMonthDate = \Carbon\Carbon::now()->startOfMonth()->subDay()->format('m/d/Y');
    $lastMonthDate = \Carbon\Carbon::now()->startOfMonth()->subMonth()->format('m/d/Y');
@endphp

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ trans('navs.frontend.reports.roster')}}</h3>
            </div>
            <div class="card-body">
                <form id="search-form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-3" style="padding-top: 1px; text-align: right">
                                    <label style="font-size: 13px">{{ trans('labels.reports.date_range') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="txtStartDate" class="form-control form-control-sm" placeholder="From"
                                           name="from"
                                           value="{{ $lastMonthDate }}" required>
                                </div>
                                <div class="col-md-1" style="padding-top: 1px; text-align: center">
                                    <label style="font-size: 13px">{{ trans('labels.reports.to') }}</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="txtEndDate" class="form-control form-control-sm" placeholder="To" name="to"
                                           value="{{ $startOfMonthDate }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <select class="form-control form-control-sm" name="filter" id="filter">
                                        <option value="{{ $lastWeekDate }},{{ $startOfWeekDate }}">{{ trans('labels.reports.last_week') }}</option>
                                        <option value="{{ $lastMonthDate }},{{ $startOfMonthDate }}"
                                                selected>{{ trans('labels.reports.last_month') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    {{ Form::select('building_id', ['all' => trans('labels.reports.all_buildings')] + $buildings, null, ['class' => 'form-control form-control-sm', 'id' => 'building']) }}
                                </div>
                                <div class="col-md-3 form-group">
                                    {{ Form::select('location_id', ['all' => trans('labels.reports.all_locations')] , null, ['class' => 'form-control form-control-sm', 'id' => 'location-report']) }}
                                </div>
                                <div class="col-md-3 form-group">
                                    {{ Form::select('course_id', ['all' => trans('labels.reports.all_courses')] + $courses, null, ['class' => 'form-control form-control-sm', 'id' => 'course-report']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="submit"
                                    class="btn btn-success btn-block btn-sm">{{ trans('labels.general.buttons.submit') }}</button>
                        </div>
                    </div>
                </form>
                <table id="reports-table" class="table table-condensed table-hover" width="100%">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.reports.event_id') }}</th>
                        <th>{{ trans('labels.reports.course') }}</th>
                        <th>{{ trans('labels.reports.class_date') }}</th>
                        <th>{{ trans('labels.reports.name') }}</th>
                        <th>{{ trans('labels.reports.encounter_hours') }}</th>
                        <th>{{ trans('labels.reports.class_role') }}</th>
                        <th>{{ trans('labels.reports.email') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection


@section('after-scripts')

    {{ Html::script("/js/DataTables/datatables.js") }}
    {{ Html::script("/js/sweetalert/sweetalert.min.js") }}
    {{ Html::script("/js/modernizr.js") }}
    {{ Html::script("/js/jquery-ui.js") }}
    {{ Html::script("/js/jquery-ui-timepicker-addon.js") }}
    {{ Html::script("/js/jquery.typeahead.bundle.js") }}
    {{ Html::script("/js/datetime.js") }}

    {{--for latest pro font awesome icons--}}
    {{--<script src="https://kit.fontawesome.com/ede0b991ce.js" crossorigin="anonymous"></script>--}}

    <script>

        $(function () {
            function getLast(day = 7) {
                var today = new Date();
                var lastWeek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - day);
                return lastWeek;
            }

            $('#txtStartDate, #txtEndDate').datepicker({
                beforeShow: function (input) {
                    if (input.id === 'txtEndDate') {
                        var minDate = new Date($('#txtStartDate').val());
                        minDate.setDate(minDate.getDate() + 1)
                        return {
                            minDate: minDate
                        };
                    }
                    return {}
                },
                dateFormat: "mm/dd/yy",
            });

            $('#filter').change(function (e) {
                let val = $(this).val().split(',');
                $('#txtStartDate').val(val[0]);
                $('#txtEndDate').val(val[1]);
                $('#search-form').submit();
            });

            function loadDataTable(q = '') {
                return $('#reports-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    ajax: `{{ route('reports-event-rosters') }}?${q}`,
                    lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],

                    //Add the buttons to download the table data.
                    buttons: ['excelHtml5', 'pdf', 'copy', 'csv'],

                    dom: '<"top"fB>rt<"bottom"lp><"clear">',
                    columns: [
                        {data: 'event_id', name: 'event_id'},
                        {data: 'course', name: 'course'},
                        {data: 'class_date', name: 'class_date'},
                        {data: 'name', name: 'name'},
                        {data: 'encounter_hours', name: 'encounter_hours'},
                        {data: 'class_role', name: 'class_role'},
                        {data: 'email', name: 'email'},
                        {
                            data: 'action',
                            name: 'action',
                            orderable: true,
                            searchable: true
                        },
                    ],
                    columnDefs: [{
                        defaultContent: "",
                        targets: "_all"
                    }]
                });
            }

            var table = null;

            $('#search-form').submit(function (e) {
                e.preventDefault();
                if (table !== null) {
                    table.destroy();
                }

                table = loadDataTable($(this).serialize());
            });

            $('#search-form').submit();

            $('select[name="building_id"]').on('change', function () {
                var building = $(this).val();
                if (building) {
                    $.ajax({
                        url: '/calendar/agenda/locations/get/' + building,
                        type: "GET",
                        dataType: "json",
                        beforeSend: function () {
                        },

                        success: function (data) {

                            $('select[name="location_id"]').empty();
                            $('select[name="location_id"]').prepend('<option value="all">{{ trans('labels.reports.all_locations') }}</option>');


                            $.each(data, function (key, value) {

                                $('select[name="location_id"]').append('<option value="' + key + '">' + value + '</option>');

                            });
                        },

                    });

                } else {
                    $('select[name="location_id"]').empty();
                    $('select[name="location_id"]').prepend('<option value="all">{{ trans('labels.reports.all_locations') }}</option>');
                }

            });
        });

    </script>
@endsection