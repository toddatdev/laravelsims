@extends('frontend.layouts.app')

@section('content')

    <div id="agenda-view">
        <div class="card">
            <div class="card-body">
                <div class="container-fluid">
                    <form id="form-search" class="form-horizontal" onsubmit="return false">

                        {{--These two hidden fields are used in calendar.js to pass translated strings for button text --}}
                        {!! Form::hidden('display_notes_text', trans('buttons.calendar.display_notes')) !!}
                        {!! Form::hidden('hide_notes_text', trans('buttons.calendar.hide_notes')) !!}

                        <div class="form-group row">
                            <div class="col-sm-4">
                                {{ Form::label('start-date', trans('labels.calendar.start_date')) }}
                                <input type="date" class="form-control" id="start-date" placeholder="{{ trans('labels.general.date') }}" value="{{ \Carbon\Carbon::now()->toDateString() }}">
                            </div>
                            <div class="col-sm-4">
                                {{ Form::label('end-date', trans('labels.calendar.end_date')) }}
                                <input type="date" class="form-control" id="end-date" placeholder="{{ trans('labels.general.date') }}" value="{{ \Carbon\Carbon::now()->addDays(7)->toDateString() }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                {{ Form::label('building', trans('labels.calendar.building')) }}
                                {{ Form::select('building', ['all' => trans('labels.calendar.all_buildings')] + $buildings, null, ['class' => 'form-control', 'id' => 'building']) }}
                            </div>
                            <div class="col-sm-4">
                                {{ Form::label('location', trans('labels.calendar.location')) }}
                                {{ Form::select('location-agenda', ['all' => trans('labels.calendar.all_locations')] + $locations, null, ['class' => 'form-control', 'id' => 'location-agenda']) }}
                            </div>
                            <div class="col-sm-4">
                                {{--Do not display if nothing in status lookup table--}}
                                @if(!$statusTypes->isEmpty())
                                    {{ Form::label('status', trans('labels.scheduling.status')) }}
                                    {{ Form::select('status_id', $statusTypes->pluck('description', 'id'), '', ['class' => 'form-control', 'placeholder' => trans('labels.general.all'), 'id' => 'status_id']) }}
                                @endif
                             </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4">
                                @permission('scheduling')
                                <input type="checkbox" name="resolved" value="false" id="resolved">
                                <label for="resolved">{{ trans('labels.calendar.only_unresolved') }}</label>
                                @endauth
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-12" for="search">{{ trans('labels.general.search') }}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="search">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3" style="padding-right:10px">
                                <input id="submit" name="submit" type="submit" value="{{ trans('labels.general.buttons.submit') }}" class="btn btn-primary">
                                <button id="clear" name="clear" class="btn btn-primary">{{ trans('labels.general.buttons.clear') }}</button>
                            </div>
                        </div>

                    </form>

                </div>

                <hr>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="agenda-table" class="table table-condensed dt-responsive" style="width:100%">
                            <thead>
                            <tr>
                                <th class="all">{{ trans('labels.calendar.building') }}</th>
                                <th>{{ trans('labels.calendar.location') }}</th>
                                <th class="all">{{ trans('labels.calendar.course_abbrv') }}</th>
                                <th class="all"></th> {{-- Color --}}
                                <th></th> {{-- StatusType --}}
                                <th></th> {{-- Specialist --}}
                                <th></th> {{-- Special requirements --}}
                                <th></th> {{-- not resolved --}}
                                <th>{{ trans('labels.calendar.initial_meeting_room') }}</th>
                                {{-- We are hiding this for now -jl --}}
                                {{-- <th>{{ trans('labels.calendar.event_group') }}</th> --}}
                                <th class="all">{{ trans('labels.general.date') }}</th>
                                <th></th> {{-- Date Sort (hidden) --}}
                                <th>{{ trans('labels.calendar.start_time') }}</th>
                                <th>{{ trans('labels.calendar.end_time') }}</th>
                                <th>{{ trans('labels.general.actions') }}</th>
                                {{-- For notes section (no header text required - it's in formatted text displayed),
                                class=none forces it into responsive child row --}}
                                <th class="none"></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ajax modal -->
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

        <script>
            $(document).ready(function() {

                // To Re-create Form input and populate w/ prev. request. Only happens when an action button (delete) fires to "appear" to stay on same page
                var popAgenda = localStorage.getItem('dispay_agenda');

                if (popAgenda) {
                    $('#start-date').val(localStorage.getItem('start_search'));
                    $('#end-date').val(localStorage.getItem('end_search'));
                    $('#form-search').trigger('submit');

                    //    localStorage.removeItem('dispay_agenda');
                }

                $('#resolved').click(function () {
                    if($('#resolved').val() == 'false') {
                        $('#resolved').val('true');
                    } else {
                        $('#resolved').val('false');
                    }
                });

                // allow tooltip for view
                $('[data-tooltip="tooltip"]').tooltip();

                if (!Modernizr.inputtypes.date) {
                    $('input[type=date]').datepicker({
                        // Consistent format with the HTML5 picker
                        dateFormat: 'yy-mm-dd'
                    });
                }

                $('select[name="location-agenda"]').empty();
                $('select[name="location-agenda"]').prepend('<option value="all">{{ trans('labels.calendar.all_locations') }}</option>');

                $('select[name="building"]').on('change', function(){
                    var building = $(this).val();
                    if(building) {
                        $.ajax({
                            url: '/calendar/agenda/locations/get/'+building,
                            type:"GET",
                            dataType:"json",
                            beforeSend: function(){
                            },

                            success:function(data) {

                                $('select[name="location-agenda"]').empty();
                                $('select[name="location-agenda"]').prepend('<option value="all">{{ trans('labels.calendar.all_locations') }}</option>');


                                $.each(data, function(key, value){

                                    $('select[name="location-agenda"]').append('<option value="'+ key +'">' + value + '</option>');

                                });
                            },

                        });
                    } else {
                        $('select[name="location-agenda"]').empty();
                        $('select[name="location-agenda"]').prepend('<option value="all">{{ trans('labels.calendar.all_locations') }}</option>');
                    }

                });

            });


            $('#form-search').on('submit', function(e) {
                // $(document).delegate('#form-search', 'submit', function(e) {
                // Get Start and End Date
                let start_date = $('#start-date').val();
                let end_date = $('#end-date').val();
                let building = $('#building').val();

                localStorage.setItem('start_search', start_date);
                localStorage.setItem('end_search', end_date);
                localStorage.setItem('dispay_agenda', true)

                e.preventDefault();
                if (!agendaTable) {
                    initAgenda('{!! url('agenda.data') !!}');
                } else {
                    agendaTable.clear();
                    agendaTable.destroy();
                    agendaTable = null;
                    initAgenda('{!! url('agenda.data') !!}');
                }
            });

            $('#clear').on('click', function(e) {
                // $(document).delegate('#clear', 'click', function(e) {
                e.preventDefault();
                if (agendaTable) {
                    $('#form-search')
                    agendaTable.clear();
                    agendaTable.destroy();
                    agendaTable = null;
                }
                // clear local stoarge vars
                localStorage.removeItem('start_search');
                localStorage.removeItem('end_search');
            });

            var eventToDelete;
            var content;
            //mitcks: set the name of the event being deleted to use in sweet alert
            $('#agenda-table').on('click', 'tbody tr', function () {
                var row = agendaTable.rows($(this)).data();
                eventToDelete = row[0]['courses.abbrv'] + ' - ' + row[0]['date'] + ' (' + row[0]['start_time'] + ' - ' + row[0]['end_time'] + ')';
                //mitcks: this is here to pass html to the sweet alert so event name is blue
                content = document.createElement('div');
                content.innerHTML = "{{ trans('alerts.general.confirm_delete_content') }}"+'</br><span style="font-weight:bolder; color:blue;">'+ eventToDelete +'</span>';
            } );

            // delete wall for delete event in agenda datatable
            $("body").on("click", "a[name='delete_event']", function(e) {
                e.preventDefault();
                var href = $(this).attr("href");
                swal({
                    title: "{{ trans('alerts.general.confirm_delete') }}",
                    content: content, //this is set above so it can include html
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then(function(isConfirmed) {
                        if (isConfirmed) {
                            window.location.href = href;
                        } else {
                        }
                    });
            });

        </script>

    </div>

@stop
