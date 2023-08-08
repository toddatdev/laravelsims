{{-- Dependencies --}}
{{-- ** Order of Scripts is Important ** --}}
    {{-- jQuery --}}
    {{ Html::script("/js/jquery.js") }}

    {{-- TINY MCE V5 --}}
    {{ Html::script("/js/tinymce/tinymce.min.js") }}

    {{-- TINY MCE V5 Varibles Plugin --}}
    {{ Html::script("/js/tinymce/plugins/variables/plugin.min.js") }}

    {{-- Assigning Varible to let our script know which setting to return --}}
    <script type="text/javascript">
        let level = 3; // EVENT
    </script>

    {{-- Build our DOM with TinyMCE's --}}
    <script type="text/javascript" src="{{ asset('/js/tinymce_emails.js') }}"></script>

{{-- CREATE HTML BELOW --}}
   
<div class="box-body">
    {{-- ID --}}
    @if(isset($eventEmail))
        {!! Form::hidden('id', $eventEmail->id) !!}
    @endif

    <!-- Label -->
    <div class="form-group row">
        {!! Form::label('label', trans('labels.siteEmails.label'), ['class' => 'col-lg-2 control-label required text-lg-right']) !!}
        <div class="col-lg-10">
            @if(session('clonedEmail'))
                {!! Form::input('label', 'label', session('clonedEmail')->label .' [copy]', ['class' => 'form-control', 'placeholder' => trans('labels.siteEmails.label')]) !!}
            @else
                {!! Form::input('label', 'label', null, ['class' => 'form-control', 'placeholder' => trans('labels.siteEmails.label')]) !!}
            @endif
        </div>      
    </div>

    <!-- Email Type -->
    <div class="form-group row">
        {!! Form::label('email_type_id', trans('labels.siteEmails.email_type'), ['class' => 'col-lg-2 control-label required text-lg-right']) !!}
        <div class="col-lg-4">
            @if(@isset($eventEmail))
                {!! Form::select('email_type_id', $email_types, $eventEmail->email_type_id, ['class' => 'form-control', 'placeholder' => trans('labels.general.select'), 'id' => 'email_type_id']) !!}
            @else
                @if(session('clonedEmail'))
                    {!! Form::select('email_type_id', session('email_types'), session('clonedEmail')->email_type_id, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) !!}
                @else
                    {!! Form::select('email_type_id', $email_types, null, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) !!}
                @endif
            @endif
        </div>
        {{-- Warning Message if Type if an "Action" Email Type --}}
        <div class="col-lg-4" style="display:none;" id="email-type-flag-warn"></div>
    </div>
    
    <!-- Email Subject -->
    <div class="form-group row">
        {!! Form::label('subject', trans('labels.siteEmails.subject'), ['class' => 'col-lg-2 control-label required text-lg-right']) !!}
        <div class="col-lg-10">
            @if(session('clonedEmail'))
                {!! Form::input('subject', 'subject', session('clonedEmail')->subject, ['id' => 'subject', 'class' => 'form-control', 'placeholder' => trans('labels.siteEmails.subject')]) !!}
            @else
                {!! Form::input('subject', 'subject', null, ['id' => 'subject', 'class' => 'form-control', 'placeholder' => trans('labels.siteEmails.subject')]) !!}
            @endif
        </div>
    </div>


    <!-- Email Body -->
    <div class="form-group row">
        {!! Form::label('body', trans('labels.siteEmails.body'), ['class' => 'col-lg-2 control-label required text-lg-right']) !!}
        <div class="col-lg-10">
            @if(session('clonedEmail'))
                {{ Form::textarea('body', session('clonedEmail')->body, ['class' => 'form-control']) }}
            @else
                {{ Form::textarea('body', null, ['class' => 'form-control']) }}
            @endif
        </div>
    </div>

    {{-- Info Message box --}}
    <div class="form-group">
        <label class="col-lg-2 control-label"></label>
        <div class="col-lg-10" style="display:none;" id="email-type-msg-box"></div>
    </div>

    <!-- TO -->
    <div class="form-group row">
        {{ Form::label('to_roles', trans('labels.siteEmails.to'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
        <div class="col-lg-3"> 
            @if(isset($eventEmail))
                {{ Form::select('to_roles[]', $permissions_types, $toRolesArrSelected, ['class' => 'form-control to-roles', 'multiple']) }}
            @else
                @if(session('clonedEmail'))                
                    {{ Form::select('to_roles[]', $permissions_types, session('toRolesArrSelected'), ['class' => 'form-control to-roles', 'multiple']) }} 
                @else
                    {{ Form::select('to_roles[]', $permissions_types, null, ['class' => 'form-control to-roles', 'multiple']) }}
                @endif
            @endif
        </div>
        <div class="col-lg-2">
            {{ Form::label('to_other', trans('labels.siteEmails.other_to'), ['class' => 'control-label']) }}
        </div>
        <div class="col-lg-5">    
            @if(session('clonedEmail'))                
                {{ Form::input('to_other', 'to_other', session('clonedEmail')->to_other, ['class' => 'form-control']) }}    
            @else
                {{ Form::input('to_other', 'to_other', null, ['class' => 'form-control']) }}
            @endif
        </div>
    </div>


    <!-- CC -->
    <div class="form-group row">
        {{ Form::label('cc_roles', trans('labels.siteEmails.cc'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
        <div class="col-lg-3"> 
            @if(isset($eventEmail))
                {{ Form::select('cc_roles[]', $permissions_types, $ccRolesArrSelected, ['class' => 'form-control', 'multiple']) }}
            @else
                @if(session('clonedEmail'))                
                    {{ Form::select('cc_roles[]', $permissions_types, session('ccRolesArrSelected'), ['class' => 'form-control', 'multiple']) }} 
                @else
                    {{ Form::select('cc_roles[]', $permissions_types, null, ['class' => 'form-control', 'multiple']) }}
                @endif
            @endif
        </div>
        <div class="col-lg-2">
            {{ Form::label('cc_other', trans('labels.siteEmails.other_cc'), ['class' => 'control-label']) }}
        </div>
        <div class="col-lg-5">    
            @if(session('clonedEmail'))                
                {{ Form::input('cc_other', 'cc_other', session('clonedEmail')->cc_other, ['class' => 'form-control']) }}    
            @else
                {{ Form::input('cc_other', 'cc_other', null, ['class' => 'form-control']) }}
            @endif 
        </div>
    </div>

    <!-- BCC -->
    <div class="form-group row">
        {{ Form::label('bcc_roles', trans('labels.siteEmails.bcc'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
        <div class="col-lg-3"> 
            @if(isset($eventEmail))
                {{ Form::select('bcc_roles[]', $permissions_types, $bccRolesArrSelected, ['class' => 'form-control', 'multiple']) }}
            @else
                @if(session('clonedEmail'))                
                    {{ Form::select('bcc_roles[]', $permissions_types, session('bccRolesArrSelected'), ['class' => 'form-control', 'multiple']) }} 
                @else
                    {{ Form::select('bcc_roles[]', $permissions_types, null, ['class' => 'form-control', 'multiple']) }}
                @endif
            @endif
        </div>
        <div class="col-lg-2">
            {{ Form::label('bcc_other', trans('labels.siteEmails.other_bcc'), ['class' => 'control-label']) }}
        </div>
        <div class="col-lg-5">    
            @if(session('clonedEmail'))                
                {{ Form::input('bcc_other', 'bcc_other', session('clonedEmail')->bcc_other, ['class' => 'form-control']) }}    
            @else
                {{ Form::input('bcc_other', 'bcc_other', null, ['class' => 'form-control']) }}
            @endif  
        </div>
    </div>

    <!-- Email Timing -->
    <div class="form-group row">
        {{ Form::label('when', trans('labels.eventEmails.automatically_send'), ['class' => 'col-lg-4 control-label text-lg-right']) }}
        <div class="col-lg-2">
                @if(isset($eventEmail))
                {{ Form::number('time_amount', $eventEmail->time_amount, ['class' => 'form-control', 'placeholder' => trans('labels.eventEmails.time'), 'min' => '0']) }}
            @else
                @if(session('clonedEmail'))
                    {{ Form::number('time_amount', session('clonedEmail')->time_amount, ['class' => 'form-control', 'placeholder' => trans('labels.eventEmails.time'), 'min' => '0']) }}
                @else
                    {{ Form::number('time_amount', '', ['class' => 'form-control', 'placeholder' => trans('labels.eventEmails.time'), 'min' => '0']) }}
                @endif
            @endif
        </div>
        <div class="col-lg-2">
            @if(session('clonedEmail'))
                {{ Form::select('time_type', [null => trans('labels.general.select'), '1' => 'Minutes', '2' => 'Hours', '3' => 'Days'], session('clonedEmail')->time_type, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) }}  
            @else
                {{ Form::select('time_type', [null => trans('labels.general.select'), '1' => 'Minutes', '2' => 'Hours', '3' => 'Days'], null, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) }}
            @endif
        </div>
        <div class="col-lg-2">
            @if(session('clonedEmail'))
                {{ Form::select('time_offset', [null => trans('labels.general.select'), '1' => 'Before Start Time', '2' => 'After Start Time', '3'=> 'Before End Time', '4' => 'After End Time'], session('clonedEmail')->time_offset, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) }}                
            @else
                {{ Form::select('time_offset', [null => trans('labels.general.select'), '1' => 'Before Start Time', '2' => 'After Start Time', '3'=> 'Before End Time', '4' => 'After End Time'], null, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) }}
            @endif
        </div>
    </div>


    <!-- When the event ... -->
    <div class="form-group row">
        {{ Form::label('limit', trans('labels.eventEmails.when_num_people'), ['class' => 'col-lg-4 control-label text-lg-right']) }}
        <div class="col-lg-2">
            @if(session('clonedEmail'))
                {{ Form::select('role_id', session('eventRoles'), session('clonedEmail')->role_id, ['class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
            @else
                {{ Form::select('role_id', $eventRoles, null, ['class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
            @endif
        </div>

        <div class="col-lg-2">
            {{ Form::label('', 'role is', ['class' => 'col-lg control-label'])}}
        </div>

        <div class="col-lg-2">
            @if(session('clonedEmail'))
                {{ Form::select('role_offset', [null => trans('labels.general.select'), '1' => 'Less Than', '2' => 'Greater Than'], session('clonedEmail')->role_offset, ['class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
            @else
                {{ Form::select('role_offset', [null => trans('labels.general.select'), '1' => 'Less Than', '2' => 'Greater Than'], null, ['class' => 'form-control course-select', 'placeholder' => trans('labels.general.select')]) }}
            @endif
        </div>

        <div class="col-lg-2">
                @if(isset($eventEmail))
                {{ Form::number('role_amount', $eventEmail->role_amount, ['class' => 'form-control', 'placeholder' => trans('labels.eventEmails.amount'), 'min' => '0']) }}
            @else
                @if(session('clonedEmail'))
                    {{ Form::number('role_amount', session('clonedEmail')->role_amount, ['class' => 'form-control', 'placeholder' => trans('labels.eventEmails.amount'), 'min' => '0']) }}
                @else
                    {{ Form::number('role_amount', '', ['class' => 'form-control', 'placeholder' => trans('labels.eventEmails.amount'), 'min' => '0']) }}
                @endif
            @endif
        </div>
    </div>
    {{ Form::hidden('event_id', $event_id) }}

</div>


<!-- Btn's -->
<div class="box box-success">
    <div class="box-body">
        <div class="float-left">
            {{ link_to_route('event_dashboard', trans('buttons.general.cancel'), [$event_id, 'email'], ['class' => 'btn btn-warning btn-md']) }}
        </div>

        <div class="float-right">
            {{ Form::submit($submitButton, ['class' => 'btn btn-success btn-md', 'id' => 'event-email-btn']) }}
        </div>

        <div class="clearfix"></div>
    </div>
</div>

@if (isset($eventEmail->course_email_id))
    <div class="text-center">{{ trans('strings.frontend.email.based_upon')}} <span style="font-weight:bold;">{!! App\Models\Course\CourseEmails::find($eventEmail->course_email_id)->label !!}</span> {{ trans('strings.frontend.email.course_email_template')}}
    @if ($eventEmail->edited > 0)
      <span style="color:red">{{ trans('strings.frontend.email.but_edited') }}</span>
    @endif
    .</div>
@else
    <div class="text-center"> {{ trans('strings.frontend.email.not_based_course_template')}}</div>
@endif
