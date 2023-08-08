{{-- Content Item - Page modal --}}
<div id="pageModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="pull-left modal-title" id="ModalLabel">Create new </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="courseContent" action="/course/content">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="course_id" value="{{ $id }}">
                <input type="hidden" name="created_by" value="{{ Auth::id() }}">
                <input type="hidden" name="parent_id" value="">
                <input type="hidden" name="content_type_id" value="">
                <input type="hidden" name="viewer_type_id" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="menu_title">Title</label>
                        <input class="form-control title" name="menu_title" maxlength="75" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('buttons.general.cancel')}}</button>
                    <button type="submit" class="btn btn-primary">{{trans('buttons.general.crud.create')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>