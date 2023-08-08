@extends('backend.layouts.app')

@section ('title', trans('menus.backend.course.title') . ' | ' . trans('menus.backend.courseOptions.title'))
{{-- include the Spectrum color picker CSS, JavaScript files and JQuery. --}}
<link rel="stylesheet" type="text/css" href="{{ asset('/css/spectrum/spectrum.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/css/spectrum/larasim-spectrum.css') }}">
{{ Html::script("js/jquery.js") }}
<script type="text/javascript" src="{{ asset('/js/spectrum/spectrum.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/spectrum/larasim-spectrum.js') }}"></script>

@section('page-header')
    <h4>
        {{ trans('menus.backend.courseOptions.title') }}
    </h4>
@endsection

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }} </strong>
        </div>
    @endif

    <section class="content">
        {{ Form::open(['url' => route('CourseOption.store'), 'method' => 'post', 'class' => 'form-horizontal']) }}
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">{{ $course->abbrv }}</h3>
                        <div class="float-right">
                            @include('courses.partial-header-buttons-sub')
                        </div>
                    </div>

                    <div class="card-body">

                        @foreach ($courseOptions as $courseOption)

                            {{--get value for this course if exists--}}

                            <?php
                            $existingValue = NULL;
                            $courseOptionValue = \App\Models\Course\CourseOption::
                                where(['option_id' => $courseOption->id,
                                'course_id' => $course->id])
                                ->first();
                            if(isset($courseOptionValue))
                            {
                                $existingValue = $courseOptionValue->value;
                            }
                            ?>

                            <div class="form-group row">
                                {{ Form::label($courseOption->description, trans('labels.courseOptions.' . $courseOption->description ), ['class' => 'col-md-4 control-label text-md-right']) }}

                                <div class="col-md-8">

                                    @if ($courseOption->input_type_id == 1) {{--checkbox--}}

                                        @if($existingValue == '1')
                                            {{ Form::checkbox($courseOption->id, 'checked', true, ['class' => 'mt-10']) }}
                                        @else
                                            {{ Form::checkbox($courseOption->id, 'notchecked', false, ['class' => 'mt-10']) }}
                                        @endif

                                    @elseif ($courseOption->input_type_id == 4) {{--color--}}

                                        @if(empty($existingValue))
                                            {{ Form::input($courseOption->id, $courseOption->id, null, ['class' => 'spectrum_html_color', ]) }}
                                        @else
                                            {{ Form::input($courseOption->id, $courseOption->id, $existingValue, ['class' => 'spectrum_html_color', ]) }}
                                        @endif
                                        {{--Line below shows sample of color they selected--}}
                                        <span id='spectrum-text' class='palette-label'></span>

                                    @elseif ($courseOption->input_type_id == 6) {{--number--}}

                                        {{Form::number($courseOption->id, $existingValue, ['class' => 'form-control', 'placeholder' => '0', 'min' => '0'])}}

                                    @elseif ($courseOption->input_type_id == 9) {{--number 15 min increment--}}

                                        {{ Form::number($courseOption->id, $existingValue,['size'=>3, 'max'=>'120', 'min'=>'0', 'class'=>'form-control minutes-input', 'step'=>'15']) }}

                                    @elseif ($courseOption->input_type_id == 8 || $courseOption->input_type_id == 10) {{--time 15 min increment with before and after--}}
                                        <div class="form-inline">
                                            @if(empty($existingValue))
                                                {{ Form::number($courseOption->id, '',['size'=>3, 'max'=>'120', 'min'=>'0', 'class'=>'form-control minutes-input', 'step'=>'15']) }}
                                                &nbsp;{{ Form::select($courseOption->id . 'BA', ['B' => 'Before', 'A' => 'After'], 'B') }}
                                            @else
                                                @if($existingValue > 0)
                                                    {{ Form::number($courseOption->id, $existingValue,['size'=>3, 'max'=>'120', 'min'=>'0', 'class'=>'form-control minutes-input', 'step'=>'15']) }}
                                                    &nbsp;{{ Form::select($courseOption->id . 'BA', ['B' => 'Before', 'A' => 'After'], 'A') }}
                                                @else
                                                    <?php
                                                        $existingValue = abs($existingValue);
                                                    ?>
                                                    {{ Form::number($courseOption->id, $existingValue,['size'=>3, 'max'=>'120', 'min'=>'0', 'class'=>'form-control minutes-input', 'step'=>'15']) }}
                                                        &nbsp;{{ Form::select($courseOption->id . 'BA', ['B' => 'Before', 'A' => 'After'], 'B') }}
                                                @endif
                                            @endif
                                            @if($courseOption->input_type_id == 8)
                                                &nbsp;Start Time
                                            @else
                                                &nbsp;End Time
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('active_courses', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
                        </div>
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-md']) }}
                        </div>
                    </div>
                    {{ Form::hidden('course_id', $course->id) }}
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </section>

    {{-- include the Spectrum colorpicker code and configuration. --}}
    <script>
        $(".spectrum_html_color").spectrum({

            //Give us the side palette with the squares as well as the full spectrum color picker.
            showPalette: true,

            //put the input box in so we can type in it.
            showInput: true,

            //Allows the input field to be empty, and gives us a big X to clear it.
            allowEmpty: true,

            //Prefer hexidecimal because that is what we started with, allows all other formats though.
            preferredFormat: "rgb",

            //Use the specific palette
            palette : fullPalette,

            //Have a slide to change the RGBA Alpha value (from 0 to 1)
            showAlpha: true,

            //Keep the local storage colors for this object
            localStorageKey : "spectrum.spectrum_html_color",

            //Can't keep more than 24 colors in addition to the standard palette (which is 40) in your localStorage. This is PLENTY!
            maxSelectionSize : 24,

            //Shows the initial color in a box next to the newly selected color for comparison.
            showInitial: true,

            //We will put the trans() call in here for the button text for multiple languages.
            chooseText : "Choose",
            cancelText : "Cancel",

            //If they click outside of the selector, it will not change the value.  If they get confused, they can click out, non-destructively.
            clickoutFiresChange : false,

            //when the color is selected from the Selection Palette, show the user what the background color will look like with some text.
            //Note that this does not work with the Spectrum Palette, you need to hit the "Choose" button to get the change event to fire.
            change: function(the_color) {
                if (the_color != null)
                {
                    $("#spectrum-text").text("This is what using this color [" + the_color.toString() + "] will look like." );
                    document.getElementById("spectrum-text").style.backgroundColor = the_color.toString();
                }
                else
                { //we've cleared the color. Let them know in the text.
                    $("#spectrum-text").text("Color cleared." );
                    document.getElementById("spectrum-text").style.backgroundColor = "";
                }

                $(this).data('changed', true);
            },

            //This gets called when the popup window is closed either by a button press or, with clickoutFiresChange set to false, a click outside of
            //the popup to bail out of the selection.
            hide : function(the_color) {
                if ($(this).data('changed'))
                {
                    if (the_color !== null)
                    {
                        $("#spectrum-text").text("This is what using this color [" + the_color.toString() + "] will look like." );
                        document.getElementById("spectrum-text").style.backgroundColor = the_color.toString();
                    }
                    else
                    {
                        $("#spectrum-text").text("Color cleared." );
                        document.getElementById("spectrum-text").style.backgroundColor = "";
                    }
                }
                else
                {
                    $("#spectrum-text").text("Reset");
                    document.getElementById("spectrum-text").style.backgroundColor = "";
                }
            },

        });
    </script>

@stop