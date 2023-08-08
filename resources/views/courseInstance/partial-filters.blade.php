<!-- css - calendar.css -->
<div class="box-body filter-box col-md-12" id="filterBox">

    <!-- radios -->
    <div class="form-group col-md-12">

        <!-- filter title -->
        <div class="col-md-1 col-xs-12">
                <label class="col-xs-2" style="font-size:12pt"><b>Filter</b></label>
        </div>

        <!-- radio first 3 -->
        <div class="circleRadios col-md-5 col-xs-12">
            <div class="col-xs-4 checkboxRow">
                {{Form::radio('phys_filter','Room')}}  <strong>Rooms</strong>
            </div>

            <div class="col-xs-4 checkboxRow">
                {{Form::radio('phys_filter','Equipment')}} <strong>Equipment</strong>
            </div>

            <div class="col-xs-4 checkboxRow">
                {{Form::radio('phys_filter','Personnel')}} <strong>Personnel</strong>
            </div>
        </div>

        <!-- space -->
        <div class="col-md-1 col-xs-0" style="width:5px">
        </div>

        <!-- radio second 2 -->
        <div class="circleRadios col-md-5 col-xs-12">
            <div class="col-xs-4 checkboxRow">
                {{Form::radio('events_filter', 'Scheduled')}} <strong>Scheduled</strong>
            </div>

            <div class="col-xs-4 checkboxRow">
                {{Form::radio('events_filter','Available')}} <strong>Available</strong>
            </div>

            <div class="col-xs-4 checkboxRow">
                {{Form::radio('events_filter','This_Event')}} <strong>This Event</strong>
            </div>
        </div>

    </div>

    <!-- search box / reset -->
    <div class="form-group col-xs-12">

        <div class="col-lg-10 col-md-10 col-xs-12">
            {{ Form::text('searchRows',null, ['class' => 'form-control', 'placeholder' => trans('schedule.addClass.search'), 'onkeyup' => 'searchRowHandler(event);' ]) }}
        </div>
        <div class="col-lg-2 col-md-2 col-xs-12">
            {{ Form::button('Reset Filters', ['class' => 'btn', 'id' => 'resetFilters']) }}
        </div>

    </div>

    <br clear='both' />

</div><!-- /box-body -->
