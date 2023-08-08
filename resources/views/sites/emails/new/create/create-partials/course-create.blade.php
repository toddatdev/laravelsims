{{-- Dependencies --}}
{{-- ** Order of Scripts is Important ** --}}
    {{-- jQuery --}}
    {{ Html::script("js/jquery.js") }}

    {{-- TINY MCE V5 --}}
    {{ Html::script("/js/tinymce/tinymce.min.js") }}

    {{-- TINY MCE V5 Varibles Plugin --}}
    {{ Html::script("/js/tinymce/plugins/variables/plugin.min.js") }}

    {{-- Assigning Varible to let our script know which setting to return --}}
    <script type="text/javascript">
        let level = 2; // COURSE
    </script>

    {{-- Build our DOM with TinyMCE's --}}
    <script type="text/javascript" src="{{ asset('/js/tinymce_emails.js') }}"></script>    

{{-- CREATE HTML BELOW --}}


    {{-- ID --}}
    @if(isset($siteEmails))
        {!! Form::hidden('id', $siteEmails->id) !!}
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
            @if(@isset($siteEmails))
                {!! Form::select('email_type_id', $email_types, $siteEmails->email_type_id, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) !!}
            @else
                @if(session('clonedEmail'))
                    {!! Form::select('email_type_id', session('email_types'), session('clonedEmail')->email_type_id, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) !!}
                @else
                    {!! Form::select('email_type_id', $email_types, null, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) !!}
                @endif
            @endif
        </div>
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

    <!-- TO -->
    <div class="form-group row">
        {{ Form::label('to_roles', trans('labels.siteEmails.to'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
        <div class="col-lg-3"> 
            @if(isset($siteEmails))
                {{ Form::select('to_roles[]', $permissions_types, $toRolesArrSelected, ['class' => 'form-control', 'multiple']) }}
            @else
                @if(session('clonedEmail'))                
                    {{ Form::select('to_roles[]', $permissions_types, session('toRolesArrSelected'), ['class' => 'form-control', 'multiple']) }} 
                @else
                    {{ Form::select('to_roles[]', $permissions_types, null, ['class' => 'form-control', 'multiple']) }}
                @endif
            @endif
        </div>
        {{ Form::label('to_other', trans('labels.siteEmails.other_to'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
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
            @if(isset($siteEmails))
                {{ Form::select('cc_roles[]', $permissions_types, $ccRolesArrSelected, ['class' => 'form-control', 'multiple']) }}
            @else
                @if(session('clonedEmail'))                
                    {{ Form::select('cc_roles[]', $permissions_types, session('ccRolesArrSelected'), ['class' => 'form-control', 'multiple']) }} 
                @else
                    {{ Form::select('cc_roles[]', $permissions_types, null, ['class' => 'form-control', 'multiple']) }}
                @endif
            @endif
        </div>
        {{ Form::label('cc_other', trans('labels.siteEmails.other_cc'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
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
            @if(isset($siteEmails))
                {{ Form::select('bcc_roles[]', $permissions_types, $bccRolesArrSelected, ['class' => 'form-control', 'multiple']) }}
            @else
                @if(session('clonedEmail'))                
                    {{ Form::select('bcc_roles[]', $permissions_types, session('bccRolesArrSelected'), ['class' => 'form-control', 'multiple']) }} 
                @else
                    {{ Form::select('bcc_roles[]', $permissions_types, null, ['class' => 'form-control', 'multiple']) }}
                @endif
            @endif
        </div>
        {{ Form::label('bcc_other', trans('labels.siteEmails.other_bcc'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
        <div class="col-lg-5">
            @if(session('clonedEmail'))                
                {{ Form::input('bcc_other', 'bcc_other', session('clonedEmail')->bcc_other, ['class' => 'form-control']) }}    
            @else
                {{ Form::input('bcc_other', 'bcc_other', null, ['class' => 'form-control']) }}
            @endif  
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-4 control-label">{{ trans('labels.courseEmails.select_preference') }}:</label>
    </div>

    {{-- Option --}}
    <div class="form-group row">
        {{-- EDIT --}}
        @if(isset($siteEmails))
            {!! Form::label('option_1', trans('labels.courseEmails.apply_unedit_courses') . ' ['. $uneditCount .']', ['class' => 'text-lg-right col-4 control-label']) !!}
            <div class="col-8">
                <div class="row">
                    <div class="col-1">
                        {{ Form::radio('option', '1', false, ['class' => 'form-radio-input']) }}
                    </div>
                    <div class="col-7">
                        {{ Form::label('option_1-msg', trans('labels.courseEmails.apply_unedit_courses_msg'), ['class' => 'checkbox-inline']) }}
                    </div>
                </div>
            </div>

            {!! Form::label('option_2', trans('labels.courseEmails.update_every_instance') . ' ['. $allCount .']', ['class' => 'text-lg-right col-4 control-label']) !!}
            <div class="col-8">
                <div class="row">
                    <div class="col-1">
                        {{ Form::radio('option', '2', false, ['class' => 'form-radio-input']) }}
                    </div>
                    <div class="col-7">
                        {{ Form::label('option_2-msg', trans('labels.courseEmails.update_every_instance_msg'), ['class' => 'checkbox-inline']) }}
                    </div>
                </div>
            </div>

            {!! Form::label('option_3', trans('labels.courseEmails.only_new_courses'), ['class' => 'text-lg-right col-4 control-label']) !!}
            <div class="col-8">
                <div class="row">
                    <div class="col-1">
                        {{ Form::radio('option', '3', false, ['class' => 'form-radio-input']) }}
                    </div>
                    <div class="col-7">
                        {{ Form::label('option_3-msg', trans('labels.courseEmails.only_new_courses_msg'), ['class' => 'checkbox-inline']) }}
                    </div>
                </div>
            </div>

        @else
            {{-- CREATE --}}
            @if(session('clonedEmail'))
                {!! Form::label('option', trans('labels.courseEmails.apply_to_all') . ' ['. session('courseCount') . ']' , ['class' => 'col-3 control-label']) !!}
            @else
                {!! Form::label('option', trans('labels.courseEmails.apply_to_all') . ' ['. $courseCount . ']' , ['class' => 'col-3 control-label']) !!}
            @endif
            <div class="col-9">
                {{ Form::checkbox('option', 1, false, ['class' => 'form-check-input']) }}
                {{ Form::label('option-msg', trans('labels.courseEmails.apply_to_all_msg'), ['class' => 'checkbox-inline']) }}
            </div>
        @endif
    </div>


@if(isset($siteEmails))
    {{ Form::hidden('type', Request::segment(6)) }}
@else
    {{ Form::hidden('type', Request::segment(5)) }}
@endif
