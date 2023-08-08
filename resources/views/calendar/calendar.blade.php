
@extends('frontend.layouts.app')

@section ('title', trans('labels.calendar.calendar') . ' | ' . trans('labels.calendar.week'))

{{ Html::style("/css/calendar.css") }}
{{ Html::style("/css/jquery-ui/jquery-ui.css") }}
{{ Html::style("/css/jquery-ui/jquery-ui-timepicker-addon.css") }}

@section('content')
    <section class="content">

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <!-- calendar header buttons -->
                        @include('calendar.partial-header-buttons')
                    </div>
                </div>

                <div class="card">

                    <!-- calendar app for month, day, week views. The panel heading doesn't refresh in these views for a smooth feel when hitting previous and next -->
                    <div id="calendar-view">

                        <div class="card-header text-center">
                            <h5 class="text-center"><a href="#" id="prev"><i class="fa fa-angle-left"></i></a> <b id="calendar-heading"></b> <a href="#" id="next"><i class="fa fa-angle-right"></i></a></h5>
                        </div>
                        <!-- ajax loaded calendar div -->
                        <div id="calendar">
                            <div class="loader center-block"><img class="loading-image" alt="{{ trans('labels.general.loading') }}" src="{{URL::asset('img/frontend/spinner.gif')}}"></div>
                        </div>
                    </div>

                    <!-- The header goes away in the agenda view, so we have a different div for it -->
                    <div id="agenda-view"></div>

                </div>
            </div>
        </div>
    </section>

