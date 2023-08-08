<div class="form-group row">
    {{ Form::label('abbrv', trans('labels.sites.abbrv'), ['class' => 'col-lg-2 control-label text-md-right']) }}
    <div class="col-lg-10">
        {{ Form::input('abbrv', 'abbrv', null, ['class' => 'form-control', 'placeholder' => trans('labels.sites.abbrv')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

<div class="form-group row">
    {{ Form::label('name', trans('labels.sites.name'), ['class' => 'col-lg-2 control-label text-md-right']) }}
    <div class="col-lg-10">
        {{ Form::input('name', 'name', null, ['class' => 'form-control', 'placeholder' => trans('labels.sites.name')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

<div class="form-group row">
    {{ Form::label('organization_name', trans('labels.sites.organization_name'), ['class' => 'col-lg-2 control-label text-md-right']) }}
    <div class="col-lg-10">
        {{ Form::input('organization_name', 'organization_name', null, ['class' => 'form-control', 'placeholder' => trans('labels.sites.organization_name')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

<div class="form-group row">
    {{ Form::label('email', trans('labels.sites.email'), ['class' => 'col-lg-2 control-label text-md-right']) }}
    <div class="col-lg-10">
        {{ Form::input('email', 'email', null, ['class' => 'form-control', 'placeholder' => trans('labels.sites.email')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

<div class="form-group row">
    {{ Form::label('url_root', trans('labels.sites.url'), ['class' => 'col-lg-2 control-label text-md-right']) }}
    <div class="col-lg-10">
        {{ Form::input('url_root', 'url_root', null, ['class' => 'form-control', 'placeholder' => trans('labels.sites.url')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->
