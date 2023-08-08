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
@if(isset($courseEmails))
    {!! Form::hidden('id', $courseEmails->id) !!}
@endif

<!-- Label -->
<div class="form-group row">
    {!! Form::label('label', trans('labels.courseEmails.label'), ['class' => 'col-lg-2 control-label required text-lg-right']) !!}
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
            @if(isset($courseEmails))
            {!! Form::select('email_type_id', $email_types, $courseEmails->email_type_id, ['class' => 'form-control', 'placeholder' => trans('labels.general.select')]) !!}
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
    {!! Form::label('subject', trans('labels.courseEmails.subject'), ['class' => 'col-lg-2 control-label required text-lg-right']) !!}
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
    {!! Form::label('body', trans('labels.courseEmails.body'), ['class' => 'col-lg-2 control-label required text-lg-right']) !!}
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
    {{ Form::label('to_roles', trans('labels.courseEmails.to'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
    <div class="col-lg-3">
        @if(isset($courseEmails))
            {{ Form::select('to_roles[]', $permissions_types, $toRolesArrSelected, ['class' => 'form-control', 'multiple']) }}
        @else
            @if(session('clonedEmail'))
                {{ Form::select('to_roles[]', $permissions_types, session('toRolesArrSelected'), ['class' => 'form-control', 'multiple']) }}
            @else
                {{ Form::select('to_roles[]', $permissions_types, null, ['class' => 'form-control', 'multiple']) }}
            @endif
        @endif
    </div>
    <div class="col-lg-2">
        {{ Form::label('to_other', trans('labels.courseEmails.other_to'), ['class' => 'control-label text-lg-right']) }}
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
    {{ Form::label('cc_roles', trans('labels.courseEmails.cc'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
    <div class="col-lg-3">
        @if(isset($courseEmails))
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
        {{ Form::label('cc_other', trans('labels.courseEmails.other_cc'), ['class' => 'control-label text-lg-right']) }}
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
        @if(isset($courseEmails))
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
        {{ Form::label('bcc_other', trans('labels.siteEmails.other_bcc'), ['class' => 'control-label text-lg-right']) }}
    </div>
    <div class="col-lg-5">
        @if(session('clonedEmail'))
            {{ Form::input('bcc_other', 'bcc_other', session('clonedEmail')->bcc_other, ['class' => 'form-control']) }}
        @else
            {{ Form::input('bcc_other', 'bcc_other', null, ['class' => 'form-control']) }}
        @endif
    </div>
</div>

<input type="hidden" id="course_id" name="course_id">

@if(isset($courseEmails))
    {{ Form::hidden('type', Request::segment(5)) }}
@else
    {{ Form::hidden('type', Request::segment(4)) }}
@endif


@if (isset($courseEmails->site_email_id))
    <div class="text-center">{{ trans('strings.frontend.email.based_upon')}} <span style="font-weight:bold;">{!! App\Models\Site\SiteEmails::find($courseEmails->site_email_id)->label !!}</span> {{ trans('strings.frontend.email.site_email_template')}}
    @if ($courseEmails->edited > 0)
      <span style="color:red">{{ trans('strings.frontend.email.but_edited') }}</span>
    @endif
    </div>
@else
    <div class="text-center"> {{ trans('strings.frontend.email.not_based_site_template')}}</div>
@endif

<script>
    @if(session('clonedEmail'))
        var course_id = {{ session('id') }};
    @else
        var course_id = {{ $id }};
    @endif
    
    if (course_id == localStorage.getItem("course_id")) {
        document.getElementById("course_id").value = localStorage.getItem("course_id") || course_id;        
    }else {
        // redirect, session and local storage don't match
        window.location.replace("/courses/active");
    }
</script>