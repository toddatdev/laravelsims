    <!-- header buttons panel -->
{{--    <div class="card card-default">--}}
{{--        <div class="card-header clearfix" style="padding-top:23px">--}}

            <form role="form" class="form-inline">

                <div class="row col-lg-12">
                    <div class="col-lg-5 form-group">
                        <div class="form-group">
                            <!-- date input -->
                        <div class="form-group">
                            <label for="date-input" class="sr-only">{{ trans('labels.general.date') }}</label>
                            <input type="date" class="date-select form-control form-control-inline" id="date-input" placeholder="{{ trans('labels.general.date') }}" value="{{ \Carbon\Carbon::now()->toDateString() }}">
                            <button class="btn btn-primary" type="button" id="today">{{ trans('labels.calendar.today') }}</button>
                        </div>

                        <!-- icons for add class, add request, pending requests -->
                        <div class="form-group ml-20 mr-20 ">
                            @permission('scheduling')
                                <span class="simptip-position-top simptip-smooth" data-tooltip="{{ trans('navs.frontend.scheduling.add_class') }}">
                                    <a id="addClass" href="#"><i class="fad fa-2x fa-calendar-plus addIcon mr-2"></i> </a>
                                </span>
                            @endauth

                            @permission('schedule-request')
                                <span class="simptip-position-top simptip-smooth" data-tooltip="{{ trans('navs.frontend.scheduling.add_request') }}">
                                    <a href="{{ url('scheduleRequest/create') }}"><i class="fad fa-2x fa-notes-medical color-requests-user mr-2"></i></a>
                                </span>
                            @endauth

                            @permission('scheduling')
                                <span class="simptip-position-top simptip-smooth" data-tooltip="{{ trans('navs.frontend.scheduling.pending') }}">
                                    <span @if($logged_in_user) @if($pendingRequestCount != 0) data-notifications="{{$pendingRequestCount}}" @endif @endif>
                                        <a href="{{ url('scheduleRequest/pending') }}"><i class="fad fa-2x fa-clipboard-check text-info"></i> </a>
                                    </span>
                                </span>
                            @endauth
                    `   </div>
                    </div>
                    </div>

                    <div class="col-lg-5 form-group">
                        <div class="form-group">
                            <!-- location sort -->
                            <label for="location" class="sr-only">{{ trans('labels.general.location') }}</label>
                            {{ Form::select('location', ['all' => trans('labels.calendar.all_locations')] + $locations, null, ['class' => 'form-control location-select', 'id' => 'location']) }}
                            <!-- display event rooms button -->
                            <button class="btn btn-primary ml-lg-2" type="button" id="rooms-display">{{ trans('labels.calendar.display_event_rooms') }}</button>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group pull-left-xs pull-right-xs pull-right-not-sm pull-right-not-md pull-right-lg view-button">
                            <!-- view button dropdown -->
                            <div class="dropdown form-control-inline">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" id="view">{{ trans('labels.calendar.week') }}
                                <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a id="week-show" href="#">{{ trans('labels.calendar.week') }}</a></li>
                                    <li><a id="day-show" href="#">{{ trans('labels.calendar.day') }}</a></li>
                                    <li><a id="month-show" href="#">{{ trans('labels.calendar.month') }}</a></li>
                                    <li><a id="agenda-show" href="#">{{ trans('labels.calendar.agenda') }}</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
                
                <!-- hidden inputs to determine view and rooms display -->
                <input type="hidden" id="current-view" value="week">
                <input type="hidden" id="rooms" value="">

            </form>
{{--        </div>--}}

{{--    </div> <!-- /panel -->--}}