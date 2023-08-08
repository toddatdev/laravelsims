@extends('frontend.layouts.modal')

@if ($event->eventXofY() == '1 of 1')
@php ($eventnumber = '')
@else
@php ($eventnumber = '('. $event->eventXofY() . ')')
@endif


<style type="text/css">
.colorstrip {
    width: 99.99%; height: 2px;
    border-style: solid;
    border-color: {{{ $event->eventColor() }}};
    background-color: {{{ $event->eventColor() }}};
  }
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fas fa-times"></i></button>
    <h4 class="modal-title text-center"><b>{{ $event->courseInstance->course->abbrv }} - {{ date_create($event->start_time)->format('m/d/Y g:iA') }} {{ $eventnumber }}</b> {!! $event->simSpecialistNeededYN() !!} {!! $event->specialRequirementsNeeded() !!}</h4>
</div>
<div class="colorstrip"></div>


<div class="modal-body">
    {{--<p>test: {{ $event->IsSchedulerForLocation() }} {{$event->getEditButtonAttribute()}}</p>--}}
    <p><b>{{ trans('labels.event.course') }}:</b> {{ $event->courseInstance->course->name }} ({{ $event->courseInstance->course->abbrv }})</p>
    <p><b>{{ trans('labels.event.class_date') }}:</b> {{ Carbon\Carbon::parse($event->start_time)->format('l, F d, Y') }}</p>
    <p><b>{{ trans('labels.event.initial_meeting_room') }}:</b> {{ $event->initialMeetingRoom->location->building->abbrv }} {{ $event->initialMeetingRoom->location->abbrv }} {{ $event->initialMeetingRoom->abbrv }}</p>
    <p><b>{{ trans('labels.event.time') }}:</b> {{ date_create($event->start_time)->format('g:ia')}} - {{ date_create($event->end_time)->format('g:ia') }}</p>
    <p><b>{{ trans('labels.event.public_notes') }}:</b> {!!html_entity_decode($event->public_comments)!!}</p>
    <p><b>{{ trans('labels.event.class_size') }}:</b> {{ $event->class_size }} <!--(# enrolled)--></p>
    <p><b>{{ trans('labels.event.event_rooms') }}:</b> {{ $event->eventRooms() }}</p>

    <!-- scheduling and schedule requester only see these details -->
    @permissions(['scheduling','schedule-request','event-details'])
        <p><b>{{ trans('labels.event.internal_notes') }}:</b> {!!html_entity_decode($event->internal_comments)!!} </p>
        @if($event->requestedBy())
        <p><b>{{ trans('labels.event.event_requested_by') }}:</b> {{ $event->requestedBy() }}</p>
        @endif
        <p><b>{{ trans('labels.event.event_created_by') }}:</b> {{ $event->getUserFirstLast($event->courseInstance->created_by) }}</p>
        <p><b>{{ trans('labels.event.event_created_on') }}:</b> {{ date_create($event->courseInstance->created_at->timezone(session('timezone')))->format('m/d/Y g:ia') }}</p>
        <p><b>{{ trans('labels.event.event_last_edited_by') }}:</b> {{ $event->getUserFirstLast($event->last_edited_by) }}</p>
        <p><b>{{ trans('labels.event.event_last_edited_on') }}:</b> {{ date_create($event->updated_at->timezone(session('timezone')))->format('m/d/Y g:ia') }}</p>
    @endauth

    <!-- comments - make sure user is logged in, if user id who requested it then display comments, else display comments to schedulers only -->
    
    @if($logged_in_user)   
        @if($event->requestedBy(true) == $logged_in_user->id)
            @include('courseInstance.events.partial-comments')            
        @else
            
            @permission('add-event-comment')          
                @include('courseInstance.events.partial-comments')
            @endauth

            {{-- User with course-add-event-comment belongs to that course  --}}
            @permission('course-add-event-comment')
            @if (in_array($event->courseInstance->course->id, $course_list))            
                @include('courseInstance.events.partial-comments')
            @endif
            @endauth
        @endif

    @endif


</div>

<div class="modal-footer">
    <div class="pull-left">

        {!! $event->ShowButton !!}
        {!! $event->TemplatesButton !!}
        {!! $event->AddPeopleButton !!}
        {!! $event->EditButton !!}
        {!! $event->DuplicateButton !!}
        {!! $event->DeleteButton !!}

    </div>

    @if ($eventnumber != '')
    <a href="/event/" class="btn btn-default" data-target="#modal"><i class="fa fa-arrow-left"></i></a>
    <a href="/event/" class="btn btn-default" data-target="#modal"><i class="fa fa-arrow-right"></i></a>
    @endif
</div>

{{ Html::script("/js/tinymce/tinymce.min.js") }}

{{-- TC - Adding Script to make info Modal btns work --}}
{{ Html::script("/js/calendar.js") }}
{{ Html::script("/js/sweetalert/sweetalert.min.js") }}

<script>
    
    tinyMCE.init({
        mode : "textareas",
        forced_root_block : false,
        editor_selector : "mce",
        browser_spellcheck: true,
        menubar: false,
        height: "120",
        branding: false,
        plugins: [
            'advlist autolink lists link charmap print preview anchor textcolor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime table paste code help wordcount'
        ],
        toolbar: 'undo redo |  formatselect | bold italic underline backcolor forecolor  | bullist numlist | removeformat | link code',
    });

    // submit buttons for comment
    $('#comment-form').on('submit', function(e){
        e.preventDefault();

        if ($('#comment').val() == '') {
            //pop up
            swal({
                title: "{{ trans('labels.scheduling.comments') }}",
                text: "{{ trans('labels.scheduling.add_comment') }}",
                icon: "warning",
                showCancelButton: false,
                dangerMode: true,
            })
        } else {

            var form = $('#comment-form');
            var form_action = form.attr("action");                        
            var data = $(this).serialize();
            form.find('span.preloader').show();            

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                dataType: 'json',
                type:'POST',
                url: form_action,
                data: data,
            }).done(function(data){
                form.find('span.preloader').hide();
                tinyMCE.activeEditor.setContent('');
                form.next().prepend('<hr><p class="comment-header">'+ data.name+' {{ trans("labels.scheduling.commented") }} '+data.date_time+'</p><p>'+data.comment+'</p>');
            });
            return  false;

        }
    });

    $("#delete_event").click(function(e) {
        e.preventDefault();
        var href = $(this).attr("href");
        swal({
            title: "{{ trans('labels.scheduling.event_delete_wall') }}",
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


    $("a[data-target='#modal']").click(function(e) {
        e.preventDefault();
        var target = $(this).attr("href");

        // load the url and show modal on success
        //$("#modal .modal-body").load(target, function() {
        //    $("#modal").modal("show");
        //});
    });

</script>