{{--    mitcks 2020-10-30 - I don't think any of this modals are still being used, commeting out for now--}}
                    <!-- modal link / modal -->
{{--                    <a id="modal-link" data-toggle="modal" href="/event" data-target="#modal" style="display:none">Modal Link</a>--}}

{{--                    <div class="modal" id="modal" tabindex="-1" role="dialog" labelledby="remoteModalLabel" aria-hidden="true">--}}
{{--                        <div class="modal-dialog modal-lg" >--}}
{{--                            <div class="modal-content" >--}}
{{--                                <form action="" method="post">--}}
{{--                                    <div class="modal-body" >--}}
{{--                                    <h3 class="push-down-20" >{{ trans('labels.general.loading') }}</h3>--}}
{{--                                        <div class="progress progress-striped active">--}}
{{--                                            <div class="progress-bar" style="width: 100%"></div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </form>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}


    <!-- modal clone -->
{{--    <div class="modal" id="clone" tabindex="-1" role="dialog" labelledby="remoteModalLabel" aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-lg" >--}}
{{--            <div class="modal-content" >--}}
{{--                <form action="" method="post">--}}
{{--                    <input type="hidden" id="cloneInstanceId" value="" />--}}
{{--                    <div class="modal-header">--}}
{{--                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>--}}
{{--                            <h4 class="modal-title text-center"><b>Clone Course Instance</b></h4>--}}
{{--                    </div>--}}
{{--                    <div class="modal-body" >--}}
{{--                        <div class="form-group">--}}
{{--                            {{ Form::label('eventDate', trans('labels.scheduling.event_date'), ['class' => 'col-lg-3 control-label required']) }}--}}
{{--                            <div class="col-lg-2">--}}
{{--                                {{Form::date('cloneDate', \Carbon\Carbon::now())}}--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="modal-footer">--}}
{{--                        <div class="pull-left">--}}
{{--                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i></button>--}}
{{--                            <button id="cloneCI" type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-clone"></i></button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="modal fade add-event-modal" tabindex="-1" role="dialog" id="shortcutHelp">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <button type="button" class="close close-add-event-modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
{{--                    <h4 class="modal-title">Keyboard shortcuts</h4>--}}
{{--                </div>--}}

{{--                <div class="modal-body">--}}
{{--                    --}}{{-- ... --}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}



@stop



@section('after-scripts')

{{ Html::script("/js/modernizr.js") }}
{{ Html::script("/js/jquery-ui.js") }}
{{ Html::script("/js/jquery-ui-timepicker-addon.js") }}
{{ Html::script("/js/datetime.js") }}
{{ Html::script("/js/daypilot/daypilot-all.min.js") }}
{{-- {{ Html::script("/js/daypilot_daily_global.js") }} --}}
{{ Html::script("/js/sweetalert/sweetalert.min.js") }}
{{ Html::script("/js/calendar.js") }}
{{ Html::script("/js/moment-with-locales.js") }}

<script>

    // on document load - week view by default
    $(function() {
        // GET id= from url, if the id exists open up the modal so the client can see conversations 
        let event_id = "{{ app('request')->input('id') }}";
        if (event_id) {
            $('#modal-link').attr("href", "event/"+event_id)
            $('#modal-link').click();
        }

        // GET date= from url, if the date exists set the calendar to that day
        let calendar_date = "{{ app('request')->input('date') }}";
        if (calendar_date) {
            $('#date-input').val(calendar_date);
            // Go To Day View for Date, When coming from a Redirect 
            go(calendar_date)
            
        }else {
            var toAgenda = localStorage.getItem('dispay_agenda');
            if (toAgenda) {
                $('#agenda-show').trigger('click');                
            } else {
                $('#calendar-heading').text(monday($('#date-input').val()).format('ddd, MMMM D, YYYY'));
                ajaxLoad('{{url('calendar')}}/' + $('#current-view').val() + '/' + $('#date-input').val() + '/' + $('#location').val() + '/' + $('#rooms').val());                
            }
        }
    });


    // main date input for calendar
    $("#date-input").change(function() {
        switchView();

        if ($('#current-view').val() == 'week') {
            $('#calendar-heading').text(monday($('#date-input').val()).format('ddd, MMMM D, YYYY'));
        } else if ($('#current-view').val() == 'month') {
            $('#calendar-heading').text(moment($('#date-input').val()).format('MMMM YYYY'));
        } else if ($('#current-view').val() == 'day') {
            $('#calendar-heading').text(moment($('#date-input').val()).format('ddd, MMMM D, YYYY'));
        }

        ajaxLoad('{{url('calendar')}}/' + $('#current-view').val() + '/' + $('#date-input').val() + '/' + $('#location').val() + '/' + $('#rooms').val());
    });


    // main today button for calendar
    $('#today').click(function() {
        $('#date-input').val(moment().format('YYYY-MM-DD'));
        switchView();

        if ($('#current-view').val() == 'week') {
            $('#calendar-heading').text(monday($('#date-input').val()).format('ddd, MMMM D, YYYY'));
        } else if ($('#current-view').val() == 'month') {
            $('#calendar-heading').text(moment($('#date-input').val()).format('MMMM YYYY'));
        } else if ($('#current-view').val() == 'day') {
            $('#calendar-heading').text(moment($('#date-input').val()).format('ddd, MMMM D, YYYY'));
        }

        ajaxLoad('{{url('calendar')}}/' + $('#current-view').val() + '/' + $('#date-input').val() + '/' + $('#location').val() + '/' + $('#rooms').val());
    });



    // location dropdown
    $("#location").change(function() {
        switchView();
        ajaxLoad('{{url('calendar')}}/' + $('#current-view').val() + '/' + $('#date-input').val() + '/' + $('#location').val() + '/' + $('#rooms').val());
    });


    // room display button
    $('#rooms-display').click(function() {
        if ($('#rooms-display').text() == "{{ trans('labels.calendar.display_event_rooms') }}") {
            $('#rooms-display').html('{{ trans('labels.calendar.hide_event_rooms') }}');
            $('#rooms').val('true');
            switchView();
            ajaxLoad('{{url('calendar')}}/' + $('#current-view').val() + '/' + $('#date-input').val() + '/' + $('#location').val() + '/' + $('#rooms').val());
        } else {
            $('#rooms-display').html('{{ trans('labels.calendar.display_event_rooms') }}');
            $('#rooms').val('');
            switchView();
            ajaxLoad('{{url('calendar')}}/' + $('#current-view').val() + '/' + $('#date-input').val() + '/' + $('#location').val() + '/' + $('#rooms').val());
        }
    });



    // view dropdown links
    $('#week-show').click(function() {
        document.title = "{{ trans('labels.calendar.calendar') }} | {{ trans('labels.calendar.week') }}";
        $('#view').html('{{ trans('labels.calendar.week') }} <span class="caret"></span>');
        $('#current-view').val('week');
        switchView();
        $('#calendar-heading').text('{{ trans('labels.calendar.week_of') }} ' + monday($('#date-input').val()).format('ddd, MMMM D, YYYY'));
        $('#date-input').show();
        $('#today').show();
        $('#location').show();
        $('#rooms-display').show();
        ajaxLoad('{{url('calendar/week')}}' + '/' + $('#date-input').val() + '/' + $('#location').val() + '/' + $('#rooms').val());
    });

    $('#day-show').click(function() {
        document.title = "{{ trans('labels.calendar.calendar') }} | {{ trans('labels.calendar.day') }}";
        $('#view').html('{{ trans('labels.calendar.day') }} <span class="caret"></span>');
        $('#current-view').val('day');
        switchView();
        $('#calendar-heading').text(moment($('#date-input').val()).format('ddd, MMMM D, YYYY'));
        $('#date-input').show();
        $('#today').show();
        $('#location').show();
        $('#rooms-display').hide();
        ajaxLoad('{{url('calendar/day')}}' + '/' + $('#date-input').val() + '/' + $('#location').val() + '/' + $('#rooms').val());
    });

    // Load day View for date
    function go(date) {
        document.title = "{{ trans('labels.calendar.calendar') }} | {{ trans('labels.calendar.day') }}";
        $('#view').html('{{ trans('labels.calendar.day') }} <span class="caret"></span>');
        $('#current-view').val('day');
        switchView();
        $('#calendar-heading').text(moment(date).format('ddd, MMMM D, YYYY'));
        $('#date-input').show();
        $('#today').show();
        $('#location').show();
        $('#rooms-display').hide();
        ajaxLoad('{{url('calendar/day')}}' + '/' + date + '/' + $('#location').val() + '/' + $('#rooms').val());
    }

    $('#month-show').click(function() {
        document.title = "{{ trans('labels.calendar.calendar') }} | {{ trans('labels.calendar.month') }}";
        $('#view').html('Month <span class="caret"></span>');
        $('#current-view').val('month');
        switchView();
        $('#calendar-heading').text(moment($('#date-input').val()).format('MMMM YYYY'));
        $('#date-input').show();
        $('#today').show();
        $('#location').show();
        $('#rooms-display').show();
        ajaxLoad('{{url('calendar/month')}}' + '/' + $('#date-input').val() + '/' + $('#location').val() + '/' + $('#rooms').val());
    });

    $('#agenda-show').click(function() {
        document.title = "{{ trans('labels.calendar.calendar') }} | {{ trans('labels.calendar.agenda') }}";
        $('#view').html('{{ trans('labels.calendar.agenda') }} <span class="caret"></span>');
        $('#current-view').val('agenda');
        switchView();
        $('#date-input').hide();
        $('#today').hide();
        $('#location').hide();
        $('#rooms-display').hide();
        ajaxLoad('{{url('calendar/agenda')}}');
    });



    // previous and next buttons for month / week
    $('#prev').click(function() {
        if ($('#current-view').val()== 'month') {
            var prevDate = moment($('#date-input').val()).subtract(1, 'months').format('YYYY-MM-DD');
            switchView();
            $('#date-input').val(prevDate);
            $('#calendar-heading').text(moment($('#date-input').val()).format('MMMM YYYY'));
            ajaxLoad('{{url('calendar')}}/' + $('#current-view').val() + '/' + prevDate + '/' + $('#location').val() + '/' + $('#rooms').val());

        } else if ($('#current-view').val() == 'week') {
            var prevDate = monday($('#date-input').val()).subtract(7, 'days').format('YYYY-MM-DD');
            switchView();
            $('#date-input').val(prevDate);
            $('#calendar-heading').text(moment(prevDate).format('ddd, MMMM D, YYYY'));
            ajaxLoad('{{url('calendar')}}/' + $('#current-view').val() + '/' + prevDate + '/' + $('#location').val() + '/' + $('#rooms').val());

        } else if ($('#current-view').val() == 'day') {
            var prevDate = moment($('#date-input').val()).subtract(1, 'days').format('YYYY-MM-DD');
            switchView();
            $('#date-input').val(prevDate);
            $('#calendar-heading').text(moment(prevDate).format('ddd, MMMM D, YYYY'));
            ajaxLoad('{{url('calendar')}}/' + $('#current-view').val() + '/' + prevDate + '/' + $('#location').val() + '/' + $('#rooms').val());
        }
    });


    $('#next').click(function() {
        if ($('#current-view').val() == 'month') {
            var nextDate = moment($('#date-input').val()).add(1, 'months').format('YYYY-MM-DD');
            switchView();
            $('#date-input').val(nextDate);
            $('#calendar-heading').text(moment($('#date-input').val()).format('MMMM YYYY'));
            ajaxLoad('{{url('calendar')}}/' + $('#current-view').val() + '/' + nextDate + '/' + $('#location').val() + '/' + $('#rooms').val());

        } else if ($('#current-view').val() == 'week') {
            var nextDate = monday($('#date-input').val()).add(7, 'days').format('YYYY-MM-DD');
            switchView();
            $('#date-input').val(nextDate);
            $('#calendar-heading').text(moment(nextDate).format('ddd, MMMM D, YYYY'));
            ajaxLoad('{{url('calendar')}}/' + $('#current-view').val() + '/' + nextDate + '/' + $('#location').val() + '/' + $('#rooms').val());

        } else if ($('#current-view').val() == 'day') {
            var nextDate = moment($('#date-input').val()).add(1, 'days').format('YYYY-MM-DD');
            switchView();
            $('#date-input').val(nextDate);
            $('#calendar-heading').text(moment(nextDate).format('ddd, MMMM D, YYYY'));
            ajaxLoad('{{url('calendar')}}/' + $('#current-view').val() + '/' + nextDate + '/' + $('#location').val() + '/' + $('#rooms').val());
        }
    });



    // gotoDate (show day view) on click calendar date click
    function gotoDay(gotoDate) {
        document.title = "{{ trans('labels.calendar.calendar') }} | {{ trans('labels.calendar.day') }}"
        $('#view').html('{{ trans('labels.calendar.day') }} <span class="caret"></span>');
        $('#current-view').val('day');
        switchView();
        $('#date-input').show();
        $('#today').show();
        $('#location').show();
        $('#rooms-display').hide();
        var inputDate = moment(gotoDate, 'YYYY-MM-DD').format('YYYY-MM-DD');
        var headingDate = moment(gotoDate, 'YYYY-MM-DD').format('ddd, MMMM D, YYYY');
        $('#date-input').val(inputDate);
        $('#calendar-heading').text(headingDate);
        ajaxLoad('{{url('calendar/day')}}' + '/' + $('#date-input').val() + '/' + $('#location').val() + '/' + $('#rooms').val());
    };


    //ajax reload the divs according to view
    function ajaxLoad(url) {
        $.when(
            $.get(url, function (data) {
                if ($('#current-view').val() == 'agenda') {
                    var elem = $(data).find('#agenda-view');
                    $('#agenda-view').replaceWith(elem);
                } else {
                    var elem = $(data).find('#calendar');
                    $('#calendar').replaceWith(elem);
                }
            })
        );
    }

    // switches view and displays the spinner
    function switchView() {
        if ($('#current-view').val() == 'agenda') {
            $('#calendar-view').hide();
            $('#agenda-view').html('<div class="loader center-block"><img class="loading-image" alt="{{ trans('labels.general.loading') }}" src="{{URL::asset('img/frontend/spinner.gif')}}"></div>');
            $('#agenda-view').show();

        } else {
            // Delete key when user swithes to a view that isn't agenda. We don't need to store their req. anymore
            localStorage.removeItem('dispay_agenda');
            $('#agenda-view').hide();
            $('#calendar').html('<div class="loader center-block"><img class="loading-image" alt="{{ trans('labels.general.loading') }}" src="{{URL::asset('img/frontend/spinner.gif')}}"></div>');
            $('#calendar-view').show();
        }
    }



    // sets the date for monday and returns a moment() date.
    function monday(date) {
        var myIsoWeekDay = 1; // say our weeks start on tuesday, for monday you would type 1, etc.
        var startOfPeriod = moment(date);
        // how many days do we have to substract?
        var daysToSubtract = moment(startOfPeriod).isoWeekday() >= myIsoWeekDay ?
            moment(startOfPeriod).isoWeekday() - myIsoWeekDay :
            7 + moment(startOfPeriod).isoWeekday() - myIsoWeekDay;
        // subtract days from start of period
        return moment(startOfPeriod).subtract(daysToSubtract, 'd');
    }

    // add class link
    $('#addClass').click(function() {
        
        window.location.href = '{{ url('courseInstance/main/create') }}?date=' + $('#date-input').val();
        // window.location.href = '{{ url('courseInstance/create') }}?date=' + $('#date-input').val();
    });

    // clone CI
    $('#cloneCI').click(function(event) {
        event.preventDefault();
        let cloneDate = $('[name="cloneDate"]').val();
        let courseInstanceID = $('#cloneInstanceId').val();
        window.location.href = "/courseInstance/clone/"+courseInstanceID+"/"+cloneDate;
    });

    // modal reload fix
    $(document).on('hidden.bs.modal', function (e) {
        $(e.target).removeData('bs.modal')
        .find(".modal-content").html('<form action="" method="post"><div class="modal-body" ><h3 class="push-down-20" >{{ trans('labels.general.loading') }}</h3><div class="progress progress-striped active"><div class="progress-bar" style="width: 100%"></div></div></div></form>');
    });


</script>

@stop
