{{--<p><b>{{ trans('labels.scheduling.comments') }}</b></p>--}}

<span class="comments-section">
    {!! $event->comments() !!}
</span>
<hr>

{{ Form::open(['url' => '/schedule/comment/add', 'id'=>'comment-form', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}

    <div class="form-group row">
        <div class="col-sm-10">
            <textarea name="comment" class="form-control mce"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-4">
            <button name="submit" type="submit" class="btn btn-primary">{{ trans('buttons.event.add_comment') }}</button>
            <span class="preloader" style="display:none"><img class="comment-spinner" alt="'.trans('labels.general.loading').'" src="/img/frontend/spinner.gif"></span>
            {{ Form::hidden('event_id', $event->id , array('id' => 'event_id')) }}
            {{ Form::hidden('schedule_request_id', $event->scheduleRequestId(), array('id' => 'schedule_request_id')) }}
            {{ Form::hidden('tab', 'comments', array('id' => 'tab')) }}
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12">
            <input type="checkbox" name="email_comment" value="true" id="email_comment" checked>
            <label for="email_comment">{{ trans('labels.scheduling.send_comment_email') }}</label>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12">
            <!-- decide if resolved checkbox needs checked -->
            @if($event->resolved == 1)
                @php($checked = 'checked')
            @else
                @php($checked = null)
            @endif
            @permission('scheduling')
                <input type="checkbox" name="resolved" value="true" id="resolved" {{ $checked }}>
                <label for="resolved">{{ trans('labels.scheduling.resolved_comment') }}</label>
            @endauth
        </div>
    </div>

{{ Form::close() }}


    {{ Html::script("/js/tinymce/tinymce.min.js") }}

    {{-- TC - Adding Script to make info Modal btns work --}}
    {{--mitcks 2020-01-15 commenting out line below for issie SIMS30-140, was causing error and I don't think this is still needed--}}
    {{--{{ Html::script("/js/calendar.js") }}--}}
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

        {{--// submit buttons for comment--}}
        {{--$('#comment-form').on('submit', function(e){--}}
            {{--e.preventDefault();--}}

            {{--if ($('#comment').val() == '') {--}}
                {{--//pop up--}}
                {{--swal({--}}
                    {{--title: "{{ trans('labels.scheduling.comments') }}",--}}
                    {{--text: "{{ trans('labels.scheduling.add_comment') }}",--}}
                    {{--icon: "warning",--}}
                    {{--showCancelButton: false,--}}
                    {{--dangerMode: true,--}}
                {{--})--}}
            {{--} else {--}}

                {{--var form = $('#comment-form');--}}
                {{--var form_action = form.attr("action");--}}
                {{--var data = $(this).serialize();--}}
                {{--form.find('span.preloader').show();--}}

                {{--$.ajaxSetup({--}}
                    {{--headers: {--}}
                        {{--'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
                    {{--}--}}
                {{--});--}}
                {{--$.ajax({--}}
                    {{--dataType: 'json',--}}
                    {{--type:'POST',--}}
                    {{--url: form_action,--}}
                    {{--data: data,--}}
                {{--}).done(function(data){--}}
                    {{--form.find('span.preloader').hide();--}}
                    {{--tinyMCE.activeEditor.setContent('');--}}
                    {{--form.next().prepend('<hr><p class="comment-header">'+ data.name+' {{ trans("labels.scheduling.commented") }} '+data.date_time+'</p><p>'+data.comment+'</p>');--}}
                {{--});--}}
                {{--return  false;--}}

            {{--}--}}
        {{--});--}}

        {{--$("#delete_event").click(function(e) {--}}
            {{--e.preventDefault();--}}
            {{--var href = $(this).attr("href");--}}
            {{--swal({--}}
                {{--title: "{{ trans('labels.scheduling.event_delete_wall') }}",--}}
                {{--icon: "warning",--}}
                {{--buttons: true,--}}
                {{--dangerMode: true,--}}
            {{--})--}}
                {{--.then(function(isConfirmed) {--}}
                    {{--if (isConfirmed) {--}}
                        {{--window.location.href = href;--}}
                    {{--} else {--}}
                    {{--}--}}
                {{--});--}}
        {{--});--}}


        {{--$("a[data-target='#modal']").click(function(e) {--}}
            {{--e.preventDefault();--}}
            {{--var target = $(this).attr("href");--}}

            {{--// load the url and show modal on success--}}
            {{--//$("#modal .modal-body").load(target, function() {--}}
            {{--//    $("#modal").modal("show");--}}
            {{--//});--}}
        {{--});--}}

    </script>
