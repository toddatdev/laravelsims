@extends('frontend.layouts.app')

@section ('title', trans('menus.backend.courseCurriculum.title'))

@section('after-styles')
    {{ Html::script("/js/tinymce/tinymce.min.js") }}
@stop

@section('page-header')
    {{--Only display breadcrumbs if coming from My Courses--}}
    <div class="row">
        <div class="col-lg-9">
            <h4>{{ $courseContent->menu_title }}</h4>
        </div><!-- /.col -->
        <div class="col-lg-3">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">{{ link_to('/mycourses', trans('navs.frontend.course.my_courses'), ['class' => '']) }}</li>
{{--                @if (strpos(url()->previous(), '/courses/') !== false)--}}
{{--                    <li class="breadcrumb-item">{{ link_to('/courses/active?id='.$courseContent->course_id, trans('menus.backend.course.title'), ['class' => '']) }}</li>--}}
{{--                @elseif (strpos(url()->previous(), '/course/content') !== false)--}}
{{--                    <li class="breadcrumb-item">{{ link_to('/course/content/'.$courseContent->course_id.'/edit', trans('menus.backend.course.curriculum'), ['class' => '']) }}</li>--}}
{{--                @endif--}}
                <li class="breadcrumb-item">{{ link_to('/course/content/'.$courseContent->course_id.'/edit', $courseContent->course->abbrv, ['class' => '']) }}</li>
{{--                <li class="breadcrumb-item active">{{ $courseContent->menu_title }}</li>--}}
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('content')
    <form class="card mb-5" name=pageform" method="post" action="/course/content/page/{{ $courseContent->coursePage->id }}">
        @method('PUT')
        @csrf
        <div class="card-header">
            <h3 class="card-title">{{ $courseContent->course->name }}</h3>
            <div class="float-right">
                <strong>Status:</strong>
                @switch($courseContent->publishedStatus)
                    @case(1)
                        {{ trans('labels.course_curriculum.not-published') }}
                    @break
                    @case(2)
                        {{ trans('labels.course_curriculum.same-published') }}
                    @break
                    @case(3)
                        {{ trans('labels.course_curriculum.older-published') }}
                    @break
                @endswitch
                |
                <a href="/course/content/{{ $courseContent->id }}/page/publish">
                    <button type="button"
                            class="publishbtn btn btn-primary btn-sm {{ Session::get('saved') || $courseContent->publishedStatus == 3 ? '' : 'disabledButton'}}">
                        {{ trans('buttons.curriculum.publish') }}
                    </button>
                </a>
                <button onclick="save();" class="savebtn btn btn-success btn-sm">
                    {{ trans('buttons.general.crud.save') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <script>
                tinymce.init({
                    selector: '#pagetext',
                    mode: "textareas",
                    forced_root_block: false,
                    editor_selector: "mce",
                    browser_spellcheck: true,
                    autosave_interval: "30s",
                    autosave_retention: "30m",
                    menubar: false,
                    height: "420",
                    branding: false,
                    plugins: [
                        'advlist autolink autosave lists link charmap print preview anchor ',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime table paste code help wordcount image'
                    ],

                    toolbar: 'undo redo |  formatselect | bold italic underline backcolor forecolor | alignleft aligncenter alignright alignjustify alignnone | bullist numlist | removeformat | link code' +
                        '| image | table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',

                    automatic_uploads: true,
                    images_upload_url: "/course/content/{{$courseContent->course_id}}/uploadimage",
                    file_picker_types: 'image',
                    file_picker_callback: function (cb, value, meta) {
                        var input = document.createElement('input');
                        //input.setRequestHeader('X-CSRF-TOKEN',{{ csrf_token() }});
                        input.setAttribute('type', 'hidden');
                        input.setAttribute('value', '{{ csrf_token() }}');
                        input.setAttribute('enctype', 'multipart/form-data');
                        input.setAttribute('name', 'image');
                        input.setAttribute('accept', 'image/*');
                        console.log(input);
                        input.onchange = function () {
                            var file = this.files[0];

                            var reader = new FileReader();
                            reader.readAsDataURL(file);
                            reader.onload = function () {
                                var id = 'blobid' + (new Date()).getTime();
                                var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                                var base64 = reader.result.split(',')[1];
                                var blobInfo = blobCache.create(id, file, base64);
                                blobCache.add(blobInfo);
                                cb(blobInfo.blobUri(), {title: file.name});
                            };
                        };
                        input.click();
                    }
                });
            </script>
            <input type="hidden" name="course_contents_id" value="{{ $courseContent->id }}">
            <input type="hidden" name="course_id" value="{{ $courseContent->course_id }}">
            <textarea id="pagetext" name="text">{{ $courseContent->coursePage->text ?? '' }}</textarea>
        </div>
    </form>
@endsection

@section('after-scripts')
    <script>
        function save() {

            $('form').submit();
            $('.publishbtn').removeClass('disabledButton');

        }
    </script>
@stop
