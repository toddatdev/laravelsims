

        <div class="form-group row">
            {{ Form::label('abbrv', trans('labels.courseCategory.abbrv'), ['class' => 'col-lg-2 control-label text-md-right']) }}
            <div class="col-lg-10">
                {{ Form::input('abbrv', 'abbrv', null, ['class' => 'form-control', 'maxlength' => 25, 'placeholder' => trans('labels.courseCategory.abbrv')]) }}
            </div><!--col-lg-10-->
        </div><!--form-group-->

        <div class="form-group row">
            {{ Form::label('description', trans('labels.courseCategory.description'), ['class' => 'col-lg-2 control-label text-md-right']) }}
            <div class="col-lg-10">
                {{ Form::input('description', 'description', null, ['class' => 'form-control', 'maxlength' => 100, 'placeholder' => trans('labels.courseCategory.description')]) }}
            </div><!--col-lg-10-->
        </div><!--form-group-->





