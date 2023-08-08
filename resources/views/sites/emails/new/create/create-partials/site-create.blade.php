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
        let level = 1; // SITE
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
                {!! Form::input('label', 'label', session('clonedEmail')->label .' [Cloned]', ['class' => 'form-control', 'placeholder' => trans('labels.siteEmails.label')]) !!}
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

{{-- Used To redirect back to type user was prev. on --}}
@if(isset($siteEmails))
    {{ Form::hidden('type', Request::segment(6)) }}
@else
    {{ Form::hidden('type', Request::segment(5)) }}
@endif

