
<!-- radios -->
<div class="form-group col-lg-12 form-inline">

    <!-- filter title -->
    <div class="col-lg-1">
        <label class="text-left" style="font-size:12pt"><b>{{trans('labels.event.filter')}}</b></label>
    </div>

    <!-- radio first 3 -->
    <div class="border rounded bg-white p-2 col-md-5 col-xs-12 form-inline">
        <div class="col-xs-4 checkboxRow">
            {{ Form::radio('phys_filter','Room') }}  <strong>{{trans('labels.scheduling.rooms')}}</strong>
        </div>

        <div class="col-xs-4 checkboxRow">
            {{ Form::radio('phys_filter','Equipment') }} <strong>{{trans('labels.event.equipment')}}</strong>
        </div>

        <div class="col-xs-4 checkboxRow">
            {{ Form::radio('phys_filter','Personnel') }} <strong>{{trans('labels.event.personnel')}}</strong>
        </div>
    </div>

    <!-- space -->
    <div class="col-md-1 col-xs-0" style="width:5px">
    </div>

    <!-- radio second 2 -->
    <div class="border rounded bg-white p-2 col-md-4 col-xs-12 form-inline rounded">
        <div class="col-xs-4 checkboxRow50">
            {{ Form::radio('events_filter', 'Scheduled') }} <strong>{{trans('labels.event.scheduled')}}</strong>
        </div>

        <div class="col-xs-4 checkboxRow50">
            {{ Form::radio('events_filter','Available') }} <strong>{{trans('labels.event.available')}}</strong>
        </div>
    </div>

</div>

<!-- search box / apply / reset -->
<div class="form-group col-lg-12 form-inline">
    <!-- filter title -->
    <div class="col-lg-1"></div>
    <div class="col-lg-8">
        {{ Form::text('searchRows',null, ['class' => 'form-control', 'style' => 'width:100%','placeholder' => trans('schedule.addClass.search'), 'id' => 'filter' ]) }}
    </div>
    <div class="col-lg-3">
        {{ Form::button(trans('labels.event.apply_filters'), ['class' => 'btn btn-primary', 'onclick' => 'applyRowFilters(event)', 'id' => 'apply_filters', 'type' => 'button']) }}
        {{ Form::button(trans('labels.event.reset_filters'), ['class' => 'btn btn-primary', 'onclick' => 'resetRowFilters(event)', 'id' => 'reset_filters', 'type' => 'button']) }}
    </div>

</div>
