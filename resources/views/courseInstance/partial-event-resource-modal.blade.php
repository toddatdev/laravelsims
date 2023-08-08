<div class="modal fade add-event-modal" tabindex="-1" role="dialog" id="resourceModal">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-add-event-modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Resource: <span id="resourceEditResourceName">&nbsp;</span></h4>
            </div>
            
            <div class="modal-body">
                <form name="editResourceForm" action="javascript:void(0);" class="form-horizontal" id="editResourceForm">

                    <div class="form-group">
                        <label for="{{ trans('schedule.addClass.start') }}" class="col-lg-4 col-md-4 control-label required">{{ trans('schedule.addClass.start') }} </label>
                        <div class="col-lg-8 col-md-8">
                            {{ Form::text('resourceEditStart',null,["class"=>"form-control", "id"=>"resourceEditStart", "disabled"=>true]) }}
                        </div>
                    </div><!--form-group-->

                    <div class="form-group">
                        <label for="{{ trans('schedule.addClass.end') }}" class="col-lg-4 col-md-4 control-label required">{{ trans('schedule.addClass.end') }} </label>
                        <div class="col-lg-8 col-md-8">
                            {{ Form::text('resourceEditEnd',null,["class"=>"form-control", "id"=>"resourceEditEnd", "disabled"=>true]) }}
                        </div>
                    </div><!--form-group-->

                    <div class="form-group">
                        <label for="{{ trans('schedule.addClass.setup') }}" class="col-lg-4 col-md-4 control-label required">{{ trans('schedule.addClass.setup') }} </label>
                        <div class="col-lg-8 col-md-8">
                            {{ Form::number('resourceEditSetupTime','0',["max"=>"120", "min"=>"0", "class"=>"form-control stepBugFix", "step"=>"15", "id"=>"setupTime", "value"=>"0"]) }}
                        </div>
                    </div><!--form-group-->

                    <div class="form-group">
                        <label for="{{ trans('schedule.addClass.teardown')}}" class="col-lg-4 col-md-4 control-label required">{{ trans('schedule.addClass.teardown')}} </label>
                        <div class="col-lg-8 col-md-8">
                            {{ Form::number('resourceEditTeardownTime','0',["max"=>"120", "min"=>"0", "class"=>"form-control stepBugFix", "step"=>"15", "id"=>"teardownTime"]) }}
                        </div>
                    </div><!--form-group-->

                    <div class="pull-right">
                        <button type="button" class="btn btn-default " onClick="$('#resourceModal').modal('hide');">Close</button>
                        <button type="button" class="btn btn-primary edit-resource">Edit</button>
                    </div><!--pull-right-->

                </form>
            </div>
        </div>
    </div>
</div>