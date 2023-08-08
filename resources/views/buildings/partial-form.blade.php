<div class="form-group row">
        {{ Form::label('name', trans('labels.buildings.name'), ['class' => 'col-lg-2 control-label required text-md-right']) }}
        <div class="col-lg-10">
            {{ Form::input('name', 'name', null, ['class' => 'form-control', 'placeholder' => trans('labels.buildings.name')]) }}
        </div><!--col-lg-10-->
    </div><!--form-group-->

<div class="form-group row">
    {{ Form::label('abbrv', trans('labels.buildings.abbrv'), ['class' => 'col-lg-2 control-label required text-md-right']) }}
    <div class="col-lg-10">
        {{ Form::input('abbrv', 'abbrv', null, ['class' => 'form-control', 'placeholder' => trans('labels.buildings.abbrv')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

 <div class="form-group row">
    {{ Form::label('map_url', trans('labels.buildings.map_url'), ['class' => 'col-lg-2 control-label text-md-right']) }}
    <div class="col-lg-10">
        {{ Form::input('map_url', 'map_url', null, ['class' => 'form-control', 'placeholder' => trans('labels.buildings.map_url')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

<div class="form-group row">
    {{ Form::label('more_info', trans('labels.buildings.more_info'), ['class' => 'col-lg-2 control-label text-md-right']) }}
    <div class="col-lg-10">
        {{ Form::textarea('more_info', null, ['class' => 'form-control', 'placeholder' => trans('labels.buildings.more_info')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

 <div class="form-group row">
    {{ Form::label('address', trans('labels.buildings.street_address'), ['class' => 'col-lg-2 control-label text-md-right']) }}
    <div class="col-lg-3">
        {{ Form::input('address', 'address', null, ['class' => 'form-control', 'placeholder' => trans('labels.buildings.street_address')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

 <div class="form-group row">
    {{ Form::label('city', trans('labels.buildings.city'), ['class' => 'col-lg-2 control-label text-md-right']) }}
    <div class="col-lg-3">
        {{ Form::input('city', 'city', null, ['class' => 'form-control', 'placeholder' => trans('labels.buildings.city')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

 <div class="form-group row">
    {{ Form::label('state', trans('labels.buildings.state'), ['class' => 'col-lg-2 control-label text-md-right']) }}
    <div class="col-lg-3">
        {{ Form::input('state', 'state', null, ['class' => 'form-control', 'placeholder' => trans('labels.buildings.state')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

 <div class="form-group row">
    {{ Form::label('postal_code', trans('labels.buildings.postal_code'), ['class' => 'col-lg-2 control-label text-md-right']) }}
    <div class="col-lg-3">
        {{ Form::input('postal_code', 'postal_code', null, ['class' => 'form-control', 'placeholder' => trans('labels.buildings.postal_code')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

 <div class="form-group row">
    {{ Form::label('display_order', trans('labels.buildings.display_order'), ['class' => 'col-lg-2 control-label required text-md-right']) }}
    <div class="col-lg-3">
        {{ Form::input('display_order', 'display_order', null, ['class' => 'form-control', 'placeholder' => trans('labels.buildings.display_order')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

 <div class="form-group row">
    {{ Form::label('timezone', trans('labels.buildings.timezone'), ['class' => 'col-lg-2 control-label required text-md-right']) }}
    <div class="col-lg-3">
        {{ Form::select('timezone', $time_zones, null, ['class' => 'form-control']) }}
   </div><!--col-lg-10-->
</div><!--form-group-->




