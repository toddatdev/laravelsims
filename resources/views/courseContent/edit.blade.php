@extends('frontend.layouts.app')

@section ('title', trans('menus.backend.courseCurriculum.title'))

@section('after-styles')
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@600;700;800&display=swap" rel="stylesheet">

    <style>

        #progress {
            display: block;
            width: 400px;
        }

        #progress .bar {
            height: 24px;
            width: 0%;
            background: lightblue;
            text-align: center;
        }

        .modal-dialog {
            transform: translateY(40px) !important;
        }

        .modal input {
            border-radius: 0;
        }

        #videoModal #progressBar {
            background-color: #2f3640;
            height: 6px;
        }

        .sortable-modules > .list-group-item {
            margin-top: 15px;
        }

        .module-subitem a,
        .module-subitem a:active,
        .module-subitem a:hover,
        .module-subitem a:visited {
            color: #636b6f;
            cursor: pointer;
        }

        .retired {
            color: gray;
            background-color: #e8e8e8 !important;
        }

        #modules2 a .fa, #modules1 a .fa {
            /*font-size: 1.2rem !important;*/
            width: 20px;
            height: 20px;
            line-height: 1.2rem;
        }

        .fa-presentation, .fa-edit, .fa-trash, .fa-pause {
            font-size: .89rem !important;
        }

        #modules2 li, #modules1 li {
            line-height: 1.6rem;
        }

        label.error {
            font-weight: 300 !important;
            color: maroon;
        }

        .fa-grip-vertical:hover {
            cursor: pointer;
        }

        .hidden {
            display: none;
        }
    </style>
@stop

@section('page-header')
    @if(strpos(url()->previous(), 'courseInstanceEmails') == false) {{-- do not reset when returning from credit or edit--}}
        @if (strpos(url()->previous(), 'mycourses') !== false)
            <?php session(['breadcrumbLevel1' => 'mycourses']); ?>
        @elseif (strpos(url()->previous(), '/courses/') !== false)
            <?php session(['breadcrumbLevel1' => 'courses']); ?>
        @endif
    @endif

    <div class="row">
        <div class="col-lg-9">
            <h4>
                {{trans('menus.backend.course.curriculum')}}
            </h4>
        </div><!-- /.col -->
        <div class="col-lg-3">
            <ol class="breadcrumb float-sm-right">
                @if (Session::get('breadcrumbLevel1') == 'mycourses')
                    <li class="breadcrumb-item">{{ link_to('/mycourses', trans('navs.frontend.course.my_courses'), ['class' => '']) }}</li>
                @elseif (Session::get('breadcrumbLevel1') == 'courses')
                    <li class="breadcrumb-item">{{ link_to('/courses/active?id='.$course->id, trans('menus.backend.course.title'), ['class' => '']) }}</li>
                @endif
                <li class="breadcrumb-item active">{{ $course->abbrv }}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $course->name }}</h3>
        </div>
        <div class="card-body">
            @foreach($viewerType as $header)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title module-title w-100" style="font-family: Raleway; font-weight: 700; font-size: 23px; letter-spacing: .5px; ">{{$header->description}}</h5>
                        <div class="module-actions w-100 text-right">
                            @if(isset($courseContents[$header->id]) && count($courseContents[$header->id]) > 0)
                                @foreach($courseContents[$header->id] as $module)
                                    @php
                                        $breakLoop = false;
                                    @endphp
                                    @foreach($module->contentItems as $item)
                                        @if($item->retired_date == null)
                                            <a
                                                href="/course/content/page/{{$item->id}}/preview"
                                                class="btn btn-dark btn-sm">
                                                    Preview
                                            </a>
                                            @php
                                                $breakLoop = true;
                                            @endphp
                                            @break
                                        @endif
                                    @endforeach
                                    @if($breakLoop)
                                        @break;
                                    @endif
                                @endforeach
                            @endif
                            <button class="btn btn-primary btn-sm">
                                <span class="add-module" data-viewer-type="{{$header->id}}"
                                      data-content-type-id="1">
                                    {{ trans('buttons.curriculum.add_module') }}
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body px-3 pt-3 pb-0">
                        @if(isset($courseContents[$header->id]))
                            <div id="modules{{$header->id}}"
                                class="sortable-modules connectedSortable"
                                data-viewer-type="{{$header->id}}">
                                @foreach($courseContents[$header->id] as $module)
                                    @include('courseContent.partial-course-item', ['viewerTypeId' => $header->id])
                                @endforeach
                            </div>
                        @else
                            <p class="info pl-10">
                                {{ trans('alerts.backend.courseCurriculum.there_is_no_modules_start') . $header->description . trans('alerts.backend.courseCurriculum.there_is_no_modules_end')}}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @include('courseContent.partial-item-page-modal')
    @include('courseContent.partial-item-file-modal')
    @include('courseContent.partial-item-apqs-upload')
    @include('courseContent.partial-item-qse-modal')
