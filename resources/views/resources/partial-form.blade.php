{{ Html::script("js/jquery.js") }}

<div class="form-group row">
    {{ Form::label('description', trans('labels.resources.full_name'), ['class' => 'col-lg-2 control-label text-lg-right required']) }}
    <div class="col-lg-10">
        {{ Form::input('description', 'description', null, ['class' => 'form-control', 'maxlength' => 50, 'placeholder' => trans('labels.resources.full_name')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

<div class="form-group row">
    {{ Form::label('abbrv', trans('labels.resources.abbrv'), ['class' => 'col-lg-2 control-label text-lg-right required']) }}
    <div class="col-lg-10">
        {{ Form::input('abbrv', 'abbrv', null, ['class' => 'form-control', 'maxlength' => 25, 'placeholder' => trans('labels.resources.abbrv')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

<div class="form-group row">
    {{ Form::label('type', trans('labels.resources.type'), ['class' => 'col-lg-2 control-label text-lg-right required']) }}
    <div class="col-lg-10">
        {{--{{ Form::select('resource_type_id', $resourceTypes, null, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) }}--}}

        @foreach ($resourceTypes as $resourceType)

            <div>
                {{ Form::radio('resource_type_id', $resourceType->id, false, ['class' => 'radio','style'=>'display:inline-block; vertical-align: middle;']) }}
                <span title="{{ $resourceType->abbrv }}">{{ $resourceType->abbrv }}</span>
            </div>

        @endforeach

    </div><!--col-lg-10-->
</div><!--form-group-->

<div class="form-group row">
    {{ Form::label('location', trans('labels.resources.location'), ['class' => 'col-lg-2 control-label text-lg-right required']) }}
    <div class="col-lg-3">
        {{ Form::select('location_id', $locations, null, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

<div class="form-group row">
    {{ Form::label('category', trans('labels.resources.category'), ['class' => 'col-lg-2 control-label text-lg-right required']) }}
    <div class="col-lg-3">
        {{ Form::select('resource_category_id', $categories, null, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) }}
    </div><!--col-lg-3-->
</div><!--form-group-->

<div class="form-group row">
    {{ Form::label('subcategory', trans('labels.resources.subcategory'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
    <div class="col-lg-3">
        {{ Form::select('resource_sub_category_id', $subcategories, null, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) }}
    </div><!--col-lg-3-->
</div><!--form-group-->

<script>
    $(document).ready(function() {

        $('select[name="resource_category_id"]').on('change', function(){
            var resourceCategoryId = $(this).val();
            if(resourceCategoryId) {
                $.ajax({
                    url: '/resourcesubcategory/get/'+resourceCategoryId,
                    type:"GET",
                    dataType:"json",
                    beforeSend: function(){
                        $('#loader').css("visibility", "visible");
                    },

                    success:function(data) {

                        $('select[name="resource_sub_category_id"]').empty();
                        $('select[name="resource_sub_category_id"]').prepend('<option value="0">Select...</option>');


                        $.each(data, function(key, value){

                            $('select[name="resource_sub_category_id"]').append('<option value="'+ key +'">' + value + '</option>');

                        });
                    },

                });
            } else {
                $('select[name="resource_sub_category_id"]').empty();
            }

        });

    });
</script>










