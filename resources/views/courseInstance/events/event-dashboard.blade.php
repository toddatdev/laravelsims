@extends('frontend.layouts.app')

@section ('title', trans('navs.frontend.event.dashboard'))
@section('after-styles')
    <style>
        [data-toggle="collapse"] .fa:before {
            content: "\f0d7";
        }

        [data-toggle="collapse"].collapsed .fa:before {
            content: "\f0da";
        }
        .carousel-fade .carousel-item {
            opacity: 0;
            transition-duration: 50ms;
            transition-property: opacity;
        }

        .carousel-fade .carousel-item.active,
        .carousel-fade .carousel-item-next.carousel-item-left,
        .carousel-fade .carousel-item-prev.carousel-item-right {
            opacity: 1;
        }

        .carousel-fade .active.carousel-item-left,
        .carousel-fade .active.carousel-item-right {
            opacity: 0;
        }

        .carousel-fade .carousel-item-next,
        .carousel-fade .carousel-item-prev,
        .carousel-fade .carousel-item.active,
        .carousel-fade .active.carousel-item-left,
        .carousel-fade .active.carousel-item-prev {
            transform: translateX(0);
            transform: translate3d(0, 0, 0);
        }
    </style>
@stop
@section('page-header')

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{!! $message !!}</strong>
        </div>
    @endif
    <h4>{{ trans('navs.frontend.event.dashboard')}}</h4>
@endsection

