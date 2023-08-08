@extends('frontend.layouts.modal')

<form action="/scheduleRequest/deny" id="deny-form">

    <div class="modal-header text-center">
        <h5 class="modal-title text-center"><b>{{trans('labels.scheduling.deny_schedule_request')}} {{ $request->course->abbrv }} - {{ date_create($request->start_time)->format('m/d/Y g:iA') }}</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fas fa-times"></i></button>
    </div>

    <div class="modal-body">
        <p><b>{{ trans('labels.scheduling.course') }}: </b>{{ $request->course->name }} ({{ $request->course->abbrv }})</p>
        <p><b>{{ trans('labels.scheduling.event_date') }}: </b>{{ date_create($request->start_time)->format('m/d/Y') }}</p>
        <p><b>{{ trans('labels.general.time') }}: </b>{{ date_create($request->start_time)->format('g:ia') .' - '. date_create($request->end_time)->format('g:ia') }}</p>
        <hr>
        <p><b>{{ trans('labels.scheduling.deny_recipients') }}: </b></p>
        <p><small>Requestor: {{ $requestedByUserString }} </small></p>
        <p><small>Scheduler(s): {{ $request->getSchedulersEmailsToDisplay() }}</small></p>

        <div id="error-alert-block" class="alert alert-danger alert-block" style="display:none;">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong id="error-alert-message"></strong>
        </div>

        <p><b>{{ trans('labels.scheduling.deny_cc') }}:</b></p>
        <div class="form-group row">
            <div class="col-sm-12">
                <input name="cc_email" class="form-control" placeholder="personA@email.com, personB@email.com">
            </div> 
        </div>
        <p><b>{{ trans('labels.scheduling.deny_bcc') }}:</b></p>
        <div class="form-group row">
            <div class="col-sm-12">
                <input name="bcc_email" class="form-control" placeholder="personA@email.com, personB@email.com">
            </div> 
        </div>
        <p><b>{{ trans('labels.scheduling.email') }}</b></p>
        <div class="form-group row">
            <div class="col-sm-12">
                <textarea name="email_content" class="form-control mce">
                    {{ $default_text }}
                </textarea>
            </div>
        </div>
        <input name="schedule_request_id" type="hidden" value="{{ $request->id }}">
    </div>

    <div class="modal-footer">
        <div class="float-left">
            <button type="button" class="btn btn-warning btn-md" data-dismiss="modal">{{ trans('buttons.general.cancel') }}</button>
        </div>
        <div class="float-right">
            {{ Form::submit(trans('labels.scheduling.deny_request'), ['class' => 'btn btn-success btn-md']) }}
        </div>
    </div>

</form>

{{ Html::script("/js/sweetalert/sweetalert.min.js") }}

<script>
    // apply mce to textbox
    applyMCE();

    // submit buttons for comment
    $('#deny-form').on('submit', function(e){
        e.preventDefault();

        if ($('#email_content').val() == '') {
            //pop up
            swal({
                title: "{{ trans('labels.scheduling.comments') }}",
                text: "{{ trans('labels.scheduling.add_comment') }}",
                icon: "warning",
                buttons: {cancel: "OK"},
                dangerMode: true,
            })
        } else {

            var form = $('#deny-form');
            var form_action = form.attr("action");
            var data = $(this).serialize();

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
                if (data.status == 'success'){
                    $("#denyModal").modal('hide');
                    dt.row('.selected').remove().draw( false );
                }else{
                    console.log(data);
                }
            }).fail(function(data){
                // this parses the errors of the validation
                var response = jQuery.parseJSON(data.responseText);

                //set message to blank for first response and build the message from the validation error object
                var message = '';
                Object.keys(response.errors).forEach(function(key) {
                    message = message + response.errors[key] + '<br>';
                    console.log(key, response.errors[key]);
                });

                // add to alert box
                $('#error-alert-message').html(message);
                $('#error-alert-block').show();

            });
            return  false;

        }
    });

</script>