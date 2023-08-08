{{-- Content Item QSE - Page modal --}}
@php
    $hasEvents = \App\Models\CourseInstance\Event::with('CourseInstance')
                    ->where('parking_lot', '=', 0)
                    ->whereHas('CourseInstance', function($q) use($id){
                        $q->where('course_id', '=', $id);
                    })->exists();
@endphp
<div id="qseModal" class="modal fade"  data-backdrop="static" data-keyboard="false"
     tabindex="-1"
     aria-labelledby="addQSEModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="qseAddForm" action="/course/content">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="course_id" value="{{ $id }}">
                <input type="hidden" name="created_by" value="{{ Auth::id() }}">
                <input type="hidden" name="parent_id" value="">
                <input type="hidden" name="content_type_id" value="">
                <input type="hidden" name="viewer_type_id" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="qse_type">{{ trans('labels.qse.type') }}</label>
                        <select id="qse_type" name="qse_type_id" class="form-control" required>
                            <option value="">{{ trans('labels.general.select') }}</option>
                            @foreach(\App\Models\CourseContent\QSE\QSEType::all() as $qseType)
                                <option value="{{$qseType->id}}">{{ $qseType->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">{{ trans('labels.general.name') }}</label>
                        <input type="text" class="form-control" id="name" name="menu_title" maxlength="75" required />
                    </div>
                    @if($hasEvents)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="add_to_event_qse_activation" id="add_to_all_events" value="add_to_all_events">
                            <label class="form-check-label" for="add_to_all_events">
                                {{ trans('labels.qse.add_qse_to_all_events') }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="add_to_event_qse_activation" id="add_to_all_upcoming_events" value="add_to_all_upcoming_events">
                            <label class="form-check-label" for="add_to_all_upcoming_events">
                                {{ trans('labels.qse.add_qse_to_all_upcoming_events_from_today') }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="add_to_event_qse_activation" id="do_not_add_to_any_event" value="do_not_add_to_any_event" checked>
                            <label class="form-check-label" for="do_not_add_to_any_event">
                                {{ trans('labels.qse.do_not_add_to_any_existing_event') }}
                            </label>
                        </div>
                    @endif
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-warning btn-sm"
                            data-dismiss="modal">{{trans('buttons.general.cancel')}}</button>
                    <button type="submit" id="btnUploadZipFile" class="btn btn-success btn-sm">
                        {{trans('buttons.general.crud.create')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