@section('content')

    {{ Html::script("/js/jquery.js") }}

    {{--2020-04-07 mitcks: IMPORTANT - note the defer tag here that forces them to load AFTER jquery,
        otherwise all sorts of errors, no idea why this is needed on this page and not others
        @section('after-scripts') wasn't working as it should have either --}}
    {{--2020-11-20 mitcks commenting out dataTables should now work with the one from mixed files--}}
    {{--<script src="/js/DataTables/datatables.js" defer></script>--}}
    <script src="/js/sweetalert/sweetalert.min.js" defer></script>

    <section class="content">
        @php($title_string = $event->courseInstance->course->name.' ('.$event->courseInstance->course->abbrv .') ' . date_create($event->start_time)->format('m/d/Y g:iA'))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="border-bottom: 5px solid {{ $event->eventColor() }};">
                        <div class="row">
                            <div class="col-md-7">
                                <h3 class="card-title text-bold">{{ $event->DisplayEventNameShort }}</strong>
                                    @if($event->isFull())
                                        <span class="event-full">{{ trans('labels.event.full') }}</span>
                                    @endif
                                </h3>
                            </div>
                            <div class="col-md-1">
                                <div class="btn-group text-md-center">
                                    {{-- icons aligned center --}}
                                    {!! $event->statusImage() !!}{!! $event->SimSpecialistNeededYN() !!}{!! $event->specialRequirementsNeeded() !!}{!! $event->notResolved() !!}                                </div>
                                </div>
                            <div class="col-md-4">
                                <div class="text-md-right">
                                {{-- calendar and other admin buttons aligned right --}}
                                {!! $event->event_dashboard_buttons !!}
                                </div>
                            </div>
                        </div>
                    </div> <!--card header-->

                    <div class="card-body">
                        <div class="row">
                            @if($logged_in_user) <!--do not need to display vertical tabs when not logged in-->
                                <div class="col-5 col-lg-2 col-sm-3">
                                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                    {{--INFO TAB: everyone can see tab, content displayed there depends on permissions set in partial--}}
                                    <a class="nav-link @if($tab == 'info' || $logged_in_user == "") active @endif" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-info" role="tab" aria-controls="vert-tabs-home" aria-selected="true">{{ trans('labels.eventTabs.info') }}</a>
                                    {{--User must be logged in to see any of the remaining tabs--}}
                                    @if($logged_in_user)

                                        {{--COMMENTS TAB CONTENT: only the event requester and those *add-event-comment permissions can see tab--}}
                                        @if($event->requestedBy(true) == $logged_in_user->id or $event->hasSiteCourseEventPermissions(['add-event-comment'], ['course-add-event-comment'], ['event-add-event-comment']))
                                            <a class="nav-link @if($tab == 'comments') active @endif" id="vert-tabs-comments-tab" data-toggle="pill" href="#vert-tabs-comments" role="tab" aria-controls="vert-tabs-comments" aria-selected="false">{{ trans('labels.eventTabs.comments') }}</a>
                                        @endif

                                        {{--ROSTER TAB: only those with *add-people-to-events permission can see tab--}}
                                        {{-- 2021-02-17 mitcks: adding *mark-event-attendance permisson to view this tab--}}
                                        @if($event->hasSiteCourseEventPermissions(['add-people-to-events'], ['course-add-people-to-events'], ['event-add-people-to-events'])
                                            or $event->hasSiteCourseEventPermissions(['site-mark-event-attendance'], ['course-mark-event-attendance'], ['event-mark-event-attendance']))
                                            <a class="nav-link @if($tab == 'roster') active @endif" id="vert-tabs-roster-tab" data-toggle="pill" href="#vert-tabs-roster" role="tab" aria-controls="vert-tabs-roster" aria-selected="false">{{ trans('labels.eventTabs.roster') }}</a>
                                        @endif

                                        {{--WAITLIST TAB: only those with *add-people-to-events permission can see tab--}}
                                        @if($event->hasSiteCourseEventPermissions(['add-people-to-events'], ['course-add-people-to-events'], ['event-add-people-to-events']))
                                            <a class="nav-link @if($tab == 'waitlist') active @endif" id="vert-tabs-waitlist-tab" data-toggle="pill" href="#vert-tabs-waitlist" role="tab" aria-controls="vert-tabs-waitlist" aria-selected="false">{{ trans('labels.eventTabs.waitlist') }}</a>
                                        @endif

                                        {{--EMAIL TAB: only those with *manage-event-emails permission can see tab--}}
                                        @if($event->hasSiteCourseEventPermissions(['manage-event-emails'], ['course-manage-event-emails'], ['event-manage-event-emails']))
                                            <a class="nav-link @if($tab == 'email') active @endif" id="vert-tabs-email-tab" data-toggle="pill" href="#vert-tabs-email" role="tab" aria-controls="vert-tabs-email" aria-selected="false">{{ trans('labels.eventTabs.email') }}</a>
                                        @endif

                                        {{--VIEWER TYPE TABS (Modules): Dynamically handle viewer types and permissions--}}
                                        @foreach($viewerTypes as $viewerType)
                                            @if($courseCurriculum->where('viewer_type_id', $viewerType->id)->first())
                                                @if($event->hasSiteCourseEventPermissions(['view-'.strtolower($viewerType->description).'-curriculum'], ['course-view-'.strtolower($viewerType->description).'-curriculum'], ['event-view-'.strtolower($viewerType->description).'-curriculum']))
                                                    <a class="nav-link @if($tab == strtolower($viewerType->description)) active @endif"
                                                       id="vert-tabs-{{strtolower($viewerType->description)}}-tab" data-toggle="pill"
                                                       href="#{{strtolower($viewerType->description)}}"
                                                       role="tab" aria-controls="vert-tabs-{{strtolower($viewerType->description)}}" aria-selected="false">
                                                        {{ trans('labels.eventTabs.' . strtolower($viewerType->description)) }}
                                                    </a>
                                                @endif
                                            @endif
                                        @endforeach

                                        {{--QSE TAB: only those with *manage-qse permission can see tab--}}
                                        {{-- Only display if QSE Exist for Course--}}
                                        @if($event->getQSE()->isNotEmpty())
                                            @if($event->hasSiteCourseEventPermissions(['manage-qse'], ['course-manage-qse'], ['event-manage-qse']))
                                                <a class="nav-link @if($tab == 'qse') active @endif" id="vert-tabs-qse-tab" data-toggle="pill" href="#vert-tabs-qse" role="tab" aria-controls="vert-tabs-qse" aria-selected="false">{{ trans('labels.eventTabs.qse') }}</a>
                                            @endif
                                        @endif

                                    @endif
                                </div>
                                </div>
                            @endif
                            <div class="col-7 col-lg-10 col-sm-9">
                            <div class="tab-content" id="vert-tabs-tabContent">
                                <!--INFO TAB CONTENT-->
                                <div class="tab-pane text-left @if($tab == 'info') fade show active @endif" id="vert-tabs-info" role="tabpanel" aria-labelledby="#vert-tabs-info">
                                    <div class="container">
                                        {{--IMR, Event Rooms and Comments Can be Viewed by Everyone--}}
                                        <div class="row mb-2">
                                            <label class="col-md-3 control-label text-md-right"><a href="mailto:?subject={{ $event->DisplayEventName }}&body={{ $mailToBody }}"
                                               target="_blank" rel="noopener noreferrer">
                                                <i class="fa fa-envelope"></i></a> {{ trans('labels.event.course') }}</label>
                                            <div class="col-md-9 text-left">
                                                <a href="/courses/catalogShow/{{ $event->CourseInstance->course_id }}">{{ $event->CourseNameAndAbbrv }}</a>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            {{ Form::label('date', trans('labels.event.class_date'), ['class' => 'col-md-3 control-label text-md-right']) }}
                                            <div class="col-md-9 text-left">
                                                <a href="/calendar?date={{ Carbon\Carbon::parse($event->start_time)->format('Y-m-d') }}">{{ $event->DisplayDateStartEndTimes }}</a>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                        <label class="col-md-3 control-label text-md-right">{{ trans('labels.event.initial_meeting_room') }}</label>
                                            <div class="col-md-9 text-left">
                                                <a href="/locations/show/{{ $event->initialMeetingRoom->location->id }}">{{ $event->DisplayIMR }}</a>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label class="col-md-3 control-label text-md-right">{{ trans('labels.event.event_rooms') }}</label>
                                            <div class="col-md-9 text-left">{{ $event->getResources(1) }}</div>
                                        </div>

                                        @if($event->hasSiteCourseEventPermissions(['scheduling','event-details'], '', ''))

                                            @if($event->getResources(2) != null)
                                                <div class="row mb-2">
                                                    <label class="col-md-3 control-label text-md-right">{{ trans('labels.event.equipment') }}</label>
                                                    <div class="col-md-9 text-left">{{ $event->getResources(2) }}</div>
                                                </div>
                                            @endif

                                            @if($event->getResources(3) != null)
                                                <div class="row mb-2">
                                                    <label class="col-md-3 control-label text-md-right">{{ trans('labels.event.personnel') }}</label>
                                                    <div class="col-md-9 text-left">{{ $event->getResources(3) }}</div>
                                                </div>
                                            @endif

                                        @endif

                                        {{--Public Comments is labeled Public Notes to avoid confusion with the "back and forth" Comments (but the original field was not renamed)--}}
                                        {{--hide public comments when value is null--}}
                                        @isset($event->public_comments)
                                            <div class="row mb-2">
                                                <label class="col-md-3 control-label text-md-right">{{ trans('labels.event.public_notes') }}</label>
                                                <div class="col-md-9 text-left">{{ $event->public_comments}}</div>
                                            </div>
                                        @endisset

                                        {{-- Receipt--}}
                                        @isset($eventUserPayment)
                                            <div class="row mb-2">
                                                <label class="col-md-3 control-label text-md-right">{{ trans('labels.event.view_receipt') }}</label>
                                                <div class="col-md-9 text-left">
                                                    <span class="simptip-position-top simptip-smooth" data-tooltip="{{ trans('labels.event.view_receipt') }}">
                                                        <button class="btn btn-sm btn-default" data-toggle="modal"
                                                            data-id="{{ $eventUserPayment->id }}" data-target="#receiptModal">
                                                            <i class="fas fa-file-invoice-dollar fa-2x"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        @endisset

                                        {{--The following can only be viewed by site level event-details pemission--}}
                                        {{--mitcks 11/8/19 I am using site level permission check here and passing null strings--}}
                                        {{--for course and event level in case those permissions are added later--}}
                                        @if($event->hasSiteCourseEventPermissions(['scheduling','event-details'], '', ''))

                                            {{--Internal Comments is labeled Internal Notes to avoid confusion with the "back and forth" Comments (but the original field was not renamed)--}}
                                            {{--hide internal comments when value is null--}}
                                            @isset($event->internal_comments)
                                                <div class="row mb-2">
                                                    <label class="col-md-3 control-label text-md-right">{{ trans('labels.event.internal_notes') }}</label>
                                                    <div class="col-md-9 text-left">{!!html_entity_decode($event->internal_comments)!!}</div>
                                                </div>
                                            @endisset

                                            {{--Class Size/Enrollment--}}
                                            <div class="row mb-2">
                                                <label class="col-md-3 control-label text-md-right">{{ trans('labels.event.class_size') }}</label>
                                                <div class="col-md-9 text-left">
                                                    {{ $event->class_size }} ({{ $event->numLearnersEnrolled() }}
                                                    @if($event->numLearnersEnrolled() == 1)
                                                        {{ trans('labels.event.one_learner') }})
                                                    @else
                                                        {{ trans('labels.event.more_learners') }})
                                                    @endif
                                                    @if($event->isFull())
                                                        <span class="event-full">{{ trans('labels.event.full') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{--hide requested by when value is null--}}
                                            @if($event->requestedBy())
                                                <div class="row mb-2">
                                                    <label class="col-md-3 control-label text-md-right">{{ trans('labels.event.event_requested_by') }}</label>
                                                    <div class="col-md-9 text-left">{!! $event->requestedBy()!!}</div>
                                                </div>
                                            @endif

                                            {{--The following 4 fields should never be null--}}
                                            <div class="row mb-2">
                                                <label class="col-md-3 control-label text-md-right">{{ trans('labels.event.event_created_by') }}</label>
                                                <div class="col-md-9 text-left">{!! $event->createdBy()!!}</div>
                                            </div>

                                            <div class="row mb-2">
                                                <label class="col-md-3 control-label text-md-right">{{ trans('labels.event.event_created_on') }}</label>
                                                <div class="col-md-9 text-left">{{ date_create($event->created_at->timezone(session('timezone')))->format('m/d/Y g:ia') }}</div>
                                            </div>

                                            <div class="row mb-2">
                                                <label class="col-md-3 control-label text-md-right">{{ trans('labels.event.event_last_edited_by') }}</label>
                                                <div class="col-md-9 text-left">{!! $event->editedBy()!!}</div>
                                            </div>

                                            <div class="row mb-2">
                                                <label class="col-md-3 control-label text-md-right">{{ trans('labels.event.event_last_edited_on') }}</label>
                                                <div class="col-md-9 text-left">{{ date_create($event->updated_at->timezone(session('timezone')))->format('m/d/Y g:ia') }}</div>
                                            </div>

                                        @endif

                                        {{--only display enroll button if not logged in OR if they are not already enrolled in event--}}
                                        @if((!$event->isEnrolled() AND !$event->isWaitlisted()) OR !$logged_in_user)
                                            <div class="row mb-2">
                                                <label class="col-md-3 control-label text-md-right"></label>
                                                <div class="col-md-9 text-left">
                                                    {{-- enroll request button, hide if option set OR if no upcoming dates, mitcks: had to do a nested IF here because OR operator would not work?  Not sure why.--}}
                                                    @if(null !== $event->courseInstance->course->upcomingClassDates())
                                                        {{-- Only display if the event is not full OR it is full but requests are allowed --}}
                                                        @if(!$event->isFull() OR ($event->isFull() AND $event->courseInstance->course->isOptionChecked(13)))
                                                            @if(!$event->courseInstance->course->isOptionChecked(6)) {{-- hide enrollment button--}}
                                                                @if($event->courseInstance->course->isOptionChecked(1)) {{--change button text if auto enroll--}}
                                                                    {{ link_to_route('enrollRequest',
                                                                    trans('navs.frontend.course.auto_enroll'),
                                                                    ['course_id'=>$event->courseInstance->course->id, 'event_id'=>$event->id],
                                                                    ['class' => 'btn btn-success btn-md mt-10']) }}
                                                                @else
                                                                    {{ link_to_route('enrollRequest',
                                                                    trans('navs.frontend.course.request_enroll'),
                                                                    ['course_id'=>$event->courseInstance->course->id, 'event_id'=>$event->id],
                                                                    ['class' => 'btn btn-success btn-md mt-10']) }}
                                                                @endif
                                                            @endif
                                                        @else
                                                            <p class="mt-10">
                                                                {!! trans('strings.frontend.no_enroll_event_full', ['course_id'=>$event->courseInstance->course->id]) !!}
                                                            </p>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        {{-- If waitlisted display message--}}
                                        @if($event->isWaitlisted())
                                            <div class="row mb-2">
                                                <div class="col-md-12 text-center mt-10">
                                                    {!! trans('strings.frontend.event-dashboard-waitlist', ['siteEmail'=>Session::get('site_email')]) !!}
                                                </div>
                                            </div>
                                        @endif

                                    </div><!--info container-->
                                </div> <!-- info tab-pane -->

                                {{--User must be logged in to see any of the remaining tab content--}}
                                @if($logged_in_user)

                                    {{--COMMENTS TAB CONTENT: only the event requester and those add-event-comment permissions can see content--}}
                                    @if($event->requestedBy(true) == $logged_in_user->id or $event->hasSiteCourseEventPermissions(['add-event-comment'], ['course-add-event-comment'], ['event-add-event-comment']))
                                        <div class="tab-pane @if($tab == 'comments') fade show active @endif" id="vert-tabs-comments" role="tabpanel" aria-labelledby="vert-tabs-comments-tab">
                                            @include('courseInstance.events.partial-comments')
                                        </div>
                                    @endif

                                    {{--ROSTER TAB CONTENT: only those with *add-people-to-events permission can see content--}}
                                    {{-- 2021-02-17 mitcks: adding *mark-event-attendance permisson to view this tab--}}
                                    @if($event->hasSiteCourseEventPermissions(['add-people-to-events'], ['course-add-people-to-events'], ['event-add-people-to-events'])
                                        or $event->hasSiteCourseEventPermissions(['site-mark-event-attendance'], ['course-mark-event-attendance'], ['event-mark-event-attendance']))
                                        <div class="tab-pane @if($tab == 'roster') fade show active @endif" id="vert-tabs-roster" role="tabpanel" aria-labelledby="vert-tabs-roster-tab">
                                            @include('courseInstance.events.partial-users')
                                        </div>
                                    @endif

                                    {{--WAITLIST TAB CONTENT: only those with *add-people-to-events permission can see content--}}
                                    @if($event->hasSiteCourseEventPermissions(['add-people-to-events'], ['course-add-people-to-events'], ['event-add-people-to-events']))
                                        <div class="tab-pane @if($tab == 'waitlist') fade show active @endif" id="vert-tabs-waitlist" role="tabpanel" aria-labelledby="vert-tabs-waitlist-tab">
                                            @include('courseInstance.events.partial-waitlist')
                                        </div>
                                    @endif

                                    {{--EMAIL TAB content: only those with *manage-event-emails permission can see tab--}}
                                    @if($event->hasSiteCourseEventPermissions(['manage-event-emails'], ['course-manage-event-emails'], ['event-manage-event-emails']))
                                        <div class="tab-pane @if($tab == 'email') fade show active @endif"
                                             id="vert-tabs-email" role="tabpanel" aria-labelledby="vert-tabs-email-tab">
                                            @include('courseInstance.events.partial-emails')
                                        </div>
                                    @endif

                                    {{--CURRICULUM TABS content: only those with *view permission can see tab--}}
                                    @foreach($viewerTypes as $viewerType)
                                        @if($event->hasSiteCourseEventPermissions(['view-'.strtolower($viewerType->description).'-curriculum'], ['course-view-'.strtolower($viewerType->description).'-curriculum'], ['event-view-'.strtolower($viewerType->description).'-curriculum']))
                                            <div id="{{strtolower($viewerType->description)}}"
                                                 class="tab-pane @if($tab == strtolower($viewerType->description)) fade show active @endif">
                                                @include('courseInstance.events.partial-curriculum', ['courseCurriculum' => $courseCurriculum->where('viewer_type_id', $viewerType->id)])
                                            </div>
                                        @endif
                                    @endforeach

                                    {{--QSE TAB content: only those with *manage-qse permission can see tab content--}}
                                    {{--Only include if QSE exist for course --}}
                                    @if($event->getQSE()->isNotEmpty())
                                        @if($event->hasSiteCourseEventPermissions(['manage-qse'], ['course-manage-qse'], ['event-manage-qse']))
                                            <div class="tab-pane @if($tab == 'qse') fade show active @endif"
                                                 id="vert-tabs-qse" role="tabpanel" aria-labelledby="vert-tabs-qse-tab">
                                                @include('courseInstance.events.partial-qse')
                                            </div>
                                        @endif
                                    @endif

                                @endif
                            </div><!--tab-content-->
                        </div>
                        </div><!--row-->
                    </div> <!--card body-->
                </div>
            </div><!--card-->
        </div>

        <div id="historyModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>

        <div id="receiptModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>

    </section>

    <script type="text/javascript">

        $(function (){
            $('.accordionCurriculumCollapseOrExpandAll').on('click', function () {
                let collapseAll = $(this).data('collapsed-all');



                if (!collapseAll) {
                    $('#accordionCurriculum .collapse').collapse('hide');
                    $(this).data('collapsed-all', true);
                } else if (collapseAll) {
                    $('#accordionCurriculum .collapse').collapse('show');
                    $(this).data('collapsed-all', false);

                }

            });


            $("#historyModal").on('show.bs.modal', function (e) {
                var triggerLink = $(e.relatedTarget);
                var id = triggerLink.data("id");

                $(this).find(".modal-body").load("/event/users/history/"+id);
            });

            $("#receiptModal").on('show.bs.modal', function (e) {
                var triggerLink = $(e.relatedTarget);
                var id = triggerLink.data("id");

                $(this).find(".modal-body").load("/paymentReceipt/"+id);
            });

            // DELETE THE EVENT SWEET ALERT
            $("body").on("click", "a[name='delete_event']", function (e) {
                e.preventDefault();
                var href = $(this).attr("href");

                var content = document.createElement('div');

                //if this event is part of a recurrence group
                @if($event->courseInstance->hasRecurrence())

                    content.innerHTML = "{{ trans('alerts.frontend.scheduling.delete_recurrence1') }}{{ $event->CourseInstance->RecurrenceDates }}</br></br>{{ trans('alerts.frontend.scheduling.delete_recurrence2') }}" + '</br><span style="font-weight:bolder; color:blue;">' + " {{ $event->DisplayEventNameShort }}</span>{{ trans('alerts.frontend.scheduling.delete_recurrence3') }}";
                swal({
                    title: "{{ trans('alerts.general.confirm_delete') }}",
                    {{--text: "{{ trans('alerts.frontend.scheduling.confirm_delete_text', ['Event'=>$event->DisplayEventName]) }} {!! $event->DisplayEventNameShortHTML !!} ",--}}
                    content: content, //this is set above so it can include html
                    icon: "warning",
                    buttons: {
                        cancel: "Cancel",
                        all: {
                            text: "Delete All",
                            value: "all",
                            className: "deleteButton",
                        },
                        one: {
                            text: "Delete One",
                            value: "one",
                            className: "templateButton",
                        },
                    },
                    dangerMode: true,
                })
                    .then((value) => {
                        switch (value) {

                            case "all":
                                window.location.href = href + '/true';
                                break;
                            case "one":
                                window.location.href = href;
                                break;
                            default:
                            // cancel
                        }
                    });
                @else
                //event not part of recurrence group

                content.innerHTML = "{{ trans('alerts.general.confirm_delete_content') }}" + '</br><span style="font-weight:bolder; color:blue;">' + " {{ $event->DisplayEventNameShort }}" + '</span>';

                swal({
                    title: "{{ trans('alerts.general.confirm_delete') }}",
                    {{--text: "{{ trans('alerts.frontend.scheduling.confirm_delete_text', ['Event'=>$event->DisplayEventName]) }} {!! $event->DisplayEventNameShortHTML !!} ",--}}
                    content: content, //this is set above so it can include html
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then(function (isConfirmed) {
                        if (isConfirmed) {
                            window.location.href = href;
                        } else {
                        }
                    });
                @endif

            });
        });
    </script>

@endsection


@section('after-scripts')

@endsection