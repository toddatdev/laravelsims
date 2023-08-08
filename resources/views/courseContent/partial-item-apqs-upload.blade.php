{{-- Content Item - Page modal --}}
<div id="apqsModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="pull-left modal-title" id="modalLabel">Upload  Articulate/Presenter/Quizmaker/Storyline</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="zipUploadForm" action="/course/content" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="course_id" value="{{ $id }}">
                <input type="hidden" name="created_by" value="{{ Auth::id() }}">
                <input type="hidden" name="parent_id" value="">
                <input type="hidden" name="content_type_id" value="">
                <input type="hidden" name="viewer_type_id" value="">
                <input type="hidden" name="upload_file_type" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="menu_title" id="menuLabel">Title</label>
                        <input class="form-control title" id="menuTitle" name="menu_title" maxlength="75" required>
                    </div>
                    <div class="form-group">
                        <label for="file_type">File type</label>
                        <select id="file_type" name="file_type" class="form-control">
                            <option value="articulate" selected>Articulate/Presenter (presentation_html5.html)</option>
                            <option value="engage">Engage (interaction_html5.html)</option>
                            <option value="quizmaker">Quiz Maker (quiz_html5.html)</option>
                            <option value="rise">Rise (content/index.html)</option>
                            <option value="storyline">Storyline (story.html)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="menu_title" id="fileLabel">Choose a zip file</label>
                        <input class="form-control" id="zipFile" type="file" name="file" required accept=".zip">
                        <input type="hidden" name="MAX_FILE_SIZE" value="100" />
                        <input type="text" name="aws_file_url" id="zip-aws-file-url" style="display: none">
                        <small style="font-size: .99rem" class="text-small"><sup>*</sup>
                            <span id="allowedFileTypes">Max file size should be 100MB (zip only)</span></small><br>
                        <div id="processingZipFile"></div>
                        <progress id="progressBarZip" class="hidden" value="0" max="100" style="width:100%;"></progress>
                        <p id="loaded_n_total_zip"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{trans('buttons.general.cancel')}}</button>
                    <button type="submit" id="btnUploadZipFile" class="btn btn-primary">
                        <i class="fa fa-spinner fa-spin hidden"></i> {{trans('buttons.general.crud.create')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

