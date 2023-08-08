@extends('frontend.layouts.modal')

<div class="modal-header text-center">
    <h5 class="modal-title w-100 text-md">{{ $eventUser->event->DisplayEventName }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fas fa-times"></i></button>
</div>

<div class="modal-body">
    <div class="card">
    <div class="card-header">
        <h3 class="card-title" id="heading">{{ trans('labels.eventUserHistory.event_history') }} - <span class="text-primary">{!! $eventUser->user->nameEmail !!}</span></h3>
    </div>
    <div class="card-body">
            {!!  $timeline !!}
    </div>
</div>

<script>

</script>