@endsection

@section('after-scripts')
    {{ Html::script("/js/sweetalert/sweetalert.min.js") }}
    {{ Html::script("/js/jquery-ui.js") }}
    {{ Html::script("/js/jquery.validate.min.js") }}

    <script>
        $(function () {
            if (!getCookie(cookieName)) {
                setCookie(cookieName, JSON.stringify([]));
            }

            $('.sortable-modules').sortable({
                //tolerance: "pointer",
                connectWith: '.connectedSortable',
                cancel: '',
                items: ".module-item",
                //forcePlaceholderSize: true,
                handle: '.module-handle',
                start: function (e, ui) {
                    $(this).attr('data-oldindex', ui.item.index());
                    $(this).attr('data-parentId', ui.item.data("parent"));
                    $(this).attr('data-viewer-type', ui.item.data("viewer-type"));
                },
                update: function (event, ui) {
                    let sortOrder = $(this).sortable('toArray', {attribute: 'data-id'});
                    $(this).removeAttr('data-oldindex');
                    $.post("/course/content/updateOrder", {
                        parentId: null,
                        sortOrder: sortOrder,
                        viewerType: $(this).data('viewer-type')
                    });
                }
            });


            // Sort items
            $('.sortable-items').sortable({
                connectWith: '.sortable-items',
                handle: '.sort-handle',
                cancel: '',
                items: ".module-subitem",
                start: function (e, ui) {
                    $(this).attr('data-oldindex', ui.item.index());
                    $(this).attr('data-parentId', ui.item.data("parentId"));
                    $(this).attr('data-viewer-type-id', ui.item.data("viewerTypeId"));
                    $(this).attr('data-id', ui.item.data("id"));
                },

                // triggers when item is dragged to another module
                receive: function (event, ui) {
                    let parentId = ui.item.parent().data('module-id');
                    let viewerTypeId = ui.item.parent().data('viewer-type-id');
                    let sortOrder = $(this).sortable('toArray', {attribute: 'data-id'});
                    $(this).removeAttr('data-oldindex');

                    $.post("/course/content/updateOrder", {
                        parentId: parentId,
                        sortOrder: sortOrder,
                        viewerType: viewerTypeId
                    });

                    // set parent-id to the one of the new parent
                    $("#item-" + ui.item.data("id")).attr('data-parent-id', parentId);
                },

                // triggers when sortorder changes within the module changes (always triggers before Receive)
                update: function (event, ui) {
                    let parentId = $(this).attr('data-parentId');
                    let moduleId = ui.item.parent().data('module-id');

                    if (parentId) {
                        let viewerTypeId = $(this).attr('data-viewer-type-id');
                        let sortOrder = $(this).sortable('toArray', {attribute: 'data-id'});
                        $(this).removeAttr('data-oldindex');

                        $.post("/course/content/updateOrder", {
                            sortOrder: sortOrder,
                            viewerType: viewerTypeId
                        });
                    }
                }
            });


            $('.video-upload').on('click', function (ev) {
                openModal(ev, 'video-upload', $(this).data('viewer-type-id'), $(this).data('content-type-id'), $(this).data('parent-id'));
            });

            $('.document-upload').on('click', function (ev) {
                openModal(ev, 'document-upload', $(this).data('viewer-type-id'), $(this).data('content-type-id'), $(this).data('parent-id'));
            });

            $('.any-file-upload').on('click', function (ev) {
                openModal(ev, 'any-file-upload', $(this).data('viewer-type-id'), $(this).data('content-type-id'), $(this).data('parent-id'));
            });

            $('.add-page').on('click', function (ev) {
                openModal(ev, 'page-add', $(this).data('viewer-type-id'), $(this).data('content-type-id'), $(this).data('parent-id'));
            });


            $('.page-rename').off('click').on('click', function (ev) {
                openModal(ev, 'page-rename', $(this).data('viewer-type-id'), $(this).data('content-type-id'), $(this).data('parent-id'), $(this).data('id'), $(this).data('menu-title'));
            });

            $('.add-module').on('click', function (ev) {
                openModal(ev, 'module-add', $(this).data('viewer-type'), $(this).data('content-type-id'), 0);
            });

            $('.module-rename').on('click', function (ev) {
                openModal(ev, 'module-rename', $(this).data('viewer-type'), $(this).data('content-type-id'), 0, $(this).data('id'), $(this).data('menu-title'));
            });

            $('.presenter-upload').on('click', function (ev) {
                openModal(ev, 'presenter-upload', $(this).data('viewer-type-id'), $(this).data('content-type-id'), $(this).data('parent-id'), $(this).data('id'), $(this).data('menu-title'));
            });

            $('.add-qse').on('click', function (ev) {
                openModal(ev, 'add-qse', $(this).data('viewer-type-id'), $(this).data('content-type-id'), $(this).data('parent-id'), $(this).data('id'), $(this).data('menu-title'));
            });

            $('.module-delete').on('click', function (ev) {
                if ($(this).data('content-items') > 0) {
                    swal("", "{{ trans('alerts.backend.courseCurriculum.cannot_delete_module_with_content')  }}", "info")
                } else {
                    deleteModal($(this).data('id'), $(this).data('menu-title'));
                }
            });

            function openModal(ev, modalType, viewerTypeId, contentTypeId, parentId, id, title) {
                ev.preventDefault();
                ev.stopPropagation();

                let modalTitle = '';
                let modal = '#pageModal'
                let method = 'POST';
                let uploadFileType = 'video';

                switch (modalType) {
                    case 'page-add':
                        modalTitle = 'Create new page';
                        addCollapsedModule(parentId);
                        break;
                    case 'video-upload':
                        modal = '#fileModal';
                        modalTitle = 'Upload new video';
                        $('#menuLabel').text('Video title');
                        $('#fileLabel').text('Choose a video');
                        $('#file').attr('accept', "video/mp4");
                        $('#allowedFileTypes').text('Max file size should be 500MB (.mp4)')
                        addCollapsedModule(parentId);
                        break;

                    case 'document-upload':
                        modal = '#fileModal';
                        modalTitle = 'Upload new Office Doc';
                        uploadFileType = 'document';
                        $('#menuLabel').text('Document title');
                        $('#fileLabel').text('Choose a document');
                        $('#allowedFileTypes').text('Max file size should be 10MB (xlxs, pptx, docx, pdf)');
                        $('#file').attr('accept', false);
                        addCollapsedModule(parentId);
                        break;

                    case 'any-file-upload':
                        modal = '#fileModal';
                        modalTitle = 'New file';
                        uploadFileType = 'anyFile';
                        $('#menuLabel').text('File title');
                        $('#fileLabel').text('Choose a file');
                        $('#allowedFileTypes').text('Max file size should be 100MB');
                        $('#file').attr('accept', false);
                        addCollapsedModule(parentId);
                        break;
                    case 'page-rename':
                        modalTitle = 'Rename page title';
                        method = 'PUT';
                        $('#courseContent').attr('action', '/course/content/' + id);
                        $('#pageModal [name="menu_title"]').val(title);
                        break;
                    case 'module-add':
                        modalTitle = 'Create new module';
                        break;
                    case 'module-rename':
                        modalTitle = 'Edit module title';
                        method = 'PUT';
                        $('#courseContent').attr('action', '/course/content/' + id);
                        $('#pageModal [name="menu_title"]').val(title);
                        break;
                    case 'presenter-upload':
                        modalTitle = 'Upload Articulate';
                        modal = '#apqsModal';
                        uploadFileType = 'articulate';
                        addCollapsedModule(parentId);
                        break;

                    case 'add-qse':
                        modalTitle = `{{ trans('navs.frontend.qse.add_qse_to') }} ` + title;
                        modal = '#qseModal';
                        addCollapsedModule(parentId);
                        break;
                }

                $(modal).find('.modal-title').html(modalTitle);
                $(modal).find('[name="_method"]').val(method);
                $(modal).find('[name="viewer_type_id"]').val(viewerTypeId);
                $(modal).find('[name="parent_id"]').val(parentId);
                $(modal).find('[name="content_type_id"]').val(contentTypeId);
                $(modal).find('[name="upload_file_type"]').val(uploadFileType);
                $(modal).modal({backdrop: 'static', keyboard: false});

                //sets focus on the title input
                $(modal).find('.title').focus();
            }

            $("#fileModal").on('hidden.bs.modal', function () {
                $('#loaded_n_total').show();

                $('#progressBar').show();
                $('#processingVideo').hide();
                $('#loaded_n_total').hide();
                $('#file').val('');
                $('#menuTitle').val('');
            });

            $('.collapse').on('show.bs.collapse', function (ev) {
                addCollapsedModule($(this).data('moduleId'));
            }).on('hide.bs.collapse', function (ev) {
                removeCollapsedModule($(this).data('moduleId'));
            });

            let collapsedModules = JSON.parse(getCookie(cookieName));
            collapsedModules.forEach(function (module) {
                let divClass = '#menuTitleAcc' + module;
                $('#collapseItem' + module).collapse('show');

                setTimeout(function () {
                    $(divClass + ' .collapse-down-icon').addClass('hidden');
                    $(divClass + ' .collapse-up-icon').removeClass('hidden');
                });
            });
        });


        function deleteModal(id, title) {
            let href = '/course/content/' + id + '/delete';
            swal({
                title: "{{ trans('alerts.backend.courseCurriculum.delete_module') }} " + title + '?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then(function (isConfirmed) {
                    if (isConfirmed) {
                        window.location.href = href;
                    } else {
                    }
                });
        }

        function deleteFile(el) {

            let href = '/course/content/' + el.dataset.id + '/delete';

            swal({
                title: "<?php echo e(trans('alerts.backend.courseCurriculum.delete_module')); ?> " + el.dataset.title + '?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then(function (isConfirmed) {
                    if (isConfirmed) {
                        window.location.href = href;
                    } else {
                    }
                });
        }


        function deletePage(el) {
            let href = '/course/content/' + el.dataset.id + '/delete';
            swal({
                title: "{{ trans('alerts.backend.courseCurriculum.delete_module') }} " + el.dataset.title + '?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then(function (isConfirmed) {
                    if (isConfirmed) {
                        window.location.href = href;
                    } else {
                    }
                });
        }


        const cookieName = 'modules_collapsed';

        function removeCollapsedModule(id) {
            let divClass = '#menuTitleAcc' + id;
            $(divClass + ' .collapse-up-icon').addClass('hidden');
            $(divClass + ' .collapse-down-icon').removeClass('hidden');
            $(divClass + ' .collapse-down-icon').removeClass('hidden');
            let cookie = JSON.parse(getCookie(cookieName));
            if (cookie.includes(id)) {
                cookie = cookie.filter(function (el) {
                    return el != id
                });
            }
            setCookie(cookieName, JSON.stringify(cookie));
        }

        function addCollapsedModule(id) {
            let divClass = '#menuTitleAcc' + id;
            $(divClass + ' .collapse-down-icon').addClass('hidden');
            $(divClass + ' .collapse-up-icon').removeClass('hidden');
            let cookie = JSON.parse(getCookie(cookieName));
            if (!cookie.includes(id)) {
                cookie.push(id);
            }
            setCookie(cookieName, JSON.stringify(cookie));
        }

        function setCookie(cname, cvalue) {
            let exdays = 30;
            let d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            let expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        /**
         * ================================================
         * IMPLEMENTING Video Upload and Validation Feature
         * ================================================
         */


        var fileUploadForm = $('#fileUploadForm');
        var btnUploadFile = $('#btnUploadFile');

        var zipUploadForm = $('#zipUploadForm');
        var btnUploadZipFile = $('#btnUploadZipFile');

        //Limit max upload video size.
        $('[name=file]').on('change', function () {
            var uploadType = $(this).closest('form').find('[name=upload_file_type]').val();
            var fileSize = $(this)[0].files[0].size / 1024 / 1024;
            fileSize = fileSize.toFixed(2);

            var fileType = this.files[0].type;

            if (uploadType === 'anyFile' && fileSize > 100) {

                alert('too big, maximum is 100MB. You file size is: ' + fileSize + ' MB');
                $(this).val('');
            }

            if (fileSize > 500 && uploadType === 'video') {
                alert('too big, maximum is 500MB. You file size is: ' + fileSize + ' MB');
                $(this).val('');
            }

            //allowed doc types
            var allowedTypes = [
                'application/pdf',

                'application/excel',
                'application/vnd.ms-excel',
                'application/x-excel',
                'application/x-msexcel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

                'application/doc',
                'application/ms-doc',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',

                'application/mspowerpoint',
                'application/powerpoint',
                'application/vnd.ms-powerpoint',
                'application/x-mspowerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',

            ];

            //if uploaded doc is greater than 10MB
            if (fileSize > 10 && uploadType == 'document') {

                alert('too big, maximum is 10MB. You file size is: ' + fileSize + ' MB');
                $(this).val('');
            }

            //if uploaded file is not a valid document
            if (!allowedTypes.includes(fileType) && uploadType === 'document') {

                alert('please select a valid file type(xlxs,pptx,docx,pdf)');
                $(this).val('');
            }

            //re-validate form on file upload
            fileUploadForm.valid();
            zipUploadForm.valid();
        });

        //function to get the element by id
        function _(el) {
            return document.getElementById(el);
        }

        //validates logic for video upload form
        fileUploadForm.validate({
            rules: {
                menu_title: {required: true},
            },
            messages: {
                menu_title: {required: 'title is required.'},
            }
        });

        zipUploadForm.validate({
            rules: {
                menu_title: {required: true},
            },
            messages: {
                menu_title: {required: 'title is required.'},
            }
        });

        //form submission on submit button click
        btnUploadFile.on('click', function (e) {
            e.preventDefault();

            //if form is empty stop submission
            if (!fileUploadForm.valid()) return 0;

            //show loading spinner
            showSpinner(btnUploadFile);

            //call upload file process
            uploadFile();
        });

        //form submission on submit button click
        btnUploadZipFile.on('click', function (e) {
            e.preventDefault();

            //if form is empty stop submission
            if (!zipUploadForm.valid()) return 0;

            //show loading spinner
            showSpinner(btnUploadZipFile);

            var formdata = new FormData(document.getElementById('zipUploadForm'));

            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.upload.addEventListener("progress", function (event) {
                $('#progressBarZip').removeClass('hidden');
                var percent = (event.loaded / event.total) * 100;
                _("progressBarZip").value = Math.round(percent);
                _("loaded_n_total_zip").innerHTML = "(" + Math.round(percent) + "%)" + " Uploaded " + event.loaded + " bytes of " + event.total;
                $('#loaded_n_total_zip').show();
                $('#progressBarZip').show();

                if (Math.round(percent) === 100) {
                    $('#processingFile').show()
                        .html('<p id="processingFile"><span class="fa fa-spinner fa-spin"></span> Processing file...</p>');
                }
            }, false);
            ajax.addEventListener("load", function (event) {

                var response = $.trim(event.target.responseText);
                var responseJSON = JSON.parse(response);

                btnUploadFile.attr('disabled', false);

                $('#zip-aws-file-url').val(responseJSON.aws_url);
                $('#processingZipFile').html('<span class="text-success">uploaded...</span>');
                $('#loaded_n_total_zip').hide();
                $('#progressBarZip').hide();

                //clear video file because it's already pushed to AWS server
                $('#zipFile').val('').removeAttr('required');

                //hide loading spinner
                // hideSpinner(btnUploadFile);

                //submit form
                $('#zipUploadForm').submit();

                _("progressBarZip").value = 0; //will clear progress bar after successful upload
            }, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.addEventListener("abort", abortHandler, false);
            ajax.open("POST", "{{route('course.file.upload', $id)}}", true);
            ajax.send(formdata);
        });

        function uploadFile() {

            var formdata = new FormData(document.getElementById('fileUploadForm'));

            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.addEventListener("abort", abortHandler, false);
            ajax.open("POST", "{{route('course.file.upload', $id)}}", true);
            ajax.send(formdata);
        }

        function progressHandler(event) {

            $('#progressBar').removeClass('hidden');
            var percent = (event.loaded / event.total) * 100;
            _("progressBar").value = Math.round(percent);
            _("loaded_n_total").innerHTML = "(" + Math.round(percent) + "%)" + " Uploaded " + event.loaded + " bytes of " + event.total;
            $('#loaded_n_total').show();
            $('#progressBar').show();

            if (Math.round(percent) === 100) {
                $('#processingFile').show()
                    .html('<p id="processingFile"><span class="fa fa-spinner fa-spin"></span> Processing file...</p>');
            }
        }

        function completeHandler(event) {

            var response = $.trim(event.target.responseText);
            var responseJSON = JSON.parse(response);

            btnUploadFile.attr('disabled', false);

            $('#aws-file-url').val(responseJSON.aws_url);
            $('#processingFile').html('<span class="text-success">uploaded...</span>');
            $('#loaded_n_total').hide();
            $('#progressBar').hide();

            //clear video file because it's already pushed to AWS server
            $('#file').val('').removeAttr('required');

            //hide loading spinner
            hideSpinner(btnUploadFile);

            //submit form
            $('#fileUploadForm').submit();

            _("progressBar").value = 0; //will clear progress bar after successful upload
        }

        function errorHandler(event) {
            _("status").innerHTML = "Upload Failed";
        }

        function abortHandler(event) {
            _("status").innerHTML = "Upload Aborted";
        }


        function showSpinner(btn) {
            btn.find('.fa-spinner').removeClass('hidden');
            btn.attr('disabled', true);
        }

        function hideSpinner(btn) {
            btn.find('.fa-spinner').addClass('hidden');
            btn.attr('disabled', false);
        }

        $(document).on('show.bs.dropdown', '.dropdown', function (e) {
            $(this).find('button.bop').addClass('active');
        });
        $(document).on('hide.bs.dropdown', '.dropdown', function (e) {
            $(this).find('button.bop').removeClass('active');
        })
    </script>
@stop

{{--ToDo: Move CSS to styles.css --}}
{{--Todo: Move JS to the backend/app.js--}}




