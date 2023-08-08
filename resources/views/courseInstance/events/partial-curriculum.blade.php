<div class="accordion" id="accordionCurriculum{{$viewerType->id}}">
    <div class="d-flex">
        <button class="btn btn-primary ml-auto accordionCurriculum{{$viewerType->id}}CollapseOrExpandAll"
                data-collapsed-all="false">
            Collapse/Expand All
        </button>
    </div>
    <hr/>
    @php
        $MI = 0;
    @endphp
    @foreach ($courseCurriculum as $key => $courseContent)
        <div class="card mb-0">
            <div class="card-header" id="heading{{$courseContent->id}}">
                <h5
                    class="font-weight-bold mb-0 accordion-heading{{$viewerType->id}} {{ $key == 0 || $key == 1 ? '' : 'collapsed' }}"
                    data-toggle="collapse"
                    data-target="#collapse{{$courseContent->id}}"
                    aria-expanded="true"
                    aria-controls="collapse{{$courseContent->id}}"
                    style="cursor: pointer;"
                >
                    <i class="fa mr-2" aria-hidden="true"></i> {{ $courseContent->menu_title }}
                </h5>
            </div>

            <div
                    id="collapse{{$courseContent->id}}"
                    class="collapse {{ $key == 0 || $key == 1 ? 'show' : '' }} multi-collapse"
                    aria-labelledby="heading{{$courseContent->id}}"
            >
                <div class="card-body px-5">
                    <ul class="list-group list-group-flush">
                        @foreach($courseContent->publishedChildren->sortBy('display_order') as $childKey => $child)
                            @if($child->content_type_id == 7 && $child->qse && !$child->qse->isExistsForEventIn($event->id))
                                @continue
                            @endif
                            <li class="list-group-item" style="text-indent: {{ $child->indent_level * 25 }}px;">
                                <a
                                    href="#"
                                    id="cc-{{$courseContent->id}}-{{ $child->id }}"
                                    class="text-primary text-decoration-none goto-slide{{$viewerType->id}}"
                                    data-id="{{ $child->id }}"
                                    data-target="#carouselIndicators{{$viewerType->id}}"
                                    data-menu-title="{{ $child->menu_title }}"
                                    data-slide-to="{{ $MI }}"
                                >
                                    @switch($child->content_type_id)
                                        @case(2)
                                        <i class="fa fa-file mr-2" aria-hidden="true"></i>
                                        @break
                                        @case(3)
                                        <i class="fa fa-video mr-2" aria-hidden="true"></i>
                                        @break
                                        @case(4)
                                        <i class="fa fa-file-alt mr-2" aria-hidden="true"></i>
                                        @break
                                        @case(5)
                                        <i class="fa fa-file-download mr-2" aria-hidden="true"></i>
                                        @break
                                        @case(6)
                                        <i class="fa fa-presentation mr-2" aria-hidden="true"></i>
                                        @break
                                        @case(7)
                                        <i class="fa fa-ballot-check mr-2" aria-hidden="true"></i>
                                        @break
                                    @endswitch
                                    {{ $child->menu_title }}
                                </a>
                            </li>
                            @php
                                $MI++;
                            @endphp
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card mb-0 d-none" id="agenda{{$viewerType->id}}">
    <div class="card-header">
        <h5 class="font-weight-bold mb-0 card-title">
        </h5>
        <div class="float-right">
            <div class="dropdown">
                <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">
                    Jump to... <span class="caret"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-right" style="max-height: 500px; overflow-y: scroll">
                    @php
                        $JTI = 0;
                    @endphp
                    @foreach ($courseCurriculum as $key => $courseContent)
                        <a href="#" data-target="#carouselIndicators{{$viewerType->id}}" data-slide-to="{{ $JTI }}"
                           class="font-weight-bold dropdown-item"
                           style="cursor: pointer;">{{$courseContent->menu_title}}</a>
                        @foreach($courseContent->publishedChildren->sortBy('display_order') as $childKey => $child)
                            <a data-target="#carouselIndicators{{$viewerType->id}}" data-slide-to="{{ $JTI }}"
                               class="dropdown-item ml-2"
                               style="cursor: pointer;">{{$child->menu_title}}</a>
                            @php
                                $JTI++;
                            @endphp
                        @endforeach
                        <li role="separator" class="divider"></li>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div
                id="carouselIndicators{{$viewerType->id}}"
                class="carousel slide carousel-fade"
                data-ride="carousel"
                data-interval="false"
        >
            <div class="carousel-inner">
                @foreach ($courseCurriculum as $key => $courseContent)
                    @foreach($courseContent->publishedChildren->sortBy('display_order') as $childKey => $child)
                        @if($child->content_type_id == 7 && $child->qse && !$child->qse->isExistsForEventIn($event->id))
                            @continue
                        @endif
                        <div
                                class="carousel-item bg-white"
                                style="text-indent: {{ $child->indent_level * 25 }}px;"
                                id="carouselItem{{$child->id}}{{$viewerType->id}}"
                                data-menu-title="{{ $child->menu_title }}"
                        >

                            @if($child->content_type_id == 7 && !is_null($child->qse))
                                @php
                                    $eventUser = \App\Models\CourseInstance\EventUser::where('event_id', $event->id)
                                                        ->where('user_id', auth()->user()->id)
                                                        ->first();
                                    $eventUserQSEs = [];
                                    $incompleteQuiz = null;
                                    if ($eventUser) {
                                        $eventUserQSEs = \App\Models\CourseContent\QSE\EventUserQSE::where('event_user_id', $eventUser->id)
                                                                                                ->where('course_qse_id', $child->qse->id)
                                                                                                ->complete()
                                                                                                ->orderBy('created_at', 'DESC')
                                                                                                ->get();
                                        $incompleteQuiz = \App\Models\CourseContent\QSE\EventUserQSE::where('event_user_id', $eventUser->id)
                                                                                                ->where('course_qse_id', $child->qse->id)
                                                                                                ->incomplete()
                                                                                                ->orderBy('created_at', 'DESC')
                                                                                                ->first();
                                    }
                                @endphp
                                @if($child->qse->feedback_type_id == 3)
                                    @foreach($eventUserQSEs as $euq)
                                        <script>
                                            $.get('{{ route('qse-results-report', $euq->id)}}')
                                                .then(function (res) {
                                                    $('body').append(res.html)
                                                })
                                        </script>
                                    @endforeach
                                @endif
                                @php
                                    $isTakeQuizDisabled = false;
                                    $disabledTakeQuizMessage = '';
                                    if($child->qse->activation_type_id == 2){
                                        $eventTZ = \Carbon\Carbon::now()->timezone($event->initialMeetingRoom->location->building->timezone)->timezoneAbbreviatedName;
                                        //this is used to get now in the building timezone for comparison below
                                        $nowBuildingTZ = \Carbon\Carbon::now()->timezone($event->initialMeetingRoom->location->building->timezone);

                                        if($child->qse->automatic_activation_time == 'S'){
                                            $whenAvailable = \Carbon\Carbon::parse($event->start_time);
                                        } else {
                                            $whenAvailable = \Carbon\Carbon::parse($event->end_time);
                                        }

                                        //offset by minutes
                                        //Note: I could not get Carbon greaterThan function to work correctly here with times, so casting to PHP DateTime instead
                                        $whenAvailable = new DateTime($whenAvailable->addMinutes($child->qse->minutes));
                                        $nowBuildingTZ = new DateTime($nowBuildingTZ);

                                        if($nowBuildingTZ >= $whenAvailable) {
                                            //is available now
                                        } else {
                                            //manual and turned on
                                            $isTakeQuizDisabled = true;
                                            $disabledTakeQuizMessage = trans('alerts.frontend.qse.unavailable_at_this_time', ['title' => $child->menu_title, 'date_time' => $whenAvailable->format('m/d/y g:i A') . ' ' . $eventTZ]);
                                        }
                                    }
                                @endphp

                                @includeWhen($child->qse->presentation_type_id === 1, 'courseInstance.events.qse.partials.one-question-per-page')
                                @includeWhen($child->qse->presentation_type_id === 2, 'courseInstance.events.qse.partials.all-questions-on-the-same-page')
                            @else
                                @isset($child->courseFile)
                                    @if(strtolower(getFileExtension($child->courseFile->links ?? '')) === 'mp4')
                                        <video
                                                width="720"
                                                height="380"
                                                controls
                                                style="margin: 0 auto;"
                                        >
                                            <source
                                                    src="{{$child->courseFile->links ?? ''}}"
                                                    type="video/mp4"
                                            >
                                            Your browser does not support the video tag.
                                        </video>
                                    @elseif(strtolower(getFileExtension($child->courseFile->links ?? ''))   === 'pdf')
                                        <iframe
                                                width="730px"
                                                height="1000px"
                                                class="doc"
                                                src="{{$child->courseFile->links}}"
                                                frameborder="0"></iframe>
                                    @elseif(isOfficeExtension($child->courseFile->links))
                                        <iframe
                                                src="https://view.officeapps.live.com/op/embed.aspx?src={{$child->courseFile->links}}"
                                                width="700"
                                                height="780"
                                                style="border: none;"></iframe>
                                    @elseif(strpos($child->courseFile->links, '.html') != FALSE)
                                        <iframe
                                                src="{{$child->courseFile->links}}"
                                                width="100%"
                                                height="780"
                                                style="border: none;"></iframe>
                                    @elseif($child->contentType->id == 6)
                                        <iframe src="{{$child->courseFile->links}}"
                                                style="border: none;" width="100%" height="700"></iframe>
                                    @else
                                        <div class="d-flex">
                                            <div class="mx-auto">
                                                <a
                                                        class="btn btn-default"
                                                        href="{{$child->courseFile->links}}"
                                                >
                                                    Download File
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endisset

                                @isset($child->coursePage)
                                    {!! $child->coursePage->text !!}
                                @endisset
                            @endif
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between">
            <div class="w-100">
                <a
                        class="btn btn-primary btn-sm " style="width:85px;"
                        href="#carouselIndicators{{$viewerType->id}}"
                        role="button"
                        id="carouselIndicatorPrev{{$viewerType->id}}"
                        data-slide="prev"
                >
                    <i class="mr-2 far fa-chevron-left"></i>{{ trans('buttons.curriculum.previous') }}
                </a>
            </div>
            <div class="w-100 text-right">
                <a
                        class="btn btn-primary btn-sm" style="width:85px;"
                        href="#carouselIndicators{{$viewerType->id}}"
                        role="button"
                        id="carouselIndicatorNext{{$viewerType->id}}"
                        data-slide="next"
                >
                    {{ trans('buttons.curriculum.next') }} <i class="ml-2 far fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        checkCarouselItem();
        $(document).on('slid.bs.carousel', '#carouselIndicators{{$viewerType->id}}', checkCarouselItem);
        $('.accordion-heading{{$viewerType->id}}').on('click', function () {
            if ($(this).find('i').hasClass('fa-caret-right')) {
                $(this).find('i').removeClass('fa-caret-right');
                $(this).find('i').addClass('fa-caret-down');
            } else if ($(this).find('i').hasClass('fa-caret-down')) {
                $(this).find('i').removeClass('fa-caret-down');
                $(this).find('i').addClass('fa-caret-right');
            }
        });

        $('.goto-slide{{$viewerType->id}}').on('click', function (e) {
            $('#accordionCurriculum{{$viewerType->id}}').addClass('d-none');
            $('#agenda{{$viewerType->id}}').removeClass('d-none');
            $(`#carouselItem${$(this).data('id')}{{$viewerType->id}}`).addClass('active');
            $('#agenda{{$viewerType->id}} > .card-header > .card-title').text($(this).data('menu-title'))
            checkCarouselItem()
        });

        $('a#vert-tabs-{{ strtolower($viewerType->description) }}-tab').on('click', function (e) {
            $('#accordionCurriculum{{$viewerType->id}}').removeClass('d-none');
            $('#agenda{{$viewerType->id}}').addClass('d-none');
            $('#carouselIndicators{{$viewerType->id}} > .carousel-inner > .carousel-item').removeClass('active');
        });

        function checkCarouselItem() {
            if ($('#carouselIndicators{{$viewerType->id}} > .carousel-inner > .carousel-item:first').hasClass('active')) {
                $('a#carouselIndicatorPrev{{$viewerType->id}}').addClass('d-none');
            } else if ($('#carouselIndicators{{$viewerType->id}} > .carousel-inner > .carousel-item:last').hasClass('active')) {
                $('a#carouselIndicatorNext{{$viewerType->id}}').addClass('d-none');
            } else {
                $('a#carouselIndicatorPrev{{$viewerType->id}}').removeClass('d-none');
                $('a#carouselIndicatorNext{{$viewerType->id}}').removeClass('d-none');

            }

            $('#agenda{{$viewerType->id}}').find('.card-title').text($(this).find('.carousel-item.active').data('menu-title'));
        }

        $('.accordionCurriculum{{$viewerType->id}}CollapseOrExpandAll').on('click', function () {
            let collapseAll = $(this).data('collapsed-all');

            if (!collapseAll) {
                $('#accordionCurriculum{{$viewerType->id}} .collapse').collapse('hide');
                $(this).data('collapsed-all', true);
            } else if (collapseAll) {
                $('#accordionCurriculum{{$viewerType->id}} .collapse').collapse('show');
                $(this).data('collapsed-all', false);

            }
        });
        @if(request()->get('qs', '') != '')
        $('a#{{ request()->get('qs', '') }}').trigger('click');
        @endif

    });
</script>