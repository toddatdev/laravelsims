
    <div class="form-group row">
        {{ Form::label('description', trans('labels.resources.full_name'), ['class' => 'col-lg-2 control-label text-md-right']) }}
        <div class="col-lg-10">
            {{ Form::input('description', 'description', null, ['class' => 'form-control', 'maxlength' => 50, 'placeholder' => trans('labels.resources.full_name')]) }}
        </div><!--col-lg-10-->
    </div><!--form-group-->

    <div class="form-group row">
        {{ Form::label('name', trans('labels.resources.abbrv'), ['class' => 'col-lg-2 control-label text-md-right']) }}
        <div class="col-lg-10">
            {{ Form::input('abbrv', 'abbrv', null, ['class' => 'form-control', 'maxlength' => 25, 'placeholder' => trans('labels.resources.abbrv')]) }}
        </div><!--col-lg-10-->
    </div><!--form-group-->

    <div class="form-group row">
        {{ Form::label('type', trans('labels.resources.type'), ['class' => 'col-lg-2 control-label text-md-right']) }}
        <div class="col-lg-10">
            @foreach ($resourceTypes as $resourceType)

                <div>
                    {{ Form::radio('resource_type_id', $resourceType->id, false, ['class' => 'radio','style'=>'display:inline-block']) }}
                    <span title="{{ $resourceType->abbrv }}">{{ $resourceType->abbrv }}</span>
                </div>

            @endforeach

        </div><!--col-lg-10-->
    </div><!--form-group-->





