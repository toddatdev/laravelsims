{{-- Script Dependencies --}}

    {{-- DayPilot library --}}
    <script src="{{ asset('js/daypilot/daypilot-all.min.js')}} "></script>   

    {{ Html::script("/js/sweetalert/sweetalert.min.js") }}

{{-- End --}}

{{-- Filters --}}
<div class="panel panel-default">
    <div class="panel-body">
        @include('courseInstance.main.partials.partial-filters')
    </div>
</div>

{{-- Grid --}}
<div id="dp"></div>

{{-- Event Model --}}
@include('courseInstance.main.partials.partial-event-resource-modal')

{{-- //    @if (isset($event_resources))
//     var event_res = {!! json_encode($event_resources->toArray()) !!};
//     Array.prototype.push.apply(arr, event_res); 
//     console.log(arr);
//  @endif --}}

<script> 
    // GRID GLOBALS
    // Day Pilot
    var dp = new DayPilot.Scheduler("dp");

    // Array to Store User Created Events. **important**
    var stored_events = [];
    
    // Declare Date var for Template
    var gridDateForTemplate;
    
    // Need to store the IDs to remove from DB
    var deleteEvArr = [];

    // Locations & Resources
    var locationsAndResources = {!! $locationsAndResources !!};

    // Array of Resources ID's user can add events to
    var resources_allowed = {!! json_encode($editable_resources) !!};

    // For user created events only
    var overlapCounter = 0;

    // For Template events, used only to recalc @overlapCounter if templates are changed
    // gets used in ./partial-form
    var curTemplateOverlaps = 0;

    function _init () {
        var busStart = {{ Session::get('business_start_hour') }};
        var busEnd = {{ Session::get('business_end_hour') }};
        var date;
        var barStart
        var barEnd
        var barSetup
        var barTeardown

        //date is set on edit, duplicate and when adding from calendar day view
        @if (isset($date))
            dp.startDate = '{{ $date }}';
            date = dp.startDate;
            barStart = '{!! \Carbon\Carbon::parse($startTime)->format("H:i:s") !!}'
            barEnd = '{!! \Carbon\Carbon::parse($endTime)->format("H:i:s") !!}'
            barSetup = '{!! \Carbon\Carbon::parse($startTime)->subMinutes($setupTime)->format("H:i:s") !!}'
            barTeardown = '{!! \Carbon\Carbon::parse($endTime)->addMinutes($teardownTime)->format("H:i:s") !!}'
        @else //this only happens on create when no date passed
            date = dp.startDate.value;
            barStart = busStart < 10 ? '0' + busStart.toString() + ':00:00' : busStart.toString() + ':00:00';
            barEnd = busEnd < 10 ? '0' + busEnd.toString() + ':00:00' : busEnd.toString() + ':00:00';
            barSetup = '{!! \Carbon\Carbon::parse($startTime)->subMinutes($setupTime)->format("H:i:s") !!}'
            barTeardown = '{!! \Carbon\Carbon::parse($endTime)->addMinutes($teardownTime)->format("H:i:s") !!}'
        @endif

        dp.treeEnabled = true;
        dp.treePreventParentUsage = true;     
        dp.businessBeginsHour = busStart;
        dp.businessEndsHour = busEnd;
        dp.crosshairType = "Full";
        dp.autoScroll = "Disabled";
        dp.scrollX = 550; // Set view focus for times. By Default focus is from far left to right
        dp.timeHeaders =  [{groupBy: "Day", format: "dddd MMMM d, yyyy" }, {groupBy: "Hour"}];
        dp.scale = "CellDuration";
        dp.cellDuration = 15;
        dp.cellWidthSpec = 'Auto';
        dp.cellWidthMin = 20;
        dp.eventStackingLineHeight = 50;
        dp.eventArrangement = "SideBySide";
        dp.allowEventOverlap = true;
        dp.resources = locationsAndResources;
        dp.allowEventOverlap = false;
        dp.floatingEvents = false;

        // Setting vert lines to default Bus. Hours
        
        // use date for ajax
        // let date = dp.startDate.value // this is set by Day Pilot by default, we don't need to include the attrb but we can still use it.
        // format
        date = date.slice(0, 10);

        //note, the blue setup and teardown lines are first so that if the setup or teardown is zero, the red line is on top and blue does not appear
        dp.separators = [{color:"blue", location: date + "T" + barSetup}, {color: "blue", location: date + "T" + barTeardown}, {color:"red", location: date + "T" + barStart}, {color: "red", location: date + "T" + barEnd}];

        // assign grid date for template
        gridDateForTemplate = date;

        // Load the init day events
        //get html page name to determine if this is from template apply
        var href = document.location.href;
        var lastPathSegment = href.substr(href.lastIndexOf('/') + 1);
        var templateApply = false; //(mitcks) did this come from the templateApply view?
        if (lastPathSegment == 'templateApply') {
            templateApply = true;
        }
        // console.log('templateapply:' + templateApply); //mitcks

        var templateApplySelectedResources = 'eventOnly'; //(mitcks) set to this by default in case not from templateApply page
        if(templateApply) {

            @isset($resources)
                @if($resources == 'template_resources')
                    templateApplySelectedResources = 'templateOnly';
                @elseif($resources == 'event_resources')
                    templateApplySelectedResources = 'existingOnly';
                @elseif($resources == 'merge_resources')
                    templateApplySelectedResources = 'templateAndEvent';
                @endif
                //console.log('templateApplySelectedResources:'+templateApplySelectedResources);
            @endisset

        }

        //these are used below to pass parameters into the ajax call below that gets existing events
        var strParameters = "/{{$initialMeetingRoom}}";
        @isset($event)
            strParameters += "/{{$event->id}}";
            @else
        @endisset

        $.get("/courseInstance/getEventsAndForDate/"+date+"/"+templateApplySelectedResources+strParameters, function (res) {

            // console.log('templateApplySelectedResources: '+ templateApplySelectedResources);
            // console.log('strParameters: '+ strParameters);
            // console.log('!= templateOnly Loading events for ' + date + ' ' + strParameters);

            //This function loads the existing events for a date, including the event being edited
            dp.update({events: addEventSetupAndTeardownTimes(res)});

            //(mitcks) this seems to only be called when the user has chosen to duplicate an event
            @if (isset($event_resources))
                loadDuplicates()
            @endif

        }).then(function() {
                applyRowFilters()
        });

        // template Day Pilot
        dp.init();
    }
    _init();


    /**
     * Get Events For New Date
    */
    // Get Slider Values
    var slider_values = $("#slider-range").slider("option", "values");

    // store template 
    var $template = $( "#template_id" );

    $('#single_date').on('change', function () {

        //console.log('date has changed');

        $("input[name='selectDate']").val(moment($(this).val(), 'MM-DD-YYYY').format('Y-MM-DD'));

        let date = moment($(this).val(), 'MM-DD-YYYY').format('Y-MM-DD');

        // assign grid date for template
        gridDateForTemplate = date;

        // Store last array of dp.events.list before we destroy that when switch days used in @editEventChange()
        var lastDpList = dp.events.list;
        $.ajax({
            url: "/courseInstance/getEventsAndForDate/".concat(date),
            type: "GET",
            data : {"_token":"{{ csrf_token() }}"},
            dataType: "json",
            async: false, // need to disabled to handle changes user created events dates
            success: function (res) {
                // console.log('Loading events for ' + date);
                // Update Sliders for New Grid Date
                var hours1 = Math.floor(slider_values[0] / 60);
                var minutes1 = slider_values[0] - (hours1 * 60);
                hours1 = hours1 < 10 ? '0' + hours1.toString() : hours1.toString();
                minutes1 = minutes1 === 0 ? '0' + minutes1.toString() : minutes1.toString();       
                var line1 = date + 'T' + hours1 + ':' +  minutes1 + ':00';            

                var hours2 = Math.floor(slider_values[1] / 60);
                var minutes2 = slider_values[1] - (hours2 * 60);
                hours2 = hours2 < 10 ? '0' + hours2.toString() : hours2.toString();
                minutes2 = minutes2 === 0 ? '0' + minutes2.toString() : minutes2.toString();
                var line2 = date + 'T' + hours2 + ':' +  minutes2 + ':00';

                //convert setup/teardown minutes to seconds so it can be subtracted/added below with moment (note: capital HH is 24 hour time format)
                var setupSeconds = $("#setup_time").val()*60;
                var setupLine = moment(hours1 + ':' +  minutes1 + ':00', "HH:mm:ss").subtract(setupSeconds, 'seconds').format("HH:mm:ss");

                var teardownSeconds = $("#teardown_time").val()*60;
                var teardownLine = moment(hours2 + ':' +  minutes2 + ':00', "HH:mm:ss").add(teardownSeconds, 'seconds').format("HH:mm:ss");

                dp.update({events: addEventSetupAndTeardownTimes(res), startDate: date, separators: [{color:"blue", location: date + "T" + setupLine}, {color:"blue", location: date + "T" + teardownLine}, {color: 'red', location: line1}, {color: 'red', location: line2}]});
                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);   
            },
            complete: function () {
                dateChange(date, stored_events);
                editEventChange(date, lastDpList);

                // Reset conflict counter when on new date
                overlapCounter = 0;
                for (let i = 0; i < stored_events.length; i++) {
                    checkUserConflicts(stored_events[i], false);                                   
                }           
            }
        });
        // apply template to new date, if template has been selected
        if ($template.val()) {

            // console.log('I changed data and there was a template');

            //trigger template onchange to reload template events for new date
            $template.trigger("change");

            // mitcks 2020-09-18 commenting this out, it is no longer needed because looking for alternatives when conflict is taken care of in controller

            // var grid_events = dp.events.list.filter(function(obj) {
            //     return obj.template_event != true;
            // });
            //
            // var template_resources = dp.events.list.filter(function(obj) {
            //     return obj.template_event == true;
            // });

            {{--for (var i = 0; i < template_resources.length; i++) {--}}
            {{--    // Get the matching resources, checking the templete resources it was moved tpye if type 2 || 3. Could switch to find the og.--}}
            {{--    var matchingResource = grid_events.filter(function(obj) {--}}
            {{--        return obj.resource == template_resources[i].resource;--}}
            {{--    });--}}
            {{--    var e = template_resources[i];--}}
            {{--    --}}
            {{--    if (matchingResource.length > 0) {--}}
            {{--        for (var j = 0; j < matchingResource.length; j++) {--}}
            {{--            var main_overlap = DayPilot.Util.overlaps(matchingResource[j].start, matchingResource[j].end, e.start, e.end);--}}

            {{--            if (main_overlap) {--}}
            {{--                                            --}}
            {{--                e.backColor = 'rgb(219, 92, 92)'; //red--}}
            {{--                dp.update();--}}
            {{--                // break;--}}

            {{--                if (e.resource_identifier_type === 2) {--}}
            {{--                    $.ajax({--}}
            {{--                        url: "/courseInstance/getResourceByCategoryId/".concat(matchingResource[j].resource, "/").concat(matchingResource[j].resource_category_id, "/").concat(matchingResource[j].location_id),--}}
            {{--                        type: "GET",--}}
            {{--                        data : {"_token": "{{ csrf_token() }}"},--}}
            {{--                        dataType: "json",--}}
            {{--                        async: false,--}}
            {{--                        success: function (res) {--}}
            {{--                            var category_matches = res.resourceList;--}}

            {{--                            for (let x = 0; x < category_matches.length; x++) {--}}

            {{--                                let id = category_matches[x].id.toString();--}}
            {{--                                let category_matches_events = dp.rows.find(id).events.all();--}}

            {{--                                if (category_matches_events.length > 0) {--}}

            {{--                                    for (let y = 0; y < category_matches_events.length; y++) {--}}
            {{--                                        let category_overlap = DayPilot.Util.overlaps(category_matches_events[y].data.start, category_matches_events[y].data.end, e.start, e.end);--}}
            {{--                                        if (category_overlap) {--}}
            {{--                                            break;--}}
            {{--                                        }else {--}}
            {{--                                            if (y == category_matches_events.length-1) {--}}
            {{--                                                e.resource = id;--}}
            {{--                                                e.backColor = 'rgb(97, 235, 52)'; //green--}}
            {{--                                                dp.update();--}}
            {{--                                            }--}}
            {{--                                        }--}}
            {{--                                    }                                                                --}}
            {{--                                }else {--}}
            {{--                                    e.resource = id;--}}
            {{--                                    e.backColor = 'rgb(97, 235, 52)'; //green--}}
            {{--                                    dp.update();--}}
            {{--                                    break;--}}
            {{--                                }                                            --}}
            {{--                            }--}}
            {{--                        },--}}
            {{--                        error: function(jqXHR, textStatus, errorThrown) {--}}
            {{--                            console.log(textStatus, errorThrown);--}}
            {{--                        }--}}
            {{--                    });                                --}}
            {{--                }--}}
            {{--                else if (e.resource_identifier_type === 3) {--}}
            {{--                    $.ajax({--}}
            {{--                        url: "/courseInstance/getResourceBySubCategoryId/".concat(matchingResource[j].resource, "/").concat(matchingResource[j].resource_sub_category_id, "/").concat(matchingResource[j].location_id),--}}
            {{--                        type: "GET",--}}
            {{--                        data : {"_token": "{{ csrf_token() }}"},--}}
            {{--                        dataType: "json",--}}
            {{--                        async: false,--}}
            {{--                        success: function(res) {--}}

            {{--                            var subcategory_matches = res.resourceList;--}}

            {{--                            for (let x = 0; x < subcategory_matches.length; x++) {--}}
            {{--                                --}}
            {{--                                let id = subcategory_matches[x].id.toString();--}}
            {{--                                let subcategory_matches_events = dp.rows.find(id).events.all();--}}

            {{--                                if (subcategory_matches_events.length > 0) {--}}

            {{--                                    for (let y = 0; y < subcategory_matches_events.length; y++) {--}}
            {{--                                        let subcategory_overlap = DayPilot.Util.overlaps(subcategory_matches_events[y].data.start, subcategory_matches_events[y].data.end, e.start, e.end);--}}
            {{--                                        if (subcategory_overlap) {--}}
            {{--                                            break;--}}
            {{--                                        }else {--}}
            {{--                                            if (y == subcategory_matches_events.length-1) {--}}
            {{--                                                e.resource = id;--}}
            {{--                                                e.backColor = 'rgb(97, 235, 52)'; //green--}}
            {{--                                                dp.update();--}}
            {{--                                            }--}}
            {{--                                        }                                                                    --}}
            {{--                                    }                                                                --}}
            {{--                                }else {--}}
            {{--                                    e.resource = id;--}}
            {{--                                    e.backColor = 'rgb(97, 235, 52)'; //green--}}
            {{--                                    dp.update();--}}
            {{--                                    break;--}}
            {{--                                }                                                            --}}
            {{--                            }--}}
            {{--                        },--}}
            {{--                        error: function(jqXHR, textStatus, errorThrown) {--}}
            {{--                            console.log(textStatus, errorThrown);--}}
            {{--                        }--}}
            {{--                    });                                --}}
            {{--                }--}}
            {{--            }else {--}}
            {{--                // ...--}}
            {{--            }                 --}}
            {{--        }   --}}
            //     }else {
            //         e.backColor = 'rgb(92, 219, 92)'; //green
            //         dp.update()
            //     }
            // }
        }
    });

    /*
     * Add new Event to Grid. Use Selected Course name, and current slider values
    */
    dp.onTimeRangeSelected = function (args) {       
        addEvent(args)
    };

    // (mitcks) Update Grid Seperator Bars on Change of Setup OR Teardown Times
    $('#setup_time').add('#teardown_time').on('change', function () {

        var gridDate = moment($('#selectDate').val()).format("Y-MM-DD");

        var hours1 = Math.floor(slider_values[0] / 60);
        var minutes1 = slider_values[0] - (hours1 * 60);
        hours1 = hours1 < 10 ? '0' + hours1.toString() : hours1.toString();
        minutes1 = minutes1 === 0 ? '0' + minutes1.toString() : minutes1.toString();
        var line1 = gridDate + 'T' + hours1 + ':' +  minutes1 + ':00';

        var hours2 = Math.floor(slider_values[1] / 60);
        var minutes2 = slider_values[1] - (hours2 * 60);
        hours2 = hours2 < 10 ? '0' + hours2.toString() : hours2.toString();
        minutes2 = minutes2 === 0 ? '0' + minutes2.toString() : minutes2.toString();
        var line2 = gridDate + 'T' + hours2 + ':' +  minutes2 + ':00';

        //convert setup/teardown minutes to seconds so it can be subtracted/added below with moment (note: capital HH is 24 hour time format)
        var setupSeconds = $("#setup_time").val()*60;
        var setupLine = moment(hours1 + ':' +  minutes1 + ':00', "HH:mm:ss").subtract(setupSeconds, 'seconds').format("HH:mm:ss");

        var teardownSeconds = $("#teardown_time").val()*60;
        var teardownLine = moment(hours2 + ':' +  minutes2 + ':00', "HH:mm:ss").add(teardownSeconds, 'seconds').format("HH:mm:ss");

        dp.update({separators: [{color:"blue", location: gridDate + "T" + setupLine}, {color:"blue", location: gridDate + "T" + teardownLine}, {color: 'red', location: line1}, {color: 'red', location: line2}]});

    });

    // Adds to grid when dropdown is selected
    $('#initial_meeting_room').on('change', function() {

        if (stored_events.length > 0) {
            var imrToMove = stored_events.filter(function(obj) {
                if (obj.flag && obj.isIMR == 1) {                    
                    newIMR = $('#initial_meeting_room').val();
                    obj.resource = newIMR.toString();

                    // get all the events in this "new" moved resource
                    resources = dp.rows.find(obj.resource).events.all();

                    // temp store the overlapCounter
                    let tempOLap = overlapCounter;

                    // loop to check conflicts
                    if (resources.length > 0) {
                        for (let i = 0; i < resources.length; i++) {
                            let overlap = DayPilot.Util.overlaps(resources[i].data.start, resources[i].data.end, obj.start, obj.end);
                            if (overlap) {
                                obj.backColor = 'rgb(219, 92, 92)'; //red
                                tempOLap++;
                                break;
                            }else {
                                if (i == resources.length -1) {
                                    obj.backColor = 'rgb(0,255,0)'; //green
                                    if (overlapCounter > 0) {
                                        overlapCounter--;
                                        tempOLap--;
                                    }
                                }
                            }                            
                        }
                    }else {
                        obj.backColor = 'rgb(0,255,0)'; //green
                        overlapCounter--;
                        tempOLap--;
                    }
                    overlapCounter = tempOLap - overlapCounter;
                    dp.update()
                    return obj;
                }
            });
            
            // IMR was the first thing set so we need to add it.
            if (imrToMove.length == 0) {
                addEvent($(this).val());                
            }
            
            
        }else {
            // add
            addEvent($(this).val());
        }
        
        
    });


    // Need this to save what event ID to edit later
    var _id;

    // Displays Modal when user 'Right' Clicks on Event
    dp.contextMenu = new DayPilot.Menu({
        items : [
            {
                text: 'Edit',
                onClick: function (args) {
                    var e_this = args.source.data
                    if (e_this.flag) {                                            
                        // Need to undo Setup / TearDown Calc. to get real Event Time
                        var start = new DayPilot.Date(e_this.start.value).addMinutes(e_this.setup);
                        var end = new DayPilot.Date(e_this.end.value).addMinutes(-Math.abs(e_this.teardown));
                    
                        // Gather Data For Inputs
                        
                        // current event ID to work on
                        _id = e_this.id;
                        $('<input>', {type: 'hidden', name: 'resource_id', value: e_this.id}).appendTo('#editResourceForm');                    
                        $('#resourceEditResourceName').text(e_this.text);
                        $('#resourceEditStart').val(start.value);
                        $('#resourceEditEnd').val(end.value);
                        $('input[name="resourceEditSetupTime"]').val(e_this.setup);
                        $('input[name="resourceEditTeardownTime"]').val(e_this.teardown);
                        // Load Modal
                        $('#resourceModal').modal('show');
                    } else {
                        var modal = new DayPilot.Modal({
                            onClosed: function(args) {
                                // console.log("Modal dialog closed");
                            },
                        });
                        modal.showHtml("<h1><center>Cannot Edit Saved Events.</center></h1>");                        
                    }
                }
            },
            {
                text: 'Delete',
                onClick: function(args) {
                    if (args.source.data.flag) {
                        if (args.source.data.isIMR) {
                          dp.message('Cannot Remove Initial Meeting Room!')
                        }else {
                            @if (isset($event->id))
                                deleteEvArr.push(args.source.data.id)
                            @endif
                            dp.events.remove(args.source);
                            
                            // Remove that event from list of user created events
                            var pos = stored_events.map(function (x) {
                                return x.id;
                            }).indexOf(args.source.data.id);
                            stored_events.splice(pos, 1);                                         
                        }                        
                    } else {
                        dp.message('Cannot delete')
                    }
                }
            }
        ]
    });



    // On submit click  only get events we just created   
    // $('#save_event').on('click', function (e) {
    var allowSubmit = false;
    $('input[name="submit"]').on('click', function (e) {
    // $('input[name="submit"]').bind("click.myclick", function (e) {
        // Check all input required input Fields 
        // var $this = $(this);

        var counter = 0;

        if (!$('#course_id').val() ) {
            let html = "<span class=\"closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span><strong>Error!</strong> The Course field is required.";
            $( ".error-storage" ).append('<div id="course-err" class="alert-err">'+ html+ '</div>');
            
            $( ".error-storage" ).show()
            counter++;
            // setTimeout(function(){
            //     $('#course-err').remove();
            // }, 4000);
            $('#course_id').focus();

        }
        if ($('#class_size').val() == 0) {
            let html = "<span class=\"closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span><strong>Error!</strong> The Number of Participants must be at least 1.";
            $( ".error-storage" ).append('<div id="class-err" class="alert-err">'+ html+ '</div>');
            $( ".error-storage" ).show()
            counter++;
            // setTimeout(function(){
            //     $('#class-err').remove();
            // }, 4000);
            $('#class_size').focus();

        }

        if ($('#selectDate').val() === 'Invalid date') {
            let html = "<span class=\"closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span><strong>Error!</strong> Use the Date Picker to select a date.";
            $( ".error-storage" ).append('<div id="date-err" class="alert-err">'+ html+ '</div>');
            $( ".error-storage" ).show()
            counter++;
            // setTimeout(function(){
            //     $('#date-err').remove();
            // }, 4000);
            $('#single_date').focus();
        }
        

        if (!$('#initial_meeting_room').val()) {
            let html = "<span class=\"closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span><strong>Error!</strong> The Initial Meeting Room field is required.";
            $( ".error-storage" ).append('<div id="imr-err" class="alert-err">'+ html+ '</div>');
            $( ".error-storage" ).show()
            counter++;
            // setTimeout(function(){
            //     $('#imr-err').remove();
            // }, 4000);
            $('#initial_meeting_room').focus();
        }

        if (counter > 0) {
            // e.preventDefault();            
            return false;
        }else {
             console.log('stored events:'+stored_events.length);
            for (let i = 0; i < stored_events.length; i++) {

                //alert('I am here?'+stored_events[i].start);
                console.log(i +'start1: ' + stored_events[i].start)
                 console.log(i +'end1: ' + stored_events[i].end)

                //(2020-07-09 mitcks) I do not understand why Tanner was changing the start and end times here?
                // this seems to be causing the bug in documented in issue SIMS30-652, I'm commenting out for
                // now to fix the issue, but leaving the code and debugging here in case might break something else
                //(2020-08-06 mitcks) - ok so now I understand why this was here, it was removing the setup and
                // teardown times so that they weren't included twice, uncommented them need to go back and look
                // at 652
                //(2020-08-09 mitcks) added this if allowSubmit to address both 652 and the new bug commenting it out created
                if (allowSubmit || overlapCounter == 0) {
                    stored_events[i].start = new DayPilot.Date(stored_events[i].start).addMinutes(stored_events[i].setup);
                    stored_events[i].end = new DayPilot.Date(stored_events[i].end).addMinutes(-Math.abs(stored_events[i].teardown));
                }
                // console.log(i +' start2: ' + stored_events[i].start)
                // console.log(i +' end2: ' + stored_events[i].end)
            }
            $('#resource_events').val(JSON.stringify(stored_events));
            $('#delete_event_arr').val(JSON.stringify(deleteEvArr));
                          
            if (!allowSubmit && overlapCounter > 0) {
                e.preventDefault();              
                swal({                    
                    title: "{{ trans('alerts.frontend.scheduling.has-conflicts') }}",
                    text: "{{ trans('alerts.frontend.scheduling.conflict_confirm') }}",
                    buttons: true
                }, function (value) {
                    if (value) {
                        allowSubmit = true;
                    }
                });
            }
        }
    });


    /*
    * HELPER FUNCTIONS 
    */

    /**
    * Build Events - Add Setup and Teardown times, and adjust barColor
    */

    // This is just for the duplicateEvent route ...
    //if Event ID be able to edit those resources that have matching ID's
    @if (isset($event_resources))
        function loadDuplicates() {

            var event_res = {!! json_encode($event_resources->toArray()) !!};

            // loop through events we can edit and add to grid
            for (let e = 0; e < event_res.length; e++) {
                event_res[e].duplicated = true;
                let ev = addEvent(event_res[e]);
            }
        }
    @endif

   //(mitcks) Loads the scheduled resources for a given date (including event being edited),
   // populated with an array via ajax, using the route /courseInstance/getEventsAndForDate/".concat(date)
   function addEventSetupAndTeardownTimes(arr) {

       var jsonObj = [];

       //console.log(arr); //mitcks

       for (let i = 0; i < arr.length; i++) {
           // Show difference between resources for other events and ones for event being edited
         @if (isset($editable)) //EDITING EXISTING EVENT (mitcks) this is set in the controller for edit and templateApply, create and duplicate fall into else
            @if (isset($event->id))
                let event_id = '{{ $event->id }}';
                if (arr[i].event_id != event_id) { //(mitcks) these are all the events on this day NOT related to the event being edited
                    //console.log(arr); //mitcks
                    arr[i].backColor = 'rgba(204, 204, 204, 0.70)'; //(mitcks) light gray = not event being edited, cannot be changed
                    arr[i].moveDisabled = true;
                    arr[i].resizeDisabled = true;
                    arr[i].clickDisabl1ed = true;
                    arr[i].deleteDisabled = true;
                }else{ //(mitcks) these are related to the event being edited
                    if (arr[i].isIMR == 1) {
                        arr[i].backColor = 'rgb(219, 242, 189)'; //(mitcks) light green for IMR
                        arr[i].barColor = $('#html_color').val(); //set to current selected color
                    }
                    else
                    {
                        arr[i].backColor = 'rgb(219, 242, 189, 0.50)'; //(mitcks) more opaque light green for other rooms for this event
                        arr[i].barColor = $('#html_color').val(); //set to current selected color
                    }
                    arr[i].flag = true;
                    arr[i].editable = true;
                    // Note: !! forces to return a boolean, remove that and it returns the full obj in the array
                    let shouldPush = !stored_events.find(x => x.id == arr[i].id)
                    if (shouldPush) {
                        stored_events.push(arr[i]);                
                    }
                    
                    // .. if already exist in dp.events.list we should not push to stored_events again
                }
            @endif
         @else //(mitcks) creating new event
            arr[i].backColor = 'rgba(204, 204, 204, 0.70)'; //(mitcks) existing events are gray
            arr[i].moveDisabled = true;
            arr[i].resizeDisabled = true;
            arr[i].clickDisabled = true;
            arr[i].deleteDisabled = true;
         @endif
           
         arr[i].areas = []; // Array To Store Setup / Teardown colors      

         //  Build Setup Times
         let setup = new DayPilot.Date(arr[i].start).addMinutes(-Math.abs(arr[i].setup));  

         // new start time w/ added setup
         arr[i].start = setup;
         
        var tmp = arr[i].barColor;
        // console.log('tmp:'+ tmp)
         
         // Push to style arr
         arr[i].areas.push({
            left: 0,
            w: ((arr[i].setup / dp.cellDuration) * dp.cellWidth),
            style: "height:4px;background: repeating-linear-gradient(45deg, ".concat(tmp, ", ").concat(tmp, ", 2.5px, ").concat(invert(tmp), " 2.5px, ").concat(invert(tmp), " 5px);"),
         });                        

         // Build Teardown times
         let teardown = new DayPilot.Date(arr[i].end).addMinutes(arr[i].teardown);

         arr[i].areas.push({
            right: 0,
            w: ((arr[i].teardown / dp.cellDuration) * dp.cellWidth),
            style: "height:4px;background: repeating-linear-gradient(45deg, ".concat(tmp, ", ").concat(tmp, ", 2.5px, ").concat(invert(tmp), " 2.5px, ").concat(invert(tmp), " 5px);"),                    
         });

         // New End time w/ teardown included
         arr[i].end = teardown;

         jsonObj.push(arr[i]);
       }
       // return array;
       return jsonObj;
   }


   /*
    * Add Event To Grid
   */
   function addEvent(args) {

        var resource;

        if (typeof args === 'string') {
            // On IMR dropdown
            resource = args.toString();
        } else {
            // this comes from dp.onTimeRangeSelected
            resource = args.resource || args.resource_id.toString()
        }

        @if (isset($event->id))
        @else
            if (!$('#course_id').val()) {
                dp.message("Must Select Course First.");
                return; // Can Give some Error Msg.
            }

        @endif      

        // Build Event Start Time
        var startT = convertTime(slider_values[0]);
        if ($('#setup_time').val() > 0) {
            let setup = new DayPilot.Date(startT).addMinutes(-Math.abs($('#setup_time').val()));  
            // new start time w/ added setup
            startT = setup;
        }
       

        // Build Event End Time
        var endT = convertTime(slider_values[1]);
        if ($('#teardown_time').val() > 0) {
            let teardown = new DayPilot.Date(endT).addMinutes($('#teardown_time').val());  
            // new start time w/ added teardown
            endT = teardown;
        }

        // Set Bar Color, might need to change
        var tmp;
        if ( $('#html_color').val() ) {
            tmp = $('#html_color').val();
        }else {
            // Default White
            tmp = 'rgb(255, 255, 255)';
        }

        var imr;
        var backColor;
        var text; //(mitcks) I added this variable so the text could be customized for IMR
        if (typeof args === 'string') {
            imr = 1;
            backColor = 'rgb(97, 235, 52)' //(mitcks) used on create event when IMR selected from dropdown and added to grid
            text = $("#course_id option:selected").text()+ '(IMR)';
        }
        else if (args.isIMR == 1) {         
            imr = 1;
            backColor = 'rgb(97, 235, 52)';
            tmp = $('#html_color').val();
            text = $("#course_id option:selected").text()+ '(IMR)';
        }else {
            imr = null;
            backColor = 'rgb(97, 235, 52)'; //used when a user clicks on grid to add resource, setting to bright green
            text = $("#course_id option:selected").text();
        }

        var e_setup;
        args.duplicated ? e_setup = args.setup_time : e_setup = $('#setup_time').val()

        var e_tear;
        args.duplicated ? e_tear = args.teardown_time : e_tear = $('#teardown_time').val()

        // Create Event
        var e = new DayPilot.Event({
            flag: true,
            non_existing: true, // This Prevents Day Pilot Duplicates
            start: args.start_time != null ? new DayPilot.Date(args.start_time).addMinutes(-Math.abs(args.setup_time)) : startT, // Get from Slider
            end: args.end_time != null ?new DayPilot.Date(args.end_time).addMinutes(args.teardown_time) : endT, // Get From Slider
            id: DayPilot.guid(),
            resource: resource,
            barColor: tmp,
            isIMR : imr,
            backColor : backColor,
            setup: args.setup_time != null ? args.setup_time : $('#setup_time').val(),
            teardown: args.teardown_time != null ? args.teardown_time : $('#teardown_time').val(),
            text:  text,
            areas: [
                {
                    left: 0,
                    w: ((e_setup / dp.cellDuration) * dp.cellWidth),
                    style: "height:4px;background: repeating-linear-gradient(45deg, ".concat(tmp, ", ").concat(tmp, ", 2.5px, ").concat(invert(tmp), " 2.5px, ").concat(invert(tmp), " 5px);"),
                },
                {
                    right: 0,
                    w: ((e_tear / dp.cellDuration) * dp.cellWidth),
                    style: "height:4px;background: repeating-linear-gradient(45deg, ".concat(tmp, ", ").concat(tmp, ", 2.5px, ").concat(invert(tmp), " 2.5px, ").concat(invert(tmp), " 5px);"),
                }
            ], // Add Setup and Teardown 
        });
        
        // Check for overlap conflicts
        if (resources_allowed.includes(parseInt(resource))) {
            checkUserConflicts(e.data, true)
            dp.events.add(e);
            dp.message("Created");        
            // Array to store user created events
            stored_events.push(e.data);
        }else {
            dp.message("Don't have access to that resource");
        }
   }


   // check for overlap conficts on user created events
   // parameter named 'first' determines if this is the first pass to the func. dp.update breaks events that don't exist yet idk why ¯\_(ツ)_/¯
   function checkUserConflicts(event, first) {       
       let resource_event_list = dp.rows.find(event.resource).events.all();              
       if (resource_event_list.length > 0) {
           for (let i = 0; i < resource_event_list.length; i++) {
               // Day Pilot is finding self row for some reason when switching back n fourth dates, only increment if not _self(id)
               if (resource_event_list[i].data.id != event.id) {                
                    let overlap = DayPilot.Util.overlaps(resource_event_list[i].data.start, resource_event_list[i].data.end, event.start, event.end);
                    if (overlap) {
                        event.backColor = 'rgb(219, 92, 92)'; //red
                        if(!first) {
                            dp.update();    
                        }
                        overlapCounter++;                
                        break;                        
                    }else {
                        event.backColor = 'rgb(97, 235, 52)'; //green
                        if(!first) {
                            dp.update();
                        } 
                    }
               }else {
                    event.backColor = 'rgb(97, 235, 52)'; //green
                    dp.update(); 
                }
           }   
       }
       return
   }



    /**
     * Handle vertical Lines in time slider move
    */
    $("#slider-range").on("slide", function(event, ui) {
        // get date of current grid to know where to place line
        var gridDate = moment($('#selectDate').val()).format("Y-MM-DD");        

        var hours1 = Math.floor(ui.values[0] / 60);
        var minutes1 = ui.values[0] - (hours1 * 60);
        hours1 = hours1 < 10 ? '0' + hours1.toString() : hours1.toString();
        minutes1 = minutes1 === 0 ? '0' + minutes1.toString() : minutes1.toString();       
        var line1 = gridDate + 'T' + hours1 + ':' +  minutes1 + ':00';
        

        var hours2 = Math.floor(ui.values[1] / 60);
        var minutes2 = ui.values[1] - (hours2 * 60);
        hours2 = hours2 < 10 ? '0' + hours2.toString() : hours2.toString();
        minutes2 = minutes2 === 0 ? '0' + minutes2.toString() : minutes2.toString();
        var line2 = gridDate + 'T' + hours2 + ':' +  minutes2 + ':00';

        // convert setup/teardown minutes to seconds so it can be subtracted/added below with moment (note: capital HH is 24 hour time format)
        var setupSeconds = $("#setup_time").val()*60;
        var setupLine = moment(hours1 + ':' +  minutes1 + ':00', "HH:mm:ss").subtract(setupSeconds, 'seconds').format("HH:mm:ss");

        var teardownSeconds = $("#teardown_time").val()*60;
        var teardownLine = moment(hours2 + ':' +  minutes2 + ':00', "HH:mm:ss").add(teardownSeconds, 'seconds').format("HH:mm:ss");

        // Update Grid
        dp.update({separators: [{color:"blue", location: gridDate + "T" + setupLine}, {color:"blue", location: gridDate + "T" + teardownLine}, {color: 'red', location: line1}, {color: 'red', location: line2}]});

    });

    /**
     * Handles Moving editable events on date change
    */
    function editEventChange(date, arr) {
        // if editable event need to delete from dp.events.list then reapply
        for (let i = 0; i < arr.length; i++) {

            if (arr[i].editable) {
                // if returns fasle don't want to do anything
                let can_move = !dp.events.find(arr[i].id);
                if (can_move) {
                    var s_time = arr[i].start.value.slice(10, arr[i].start.value.length);
                    arr[i].start = new DayPilot.Date(date + s_time);

                    var e_time = arr[i].end.value.slice(10, arr[i].end.value.length);
                    arr[i].end = new DayPilot.Date(date + e_time);

                    dp.events.list.push(arr[i])
                    dp.update()
                }else {
                    let e = dp.events.find(arr[i].id);
                    e.data.areas[0] = arr[i].areas[0]
                    e.data.areas[1] = arr[i].areas[1]
                    e.data.setup = arr[i].setup
                    e.data.teardown = arr[i].teardown

                    // strip times, in case the user edited it while on another date
                    let start = arr[i].start.value.slice(10, arr[i].start.value.length);
                    e.data.start = new DayPilot.Date(date + start);

                    let end = arr[i].end.value.slice(10, arr[i].end.value.length);
                    e.data.end = new DayPilot.Date(date + end);

                    // can't update here causes creating event to not show grid but they exist in the events array ?? ¯\_(ツ)_/¯
                }
            }
        }        
    }


    /**
     * Handles loading user created Evnts on date change
    */
    function dateChange (date, arr) {


        for (let i = 0; i < arr.length; i++) {
            // This Prevents Day Pilot Duplicates    
            if (arr[i].non_existing) {
            // if (arr[i].flag) {
            
                // replace date for start and end with $(this)           
                var s_time = arr[i].start.value.slice(10, arr[i].start.value.length);
                arr[i].start = new DayPilot.Date(date + s_time);

                var e_time = arr[i].end.value.slice(10, arr[i].end.value.length);
                arr[i].end = new DayPilot.Date(date + e_time);

                dp.events.list.push(arr[i])
            }
        }
        // console.log(dp.events.list)
        
        dp.update()

    }


   // Invert Bar Color to show setup and teardown differences
    function invert(rgb) {       
        if (!rgb) {
            return 'rgb(239, 153, 87)';
        }else {
            if (rgb.slice(0,4) === 'rgba') {
                rgb = rgb.slice(5, rgb.length-1);
            }else {
                rgb = rgb.slice(4, rgb.length-1);
            }

            // put into arr
            var arr = rgb.split(',');

            var r = (255 - arr[0])
            var g = (255 - arr[1])
            var b = (255 - arr[2])
            return "rgb(".concat(r, ", ").concat(g, ", ").concat(b, ")");;
        }
    }


    // Convert Slider Time Values to HH:MM:SS for Day Pilot
    //(mitcks) todo: not sure why this function isn't used throughout?  I think this could eliminate several sections of redundant code
    function convertTime(slider_value) {

        var gridDate = moment($('#selectDate').val()).format("Y-MM-DD");
        var hours = Math.floor(slider_value / 60);
        var minutes = slider_value - (hours * 60);
        hours = hours < 10 ? '0' + hours.toString() : hours.toString();
        minutes = minutes === 0 ? '0' + minutes.toString() : minutes.toString();       
        var timestamp = gridDate + 'T' + hours + ':' +  minutes + ':00';

        return   new DayPilot.Date(timestamp);
    }

    //ON RIGHT-CLICK UPDATE OF EXISTING GRID RESOURCE ITEM (does not execute on drag)
    //(mitcks) this function is called after a user right clicks on a resource in the grid to edit and clicks Update in that modal
    // Updates an Event when user gets prompted the Edit Modal
    $('#update-resource').on('click', function() {
        var eventToEdit = dp.events.find(_id);

        // Only Edit events we are currently adding
        if (eventToEdit.data.flag) {
            let setup_time = $('input[name="resourceEditSetupTime"]').val();
            if (setup_time != eventToEdit.data.setup) {
                let new_start = new DayPilot.Date($('#resourceEditStart').val()).addMinutes(-Math.abs(setup_time));
                eventToEdit.data.start = new_start;
                eventToEdit.data.setup = setup_time;
                eventToEdit.data.areas[0].w = ((setup_time / dp.cellDuration) * dp.cellWidth);
            }
    //(mitcks) the todo below is Tanner's - not sure if there was still something needing fixed here?
    // TODO FIX w (width) of undefined on trying to edit an event without this property 
            let teardown_time = $('input[name="resourceEditTeardownTime"]').val();
            if (teardown_time != eventToEdit.data.teardown) {
                let new_end = new DayPilot.Date($('#resourceEditEnd').val()).addMinutes(teardown_time);
                eventToEdit.data.end = new_end;
                eventToEdit.data.teardown = teardown_time;
                eventToEdit.data.areas[1].w = ((teardown_time / dp.cellDuration) * dp.cellWidth);
            }
            dp.update();
            $('#resourceModal').modal('hide');
        }
    });

    /*
     * Filtering   
    */

    // Apply Filters
    function applyRowFilters() {

        // console.log ('applying filters');

        //set value of search input if value in local storage already (this is to make search "sticky" when switching days)
        if (localStorage['search_text'] !== undefined) {
            $("input[name=searchRows]").val(localStorage['search_text']);
        }

        if (localStorage['events_filter'] != null) {
            $('input:radio[name=events_filter][value="'+ localStorage['events_filter']+'"]').prop('checked', true);
        }

        if (localStorage['phys_filter'] != null) {
            $('input:radio[name=phys_filter][value="'+ localStorage['phys_filter']+'"]').prop('checked', true);
        }

        var resourceType = null;
        if (localStorage['phys_filter'] !== undefined)
        {
            resourceType = localStorage['phys_filter'];
        }

        var availabilityType = null;
        if (localStorage['events_filter'] !== undefined)
        {
            availabilityType = localStorage['events_filter'];
        }

        //set this to '' here because otherwise the ToUpper function errors on null/undefined
        var searchString = '';
        if (localStorage['search_text'] !== undefined)
        {
            searchString = localStorage['search_text'];
        }

        // console.log ("resourceType: " + resourceType);
        // console.log ("availabilityType: " + availabilityType);
        // console.log ("searchString: " + searchString);

        if(availabilityType === 'Scheduled')
        {
            if(resourceType === 'Room')
            {
                var query = {name: 'search_rooms_scheduled', value: searchString};
            }
            if(resourceType === 'Equipment')
            {
                var query = {name: 'search_equipment_scheduled', value: searchString};
            }
            if(resourceType === 'Personnel')
            {
                var query = {name: 'search_personnel_scheduled', value: searchString};
            }
            if(resourceType === null)
            {
                var query = {name: 'search_scheduled', value: searchString};
            }
        }

        if(availabilityType === 'Available')
        {
            if(resourceType === 'Room')
            {
                var query = {name: 'search_rooms_available', value: searchString};
            }
            if(resourceType === 'Equipment')
            {
                var query = {name: 'search_equipment_available', value: searchString};
            }
            if(resourceType === 'Personnel')
            {
                var query = {name: 'search_personnel_available', value: searchString};
            }
            if(resourceType === null)
            {
                var query = {name: 'search_available', value: searchString};
            }
        }

        if(availabilityType === null)
        {
            if(resourceType === 'Room')
            {
                var query = {name: 'search_rooms_all', value: searchString};
            }
            if(resourceType === 'Equipment')
            {
                var query = {name: 'search_equipment_all', value: searchString};
            }
            if(resourceType === 'Personnel')
            {
                var query = {name: 'search_personnel_all', value: searchString};
            }
            if(resourceType === null)
            {
                var query = {name: 'search_all', value: searchString};
            }
        }

        dp.rows.filter(query);

    }

    // Reset Filters
    function resetRowFilters() {

        //mitcks 2020-07-20: clear values stored in local storage so they do not "stick" when filters reset
        localStorage.removeItem('search_text');
        localStorage.removeItem('events_filter');
        localStorage.removeItem('phys_filter');

        $("input[type=radio]").each(function() {
            $(this).prop('checked', false);
        });
        dp.events.filter(null);
        dp.rows.filter(null);

        $("input[name=searchRows]").each(function() {
            $(this).val('');
        });
        return;
    }

    // Reset Event
    $("#resetFilters").on('click',function(e) {
        
        resetRowFilters();
        e.preventDefault();
    });

    // search Event
    $("#filter").on('keyup', function() {

        //mitcks 2020-07-20 place in local storage to maintain search text when date changes
        localStorage['search_text'] = $(this).val();
    });


    // Radio Buttons
    $('body').on('click', 'input[type="radio"]', function (evnt) {

        //mitcks 2020-07-20 place in local storage to maintain filter when date changes
        localStorage[$(this).attr('name')] = $(this).val();
    });

    // Event Handler
    dp.onEventFilter = function(args) {
        if (args.e.text().toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
            args.visible = false;
        }
    };

    // Event Move Handler (changes background to bright green when resource dragged in grid)
    dp.onEventMoved = function (args) {
        // let allowed = resources_allowed.includes(parseInt(args.resource));
        // if (allowed) {
            // if (args.e.data.template_event) {
            if (args.e.data.flag) {               
                args.e.data.backColor = 'rgb(97, 235, 52)'; //bright green
                dp.update();   
            }
        // }
        if (overlapCounter > 0) {
            overlapCounter = overlapCounter -1;
        }
    };

    dp.onEventMoving = function (args) {
        let allowed = resources_allowed.includes(parseInt(args.resource));
        if (allowed) {
            // if (args.e.data.template_event) {
            //     args.e.data.backColor = 'rgb(97, 235, 52)';
            //     dp.update();   
            // }
            // updates IMR dropdown on event move
            if (args.e.data.isIMR) {
                $('#initial_meeting_room').val(parseInt(args.resource));

            }                        
        }else {
            args.left.enabled = false;
            args.allowed = false;
        }
        
    }

    // Row Handler    
    dp.onRowFilter = function(args) {

        // Search String & Room & Scheduled
        if (args.filter.name == 'search_rooms_scheduled') {

            if (args.row.events.isEmpty() || args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Room') === -1 ) )  {
                args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
            }
        }

        // Search String & Room & All (scheduled and available)
        if (args.filter.name == 'search_rooms_all') {

            if (args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Room') === -1 ) )  {
                args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
            }
        }

        // Search String & Equipment & All (scheduled and available)
        if (args.filter.name == 'search_equipment_all') {

            if (args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Equipment') === -1 ) )  {
                args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
            }
        }

        // Search Personnel & Equipment & All (scheduled and available)
        if (args.filter.name == 'search_personnel_all') {

            if (args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Personnel') === -1 ) )  {
                args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
            }
        }

        //search_all (search string and all types and availability)
        if (args.filter.name == 'search_all') {

            if (args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1)  {
                args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
            }
        }

        // Search String & Scheduled (no physical filter)
        if (args.filter.name == 'search_scheduled') {

            if (args.row.events.isEmpty() || args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 )  {
                args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
            }
        }

        // Search String & Room & Available
        if (args.filter.name == 'search_rooms_available') {

            if (!args.row.events.isEmpty() || args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Room') === -1 ) )  {
                args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
            }
        }

        // Search String & Available (no physical filter)
        if (args.filter.name == 'search_available') {

            if (!args.row.events.isEmpty() || args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 )  {
                args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
            }
        }

        // Search String & Equipment & Scheduled
        if (args.filter.name == 'search_equipment_scheduled') {

            if (args.row.events.isEmpty() || args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Equipment') === -1 ) )  {
                args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
            }
        }

        // Search String & Equipment & Available
        if (args.filter.name == 'search_equipment_available') {

            if (!args.row.events.isEmpty() || args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Equipment') === -1 ) )  {
                args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
            }
        }

        // Search String & Personnel & Scheduled
        if (args.filter.name == 'search_personnel_scheduled') {

            if (args.row.events.isEmpty() || args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Personnel') === -1 ) )  {
                args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
            }
        }

        // Search String & Personnel & Available
        if (args.filter.name == 'search_personnel_available') {

            if (!args.row.events.isEmpty() || args.row.name.toUpperCase().indexOf(args.filter.value.toUpperCase()) === -1 || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.indexOf('Personnel') === -1 ) )  {
                args.visible = false || (args.row.tags && args.row.tags.alwaysVisible);
            }
        }
    };

    // Need to move this to end so all globals have been defined
    @if(isset($templateList))
        $('#template_id').trigger('change');
    @endif

</script>