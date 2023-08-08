@extends('frontend.layouts.modal')

<div class="modal-header text-center">
    <h5 class="modal-title w-100">{{ $request->course->abbrv }} - {{ date_create($request->start_time)->format('m/d/Y g:iA') }} {!! $request->simSpecialistNeededYN() !!} {!! $request->status() !!}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fas fa-times"></i></button>
</div>

<div class="modal-body">

    <div class="container">
        <div class="row">
            {{ Form::label('date', trans('labels.scheduling.course'), ['class' => 'col-lg-3 control-label text-md-right']) }}
            <div class="col-md-9 text-left">
                {{ $request->course->name }} ({{ $request->course->abbrv }})
            </div>
        </div>
        <div class="row">
            {{ Form::label('date', trans('labels.scheduling.event_date'), ['class' => 'col-lg-3 control-label text-md-right']) }}
            <div class="col-md-9 text-left">
                {{ Carbon\Carbon::parse($request->start_time)->format('l, F d, Y') }}
            </div>
        </div>
        <div class="row">
            {{ Form::label('date', trans('labels.general.time'), ['class' => 'col-lg-3 control-label text-md-right']) }}
            <div class="col-md-9 text-left">
                {{ date_create($request->start_time)->format('g:ia') .' - '. date_create($request->end_time)->format('g:ia') }}
            </div>
        </div>
        <div class="row">
            {{ Form::label('date', trans('labels.calendar.location'), ['class' => 'col-lg-3 control-label text-md-right']) }}
            <div class="col-md-9 text-left">
                {{ $request->location->building->abbrv }} {{ $request->location->abbrv }}
            </div>
        </div>
        @if($template)
            <div class="row">
                {{ Form::label('date', trans('labels.scheduling.template'), ['class' => 'col-lg-3 control-label text-md-right']) }}
                <div class="col-md-9 text-left">
                    {{ $template->name }}
                </div>
            </div>
        @endif
        <div class="row">
            {{ Form::label('date', trans('labels.scheduling.status'), ['class' => 'col-lg-3 control-label text-md-right']) }}
            <div class="col-md-9 text-left">
                {{ $request->statusText() }}
            </div>
        </div>
        <div class="row">
            {{ Form::label('date', trans('labels.scheduling.rooms'), ['class' => 'col-lg-3 control-label text-md-right']) }}
            <div class="col-md-9 text-left">
                {{ $request->num_rooms }}
            </div>
        </div>
        <div class="row">
            {{ Form::label('date', trans('labels.scheduling.participants'), ['class' => 'col-lg-3 control-label text-md-right']) }}
            <div class="col-md-9 text-left">
                {{ $request->class_size }}
            </div>
        </div>
        <div class="row">
            {{ Form::label('date', trans('labels.scheduling.request_note'), ['class' => 'col-lg-3 control-label text-md-right']) }}
            <div class="col-md-9 text-left">
                {!! $request->notes !!}
            </div>
        </div>
        <div class="row">
            {{ Form::label('date', trans('labels.scheduling.requested_by'), ['class' => 'col-lg-3 control-label text-md-right']) }}
            <div class="col-md-9 text-left">
                {!! $request->requestedBy() !!}
            </div>
        </div>
        @if($request->denied_date != null)
            <div class="row">
                {{ Form::label('date', trans('labels.scheduling.denied_by'), ['class' => 'col-lg-3 control-label text-md-right']) }}
                <div class="col-md-9 text-left">
                    {!! $request->deniedBy() !!}
                </div>
            </div>
        @endif
    </div>

    <br>

    <!-- comments - make sure user is logged in, if user id who requested it then display comments, else display comments to schedulers only -->
    @if($logged_in_user)

        @if($request->requested_by == $logged_in_user->id)
            @include('courseInstance.scheduleRequest.partial-comments')
        @else
            @permission('scheduling')
                @include('courseInstance.scheduleRequest.partial-comments')
            @endauth
        @endif

    @endif

</div>

{{ Html::script("/js/sweetalert/sweetalert.min.js") }}

<script>

    $('.deny-{{$request->id}}').click(function(e){
        e.preventDefault();

        if ($('#modal').is(':visible')) {
        $('#modal')
            .modal('hide')
            .on('hidden.bs.modal', function (e) {
                $(this).off('hidden.bs.modal'); // Remove the 'on' event binding
            });
        }

    });

    // apply mce to textbox
    applyMCE();

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
                {{--form.next().prepend('<hr><p class="comment-header">'+ data.name+' {{ trans("labels.scheduling.commented") }} '+data.date_time+'</p><p>'+data.comment+'</p>');--}}
                form.next().prepend('<div class="row"><div class="col-md-12"><div class="timeline"><div><i class="fas fa-comment bg-blue"></i><div class="timeline-item"><span class="time">'+data.date_time+
                    '<i class="ml-3 mr-1 fas fa-clock"></i></span><h3 class="timeline-header"><a href="#">'
                    + data.name + '</a></h3><div class="timeline-body">'+data.comment+'</div></div></div></div></div></div>');
            });
            return  false;

        }
    });

</script>