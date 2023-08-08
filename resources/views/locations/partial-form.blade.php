 <div class="form-group row">
    {{ Form::label('building', trans('labels.locations.building'), ['class' => 'col-lg-2 control-label required text-lg-right']) }}
    <div class="col-lg-2">
        {{ Form::select('building_id', $buildings, null, ['class' => 'form-control']) }}
   </div><!--col-lg-10-->
</div><!--form-group-->

<div class="form-group row" >
    {{ Form::label('name', trans('labels.locations.name'), ['class' => 'col-lg-2 control-label required text-lg-right']) }}
    <div class="col-lg-10">
        {{ Form::input('name', 'name', null, ['class' => 'form-control', 'placeholder' => trans('labels.locations.name')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

<div class="form-group row">
    {{ Form::label('abbrv', trans('labels.locations.abbrv'), ['class' => 'col-lg-2 control-label required text-lg-right']) }}
    <div class="col-lg-10">
        {{ Form::input('abbrv', 'abbrv', null, ['class' => 'form-control', 'placeholder' => trans('labels.locations.abbrv')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

<div class="form-group row">
    {{ Form::label('more_info', trans('labels.locations.more_info'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
    <div class="col-lg-10">
        {{ Form::textarea('more_info', null, ['class' => 'form-control', 'placeholder' => trans('labels.locations.more_info')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

 <div class="form-group row">
    {{ Form::label('directions_url', trans('labels.locations.directions_url'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
    <div class="col-lg-10">
        {{ Form::input('directions_url', 'directions_url', null, ['class' => 'form-control', 'placeholder' => trans('labels.locations.directions_url')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

 <div class="form-group row">
    {{ Form::label('html_color', trans('labels.general.color'), ['class' => 'col-lg-2 control-label text-lg-right']) }}
    <div class="col-lg-10">
        {{ Form::input('html_color', 'html_color', null, ['class' => 'spectrum_html_color', ]) }}
        {{-- <input type='text' name='html_color' class='spectrum_html_color' value="{{ $location->html_color }}"/> --}}
        <span id='spectrum-text' class='palette-label'></span>
    </div><!--col-lg-10-->
</div><!--form-group-->

 <div class="form-group row">
    {{ Form::label('display_order', trans('labels.locations.display_order'), ['class' => 'col-lg-2 control-label required text-lg-right']) }}
    <div class="col-lg-2">
        {{ Form::input('display_order', 'display_order', null, ['class' => 'form-control', 'placeholder' => trans('labels.locations.display_order')]) }}
    </div><!--col-lg-10-->
</div><!--form-group-->

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




