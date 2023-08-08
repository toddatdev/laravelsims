<div class="box-body">
    <div class="form-group">
        <div class="col-md-2 col-lg-2 pull-left">
            {{ Form::label('question', trans('labels.user-profile-questions.question'), ['class' => 'col-lg-2 control-label']) }}
        </div>
        <div class="col-md-6 col-lg-6">
            {{ Form::input('question', 'question', null, ['class' => 'form-control', 'placeholder' => trans('labels.user-profile-questions.question')]) }}
        </div><!--col-lg-10-->
    </div><!--form-group-->

    <div class="form-group ansFieldHolder" >

        <div class="col-md-2 col-lg-2 pull-left">
            {{ Form::label('answer', trans('labels.user-profile-questions.answer'), ['class' => 'col-lg-2 control-label']) }}
        </div>
        
        <div class="col-lg-8 col-md-8 col-sm-10 col-xs-12">
            <div class="answersFieldHolder item" item-id="1" item-name="" parent-id="0" checkValue="0" >
                <a data-parent-level="0" data-level-path="0" data-current-level="0" class="addLevel pull-left" href="#"><i
                        class="fa fa-plus-circle" aria-hidden="true" title="Add Answer"></i></a>
                <a class="pull-left disable" href="#"><i class="fa fa-minus-circle" aria-hidden="true" title="Remove Answer"></i></a>
                <a class="addSubLevel sub-Level pull-left" href="#"><i class="fa fa-level-down" aria-hidden="true" title="Add Child Answer"></i></a>
                <input class="pull-left qAnsField" type="text" value="" name="" />
                <input class="commentNeeded pull-left" type="checkbox" name="has_comment" title="Comment Needed"/><label for="has_comment" class="chkbox">Requires text field</label>
                
                <div class="clearfix clear"></div>
            </div>
        </div>
    </div><!--form-group-->
</div><!-- /.box-body -->



