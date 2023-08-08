<p><b>{{ trans('labels.scheduling.comments') }}</b></p>
<form action="/schedule/comment/add" id="comment-form">
    <div class="form-group row">
        <div class="col-sm-12">
            <textarea name="comment" class="form-control mce"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-4">
            <button name="submit" type="submit" class="btn btn-primary">{{ trans('buttons.event.add_comment') }}</button>
            <span class="preloader" style="display:none"><img class="comment-spinner" alt="'.trans('labels.general.loading').'" src="/img/frontend/spinner.gif"></span>
            <input name="event_id" type="hidden" value="{{ $request->event_id }}">
            <input name="schedule_request_id" type="hidden" value="{{ $request->id }}">
        </div>
    </div>

    {{-- Only show the "send email" checbox DIV if you have scheduling permission. --}}
    <div class="form-group row"
     @permission('scheduling')
     {{-- Do nothing --}}
     @else
        style='display:none;'
     @endauth
     >
        <div class="col-sm-12">
            <input type="checkbox" name="email_comment" value="true" id="email_comment" checked>
            <label for="email_comment">{{ trans('labels.scheduling.send_comment_email') }}</label>
        </div>
    </div>

    
    @if($request->event_id)
        <div class="form-group row">
            <div class="col-sm-4">
                <!-- decide if resolved checkbox needs checked -->
                @if($request->eventResolved() == 1)
                    @php($checked = 'checked')
                @else
                    @php($checked = null)
                @endif
                @permission('scheduling')
                    <input type="checkbox" name="resolved" value="" id="resolved" {{ $checked }}>
                    <label for="resolved">{{ trans('labels.scheduling.resolved_comment') }}</label>
                @endauth
            </div>
        </div>
    @endif
    
</form>
<span class="comments-section">
    {!! $request->comments() !!}
</span>
