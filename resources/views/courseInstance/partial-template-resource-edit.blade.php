<div class="form-group" style="">
    {{ Form::label('select_resource', 'Select resource', ['class' => 'col-lg-3 control-label required']) }}
    <div class="col-lg-4">
        {{ Form::select('select_resource', [], null, ['class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
    </div>
</div>
<div class="form-group" style="">
    <div class="col-lg-4">
        {{ Form::radio('resource_identifier_type', 'resource')}} <strong>Specific <span id="specific-label"></span></strong>
    </div>
    <div class="col-lg-4">
        {{ Form::radio('resource_identifier_type', 'category')}} <strong>Category <span id='category-label'></span></strong>
    </div>
    <div class="col-lg-4">
        {{ Form::radio('resource_identifier_type', 'subcategory')}} <strong>Sub-Category <span id='subcategory-label'></span></strong>
    </div>
</div>
<div class="form-group hidden" id='imr-block'>
    <div class="col-lg-6">
        {{ Form::radio('initial_meeting_room', 0)}} <strong>Initial Meeting Room <span id="imr-label"></span></strong>
    </div>
</div>
<div class="form-group" style="padding-bottom:-10px;">
    <div class="col-lg-12">
        <table class="table margin-form-offset">
        <thead>
            <th>{{ trans('schedule.addClass.setup') }}</th>
            <th>{{ trans('schedule.addClass.start') }}</th>
            <th>{{ trans('schedule.addClass.end')}}</th>
            <th>{{ trans('schedule.addClass.teardown')}}</th>
        </thead>
        <tbody>
            <td>{{ Form::number('resource_setup_time',0,["max"=>"120", "min"=>"0", "class"=>"form-control setup-teardown-vertical", "step"=>"15", "id"=>"setup", "value"=>"0"]) }}</td>

            <td>{{ Form::time('resource_start_time', $defaultStartHour.':00',['class' => 'form-control fieldToplotDP','id' => 'start_times', 'step'=>'900']) }}</td>
            <td>{{ Form::time('resource_end_time', $defaultEndHour.':00',['class' => 'form-control fieldToplotDP','id' => 'endtime_id', 'step'=>'900']) }}  </td>

            <td>{{ Form::number('resource_teardown_time',0,["max"=>"120", "min"=>"0", "class"=>"form-control setup-teardown-vertical", "step"=>"15", "id"=>"teardown"]) }}</td>
        </tbody>
        </table>
    </div>
</div>

@section('after-scripts')
@parent
<script>
const ResourceEditModal = {
    data: {},
    options: false,
    template: false,
    getResourceById: function(id) {
        let ret = {};
        for(let i = 0; i < this.resources.length; ++i) {
            if(this.resources[i].id == id) {
                ret = this.resources[i];
                return ret;
            }
        }
        return ret;
    },
    selectReset: function() {
        $("[name='select_resource']").prop('selectedIndex',0);
    },
    setSelectByValue: function(value) {
        $("select[name='select_resource']  option[value='"+value+"']").prop('selected', true);
        $("select[name='select_resource']").trigger('change');
    },
    setSelectByIndex: function(idx) {
        $("select[name='resource_select']").prop('selectedIndex', idx);
        $("select[name='select_resource']").trigger('change');
    },
    setSelectByText: function(value) {
        $("select[name='resource_select'] option").filter(function() {
            return $(this).text() == value;
        }).prop('selected', true);
        $("select[name='resource_select']").trigger('change');
    },
    setSelectDisable: function() {
        $("select[name='select_resource']").prop('disabled', true);
    },
    setSelectEnable: function() {
        $("select[name='select_resource']").prop('disabled', false);
    },
    initResourceEditModal: function(callback, options) {
        this.options = options || false;
        var _self = this;
        if(this.options) {
            this.idx = options.idx;
            if(this.options.hasOwnProperty('event')) {
                this.template = event;
            }
            if(this.options.hasOwnProperty('type')) {
                this.type = this.options.type;
                if(this.type == 'Room') {
                    $('#imr-block').removeClass('hidden');
                } else {
                    $('#imr-block').addClass('hidden');
                }
            }
            if(this.options.hasOwnProperty('resources')) {
                this.resources = this.options.resources;
                $('select[name="select_resource"]').find('option:not(:first)').remove();

                for(let i = 0; i < this.resources.length; ++i) {
                    if(this.resources[i].type.abbrv == this.type)
                        $("[name='select_resource']").append(new Option(this.resources[i].location.building.abbrv+" "+this.resources[i].location.abbrv+" "+this.resources[i].abbrv, this.resources[i].id));
                }
            }
            if(this.options.hasOwnProperty('data')) {
                this.data = this.options.data;
                console.log(this.data);
                $('input[name="resource_setup_time"]').val(this.data.event_resource.setup_time);
                $('input[name="resource_teardown_time"]').val(this.data.event_resource.teardown_time);
                $('[name="resource_start_time"]').val(this.data.event_resource.startTime);
                $('[name="resource_end_time"]').val(this.data.event_resource.endTime);
                $('#specific-label').html("("+this.data.resource.type.abbrv+")");
                $('#category-label').html("("+this.data.resource.category.abbrv+")");
                $('#subcategory-label').html("("+this.data.resource.subcategory.abbrv+")");
                $('input[name="resource_idx"]').val(this.idx);
                $('input[name="resourceType"]').val(this.type);
                this.setSelectByValue(this.data.resource.id);

                $('input[name="initial_meeting_room"]').val(this.data.event_resource.id);
                $('input[name="initial_meeting_room"]').prop('checked', false);

                if(this.template.initial_meeting_room == this.data.event_resource.id) {
                    $('input[name="initial_meeting_room"]').prop('checked', true);
                }

                let radios = $("input[name='resource_identifier_type']");
                for(let i = 0; i < radios.length; ++i) {
                    if($(radios[i]).val() == this.data.event_resource.resource_identifier_type.abbrv) {
                        $(radios[i]).prop('checked', true);
                    }
                }
            }
        }
        $("select[name='select_resource']").change(function(evnt) {
            _self.data.resource = _self.getResourceById(evnt.target.value);
            _self.data.event_resource.resource_id = evnt.target.value;
            $('#specific-label').html("("+_self.data.resource.type.abbrv+")");
            $('#category-label').html("("+_self.data.resource.category.abbrv+")");
            $('#subcategory-label').html("("+_self.data.resource.subcategory.abbrv+")");
        });
        $('.setup-teardown-vertical[name="setup_time"]').on("change", function(evnt){
        });
        $('.setup-teardown-vertical[name="teardown_time"]').on("change", function(evnt){
        });
        $('[name="start_time"]').on("change", function(evnt){
        });
        $('[name="end_time"]').on("change", function(evnt){
        });

        $('#resourceModal').modal('show');
    }
}
</script>
@endsection
