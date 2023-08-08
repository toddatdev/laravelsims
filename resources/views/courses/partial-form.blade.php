
    {{--These two includes are needed to format the image file input--}}
    {{ Html::script("js/jquery.js") }}
    <script type="text/javascript" src="{{ asset('/js/bootstrap-filestyle.min.js') }}"></script>

    {{--Tiny MCE Editor for Course Description--}}
    <script type="text/javascript" src="{{ asset('/js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: 'textarea',
            browser_spellcheck: true,
            menubar: false,
            plugins: [
                'advlist autolink lists link charmap print preview anchor textcolor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime table paste code help wordcount'
            ],
            toolbar: 'undo redo |  formatselect | bold italic underline backcolor forecolor removeformat  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link code | table',
        });
    </script>

    <div class="form-group row">
        {{ Form::label('name', trans('labels.courses.name'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
        <div class="col-lg-10">
            {{ Form::input('name', 'name', null, ['class' => 'form-control', 'placeholder' => trans('labels.courses.name')]) }}
        </div><!--col-lg-10-->
    </div><!--form-group-->

    <div class="form-group row">
        {{ Form::label('abbrv', trans('labels.courses.abbrv'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
        <div class="col-lg-10">
            {{ Form::input('abbrv', 'abbrv', null, ['class' => 'form-control', 'placeholder' => trans('labels.courses.abbrv')]) }}
        </div><!--col-lg-10-->
    </div><!--form-group-->

    <div class="form-group row">
        {{ Form::label('author_name', trans('labels.courses.author_name'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
        <div class="col-lg-10">
            {{ Form::input('author_name', 'author_name', null, ['class' => 'form-control', 'placeholder' => trans('labels.courses.author_name')]) }}
        </div><!--col-lg-10-->
    </div><!--form-group-->

    {{--When a checkbox is unchecked, no value is returned from the form, therefore on edit when it is
        unchecked, nothing is changed.  To "fix" this a hidden field is added prior to the checkbox that will pass
        a zero value when unchecked. This will also make the default value zero on create.--}}
    {{ Form::hidden('virtual',0) }}

    <div class="form-group row">
        {{ Form::label('virtual', trans('labels.courses.virtual'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
        <div class="col-lg-10">
            <div class="form-check">
                @if( isset($course) )
                    @if($course->virtual == "1")
                        {{ Form::checkbox('virtual', 1, true, ['class' => 'form-check-input']) }}
                    @else
                        {{ Form::checkbox('virtual', 1, false, ['class' => 'form-check-input']) }}
                    @endif
                @else
                    {{ Form::checkbox('virtual', 1, false, ['class' => 'form-check-input']) }}
                @endif
                {{ Form::label('virtual_description', trans('labels.courses.virtual_description'), ['class' => 'form-check-label']) }}
            </div>
        </div><!--col-lg-10-->
    </div><!--form-group-->

    <div class="form-group align-top row">
        {{ Form::label('catalog_image', trans('labels.courses.catalog_image'), ['class' => 'col-lg-2 control-label text-lg-right']) }}

            @if( isset($course) )
                <div class="col-lg-5 align-top">

                    @if($course->catalog_image)
                        <img src="{{URL::to($course->catalog_image)}}" class="img-thumbnail mt-10" width="350">
                    @else
                        <span class="alert alert-info alert-block mt-10">{{trans('labels.courses.no_image')}}.</span>
                    @endif

                    <span class="simptip-position-top simptip-smooth" data-tooltip="{{trans('buttons.general.image_new')}}">
                    <a href="/courses/upload-image/{{$course->id}}" class="btn-sm editButton">
                    <i class="fa fa-image fa-lg"></i></a></span>

                    <span class="simptip-position-top simptip-smooth" data-tooltip="{{trans('buttons.general.crud.delete')}}">
                    <a href="/courses/delete-image/{{$course->id}}" class="btn-sm deleteButton">
                    <i class="fa fa-trash fa-lg"></i></a></span>

                </div>
            @else
                <div class="col-lg-5">
                    {{ Form::file('image', ['class' => 'filestyle', 'data-text' =>'Choose File', 'data-btnClass' => 'btn-primary']) }}
                </div>
            @endif

    </div><!--form-group-->

    <div class="form-group row">
        {{ Form::label('catalog_description', trans('labels.courses.catalog_description'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
        <div class="col-lg-10">
            {{ Form::textarea('catalog_description', null, ['class' => 'form-control', 'placeholder' => trans('labels.courses.catalog_description')]) }}
        </div><!--col-lg-10-->
    </div><!--form-group-->





