

        <div class="form-group row">
            {{ Form::label('abbrv', trans('labels.courseCategoryGroup.abbrv'), ['class' => 'col-lg-2 control-label text-md-right']) }}
            <div class="col-lg-10">
                {{ Form::input('abbrv', 'abbrv', null, ['class' => 'form-control', 'maxlength' => 25, 'placeholder' => trans('labels.courseCategoryGroup.abbrv')]) }}
            </div><!--col-lg-10-->
        </div><!--form-group-->

        <div class="form-group row">
            {{ Form::label('description', trans('labels.courseCategoryGroup.description'), ['class' => 'col-lg-2 control-label text-md-right']) }}
            <div class="col-lg-10">
                {{ Form::input('description', 'description', null, ['class' => 'form-control', 'maxlength' => 50, 'placeholder' => trans('labels.courseCategoryGroup.description')]) }}
            </div><!--col-lg-10-->
        </div><!--form-group-->




