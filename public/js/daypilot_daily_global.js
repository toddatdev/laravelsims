'use_strict'
var dp = new DayPilot.Scheduler("dp");
var d = new Date();
var month = d.getMonth() + 1;
var day = d.getDate();
var selectedDate = d.getFullYear() + '-' +
    (('' + month).length < 2 ? '0' : '') + month + '-' +
    (('' + day).length < 2 ? '0' : '') + day;

var teardownTime, setupTime, classSize, initialMeetingRoom, start_time, end_time;
var newEventResourceId, newEventCourseId, newEventName
var addedResources = [];

function validateEvent(initial_meeting_room) {
    if(roDayPilot)
        return;

    newEventResourceId = initial_meeting_room || initialMeetingRoom;
    newEventCourseId = selectedCourse.id;
    newEventName = selectedCourse.abbrv;
    if(typeof newEventName == 'undefined') {
	newEventName = selectedCourse.course_instance.course.abbrv;
    }

    console.log(newEventResourceId);
    console.log(newEventCourseId);
    if (newEventResourceId && newEventCourseId) {

        var selectedEndTime = getFormatedTime('formatedEndTime');
        var selectedStartTime = getFormatedTime('formatedStartTime');
        var setup = getFormatedTime('setup_time');
        var teardown = getFormatedTime('teardown_time');
        let backColor = ($("#html_color").val().length ? $("#html_color").val() : "rgb(16, 102, 168)");
        let area = drawResourceColors(selectedStartTime, selectedEndTime, setup, teardown, backColor);

        let params = {
            start: selectedStartTime,
            end: selectedEndTime,
            resource: initial_meeting_room,
            eventName: newEventName,
            area: area,
            setup: setup,
            teardown: teardown
        }
        addEvent(params);
    } else {
        console.log("Missing information");
    }
}



/**
 * change cell color on business hours
 */
dp.onBeforeCellRender = function (args) {
    args.cell.backColor = "#f9f9f9";
    const businessHoursStart = new DayPilot.Date(selectedDate + businessBeginHour);
    const businessHoursEnd = new DayPilot.Date(selectedDate + businessEndHour);
    const cellStart = new DayPilot.Date(args.cell.start);

    if (cellStart >= businessHoursStart && cellStart < businessHoursEnd) {
        args.cell.backColor = "#fff";
    }
};

/**
 * onTimeRangeSelected
 * should add event and auto populate resource, start, end , setup, teardown times
 * @param {*} args
 */
 dp.onTimeRangeSelected = function(args) {
    if(!roDayPilot && isDataComplete()) {
        dp.clearSelection();

        var selectedEndTime = getFormatedTime('formatedEndTime');
        var selectedStartTime = getFormatedTime('formatedStartTime');
        var setup = getFormatedTime('setup_time');
        var teardown = getFormatedTime('teardown_time');

        var backColor = ($("#html_color").val().length ? $("#html_color").val() : "rgb(16, 102, 168)");
        var area = drawResourceColors(selectedStartTime, selectedEndTime, setup, teardown, backColor);

        selectedRangeArgs = args;
        if(!selectedCourse.hasOwnProperty('abbrv'))
            selectedCourse.abbrv = selectedCourse.course_instance.course.abbrv;

        let guid = DayPilot.guid();
        let e = new DayPilot.Event({
            start: new DayPilot.Date(selectedStartTime).addMinutes(-Math.abs(setup)),
            end: new DayPilot.Date(selectedEndTime).addMinutes(Math.abs(teardown)),
            id: guid,
            resource: args.resource,
            text: selectedCourse.abbrv,
            barColor: $("#html_color").val(),
            readonly: false,
            setupTime: setup,
            teardownTime: teardown,
            addedResource: true,
            areas: area
        });
        dp.events.add(e);

        createHiddenElements(guid, args.resource, e.data.start.value, e.data.end.value, setup, teardown);
        dp.update();
    } else {
        dp.clearSelection();
        selectedRangeArgs = args;
        swal({
            title: exceptionText.initialMeetingRoom_title,
            text: exceptionText.initialMeetingRoom_text,
            icon: "warning",
            dangerMode: true,
        })
        .then(function(pushedButton) {});
    }
};
var _internalResources = [];
function createHiddenElements(id, resource, start_time, end_time, setup, teardown) {
    $('<input>', {type: 'hidden', name: 'addedResources[]', value: id, id: id}).appendTo('#addCourseInstanceForm');
    $('<input>', {type: 'hidden', name: 'RID-'+id, value: resource}).appendTo('#addCourseInstanceForm');
    $('<input>', {type: 'hidden', name: 'RStart-'+id, value: start_time}).appendTo('#addCourseInstanceForm');
    $('<input>', {type: 'hidden', name: 'REnd-'+id, value: end_time}).appendTo('#addCourseInstanceForm');
    $('<input>', {type: 'hidden', name: 'RSetup-'+id, value: setup}).appendTo('#addCourseInstanceForm');
    $('<input>', {type: 'hidden', name: 'RTeardown-'+id, value: teardown}).appendTo('#addCourseInstanceForm');
    _internalResources.push(id);
}

function deleteAllHiddenElements() {
	for(let i = 0; i < _internalResources.length; ++i) {
		deleteHiddenElements(_internalResources[i]);
	}
	_internalResources = [];
}
function deleteHiddenElements(rid) {
    let keys = ["resource", "start", "end", "setup", "teardown"];
    for(let i = 0; i < keys.length; ++i) {
        let key = keys[i];
        deleteHiddenElement(rid, key);
    }
    $('#'+rid).remove(); 
}
function deleteHiddenElement(id, key) {
    let element = false;
    switch(key) {
        case 'resource':
            element = $('input[name="RID-'+id+'"]'); 
        break;
        case 'start':
            element = $('input[name="RStart-'+id+'"]'); 
        break;
        case 'end':
            element = $('input[name="REnd-'+id+'"]'); 
        break;
        case 'setup':
            element = $('input[name="RSetup-'+id+'"]'); 
        break;
        case 'teardown':
            element = $('input[name="RTeardown-'+id+'"]'); 
        break;
    }
    if(element)
        $(element).remove();
}
function editHiddenElement(id, key, value) {
    let element = false;
    switch(key) {
        case 'resource':
            element = $('input[name="RID-'+id+'"]'); 
        break;
        case 'start':
            element = $('input[name="RStart-'+id+'"]'); 
        break;
        case 'end':
            element = $('input[name="REnd-'+id+'"]'); 
        break;
        case 'setup':
            element = $('input[name="RSetup-'+id+'"]'); 
        break;
        case 'teardown':
            element = $('input[name="RTeardown-'+id+'"]'); 
        break;
    }
    if(element) 
        $(element).val(value);
}
function getHiddenElementValue(id, key) {
    let element = false;
    switch(key) {
        case 'resource':
            element = $('input[name="RID-'+id+'"]'); 
        break;
        case 'start':
            element = $('input[name="RStart-'+id+'"]'); 
        break;
        case 'end':
            element = $('input[name="REnd-'+id+'"]'); 
        break;
        case 'setup':
            element = $('input[name="RSetup-'+id+'"]'); 
        break;
        case 'teardown':
            element = $('input[name="RTeardown-'+id+'"]'); 
        break;
    }
    if(element) 
        return $(element).val();
    else
        return false;
}
dp.onEventResize = function (args) {
    editHiddenElement(args.e.data.id, 'start', args.newStart.value);
    editHiddenElement(args.e.data.id, 'end', args.newEnd.value);
    editHiddenElement(args.e.data.id, 'setup', 0);
    editHiddenElement(args.e.data.id, 'teardown', 0);

    args.e.data.areas =  [{start:0,  end: 0, top: 0, bottom: 0},{start:0,  end: 0, top: 0, bottom: 0}];
    dp.events.update(args.e);
    dp.message("Resized");
};

dp.onEventMove = function (args) {
    if (roDayPilot) {
        args.preventDefault();
        dp.message("Daypilot is in ReadOnly mode.");
    } else {
        if(args.e.data.resource == initialMeetingRoom) {
            $("[name='initial_meeting_room']").val(args.newResource);
            initialMeetingRoom = args.newResource;
        }
        editHiddenElement(args.e.data.id, 'start', args.newStart.value);
        editHiddenElement(args.e.data.id, 'end', args.newEnd.value);
        editHiddenElement(args.e.data.id, 'resource', args.newResource);
        editHiddenElement(args.e.data.id, 'setup', 0);
        editHiddenElement(args.e.data.id, 'teardown', 0);

        args.e.data.areas =  [{start:0,  end: 0, top: 0, bottom: 0},{start:0,  end: 0, top: 0, bottom: 0}];
        dp.events.update(args.e);
    }
};

dp.onEventMoved = function (args) {
    //$('#initial_meeting_room option[value='+args.newResource+']').prop('selected','selected');
    
    //var startTimeHours = new Date(args.newStart.value).getHours();
    //startTimeHours = startTimeHours < 10 ? '0'+startTimeHours : startTimeHours;
    //var startTime =  startTimeHours +':'+ new Date(args.newStart.value).getMinutes();
    
    //var endTimeHours = new Date(args.newEnd.value).getHours();
    //endTimeHours = endTimeHours < 10 ? '0'+endTimeHours : endTimeHours;
    //var endTime = endTimeHours +':'+ new Date(args.newEnd.value).getMinutes();
};

// Fired when editing resource
$("body").on("click", ".edit-resource", function(){
    let newSetup = $('input[name="resourceEditSetupTime"]').val();
    let newTeardown = $('input[name="resourceEditTeardownTime"]').val();
    let RID = $('input[name="resource_id"]').val();

    console.log(RID);
    editHiddenElement(RID, 'setup', newSetup);
    editHiddenElement(RID, 'teardown', newTeardown);

    dp.events.find(function(ev) {
        if(ev.data.id == RID) {
            let start = ev.data.start.addMinutes(Math.abs(newSetup));
            let end = ev.data.end.addMinutes(-Math.abs(newTeardown));
            let area = drawResourceColors(start.value, end.value, newSetup, newTeardown, ev.data.barColor);
            ev.data.areas = area;
            $('input[name="resource_id"]').remove();
            dp.update(ev);
        }
    });
    $('#resourceModal').modal('hide');
});

function isResourceAlreadyAdded(id) {
    for(let idx=0; idx < addedResources.length; ++idx) {
        if(addedResources[idx].data.hasOwnProperty('backend_id')) {
            if(addedResources[idx].data.backend_id == id) {
                return true;
            }
        }
    }
    return false;
}

function populateExistingEventResources() {
    if(typeof existingEvents == 'undefined')
        existingEvents = [];

    console.log("Found "+existingEvents.length+" existing events for this date");
    for(let i = 0; i < existingEvents.length; ++i) {
        let thisEvent = existingEvents[i];

        var selectedTearDownTime = getFormatedTime('selectedTearDownTime');
        var selectedSetupTime = getFormatedTime('selectedSetupTime');

        if(thisEvent.eventText) {
            var eventText = JSON.parse("["+thisEvent.eventText+"]");
        } else {
            var eventText = [];
        }
          if(thisEvent.event_resources.length) {
            for(let j = 0; j < thisEvent.event_resources.length; ++j) {
                let thisResource = thisEvent.event_resources[j];
                if(!isResourceAlreadyAdded(thisResource.id)) {

                    var start = new DayPilot.Date(thisResource.start_time).addMinutes(-Math.abs(thisResource.setup_time));
                    var end = new DayPilot.Date(thisResource.end_time).addMinutes(Math.abs(thisResource.teardown_time));
                    let backColor = (thisResource.event.color ? thisResource.event.color : "rgb(16, 102, 168)");
                    let area = drawResourceColors(thisResource.start_time, thisResource.end_time, thisResource.setup_time, thisResource.teardown_time, backColor);

                    let readonly = true;
                    let addedResource = false;
                    let resizeDisabled = true;
                    let moveDisabled = true;
                    let rightClickDisabled = true;
                    let deleteDisabled = true;
                    let cssClass = 'already-scheduled-event';

                    if(editCourseInstance && editCourseInstance.id == thisResource.event_id) {
                        readonly = false;
                        addedResource = true;
                        resizeDisabled = false;
                        moveDisabled = false;
                        rightClickDisabled = false;
                        deleteDisabled = false;
                        cssClass = '';

                        if(cloneCourseDate) {
                            console.log("Cloning: "+thisResource.start_time);
                            tmpStart = thisResource.start_time.split(" ");
                            tmpEnd = thisResource.end_time.split(" ");
                            start = new DayPilot.Date(cloneCourseDate+" "+tmpStart[1]).addMinutes(-Math.abs(thisResource.setup_time));
                            end = new DayPilot.Date(cloneCourseDate+" "+tmpEnd[1]).addMinutes(Math.abs(thisResource.teardown_time));
                        }
                    }

                    let guid = DayPilot.guid();
                    var f = new DayPilot.Event({
                        id: guid,
                        start: start,
                        end: end,
                        resource: thisResource.resource_id.toString(),
                        text: eventText[j].text,
                        bubbleHtml: eventText[j].bubbleHtml,
                        barColor: thisEvent.color,
                        readonly: readonly,
                        addedResource: addedResource,
                        backend_id: thisResource.id,
                        setupTime: thisResource.setup_time,
                        teardownTime: thisResource.teardown_time,
                        resizeDisabled: resizeDisabled,
                        moveDisabled: moveDisabled,
                        rightClickDisabled: rightClickDisabled,
                        deleteDisabled: deleteDisabled,
                        cssClass: cssClass,
                        areas: area,
                    });

                    if(editCourseInstance && editCourseInstance.id == thisResource.event_id) {
                        createHiddenElements(guid, thisResource.resource_id.toString(), f.data.start.value, f.data.end.value, thisResource.setup_time, thisResource.teardown_time);
                        addedResources.push(f);
                    }
                    dp.events.add(f);
                }
             }
        }
    }
    dp.update();
}

function getLocationOrResourceById(id) {
    let returnedResource = {};
        for(let idx = 0; idx < locationsAndResources.length; ++idx) {
            let resource = locationsAndResources[idx];
            if(resource.children.length) {
                for(let jdx = 0; jdx < resource.children.length; ++jdx) {
                    let child = resource.children[jdx];
                    if(child.children.length) {
                        for(let kdx = 0; kdx < child.children.length; ++kdx) {
                            let res = child.children[kdx];
                            if(res.id == id) {
                                returnedResource = res;
                                return res;
                            }
                        }
                    }
                }
            }
        }
    return returnedResource;
}
/**
 *handleVerticalLines
 */
function handleVerticalLines() {
    let start_time = getFormatedTime('start_time');
    let end_time = getFormatedTime('end_time');
    let selectedTearDownTime = getFormatedTime('selectedTearDownTime');
    let selectedSetupTime = getFormatedTime('selectedSetupTime');

    const params = {
        selectedStartTime: 'T' + start_time,
        selectedEndTime: 'T' + end_time
    };

    params.showBlueLine = false;
    if (setupTime != '0' && typeof setupTime != 'undefined') {
        params.showBlueLine = true;
        params.setupTime = selectedSetupTime;
    }

    if (teardownTime != '0' && typeof teardownTime != 'undefined') {
        params.showBlueLine = true;
        params.teardownTime = selectedTearDownTime;
    }

    //console.log("handleVerticalLines()",params);
    showVerticalLines(params);
}


/**
 * showVerticalLines
 * @param {*} params
 */
function showVerticalLines(params) {
    dp.update({separators: calculateDPSeparators(params)});
}

/**
 * getFormatedTime
 * @param {*} type
 */
function getFormatedTime(type) {
    start_time = $("[name='start_time']").val() || "09:00";
    end_time = $("[name='end_time']").val() || "10:00";

    let dateArr = selectedDate.split("-");
    let startDate = new Date(Date.parse(dateArr[1]+"/"+dateArr[2]+"/"+dateArr[0] + ' ' + start_time));
    let endDate = new Date(Date.parse(dateArr[1]+"/"+dateArr[2]+"/"+dateArr[0] + ' ' + end_time));
    startDate.setMinutes(startDate.getMinutes() - Math.abs(setupTime));
    endDate.setMinutes(endDate.getMinutes() + Math.abs(teardownTime));

    //show blue color on daypilot
    let startHours = (startDate.getHours() <= 9 ? ('0' + startDate.getHours()) : startDate.getHours());
    let endHours = (endDate.getHours() <= 9 ? ('0' + endDate.getHours()) : endDate.getHours());

    let selectedSetupTime = selectedDate + 'T' + startHours + ':' + (startDate.getMinutes() == 0 ? '00' : startDate.getMinutes()) + ':00';
    let selectedTearDownTime = selectedDate + 'T' + endHours + ':' + (endDate.getMinutes() == 0 ? '00' : endDate.getMinutes()) + ':00';

    let formatedStartTime = dateArr[0]+"-"+dateArr[1]+"-"+dateArr[2] + "T" + start_time;
    if(formatedStartTime.split(":").length < 3)
        formatedStartTime += ":00";

    let formatedEndTime = dateArr[0]+"-"+dateArr[1]+"-"+dateArr[2] + "T" + end_time;
    if(formatedEndTime.split(":").length < 3)
        formatedEndTime += ":00";

    switch (type) {
        case 'start_time':
            return start_time;
            break;
        case 'end_time':
            return end_time;
            break;
        case 'setup_time':
            return setupTime;
            break;
        case 'teardown_time':
            return teardownTime;
            break;
        case 'formatedStartTime':
            return formatedStartTime;
            break;
        case 'formatedEndTime':
            return formatedEndTime;
            break;
        case 'selectedSetupTime':
            return selectedSetupTime;
            break;

        case 'selectedTearDownTime':
            return selectedTearDownTime;
            break;
        default:
            break;
    }
}

/**
 * overlapsEvent
 * to check any two events are overlapping
 * @param {*} params, index
 */

function overlapsEvent(params, index, events) {
    const resourceId = $('#initial_meeting_room').val();
    let eventsList =  JSON.parse(JSON.stringify(events));
    let ret = false;
    eventsList.splice(index, 1);
     for(let idx = 0; idx < eventsList.length; ++idx){
        let checkEvent = dp.events.find(eventsList[idx].id);
        const a_start = new Date(params.start.value);
        //a_start.setMinutes(a_start.getMinutes() + parseInt(params.setupTime));
        a_start.setMinutes(a_start.getMinutes() + 1);

        const a_end = new Date(params.end.value);
        //a_end.setMinutes(a_end.getMinutes() - parseInt(params.teardownTime));
        a_end.setMinutes(a_end.getMinutes() - 1);

        const b_start = new Date(checkEvent.data.start.value);
        //b_start.setMinutes(b_start.getMinutes() + parseInt(checkEvent.data.setupTime));
        b_start.setMinutes(b_start.getMinutes() + 1);

        const b_end = new Date(checkEvent.data.end.value);
        //b_end.setMinutes(b_end.getMinutes() - parseInt(checkEvent.data.teardownTime));
        b_end.setMinutes(b_end.getMinutes() - 1);

        if (a_start.getTime() <= b_start.getTime() && b_start.getTime() <= a_end.getTime()) ret = true; // b starts in a
        if (a_start.getTime() <= b_end.getTime()   && b_end.getTime()   <= a_end.getTime()) ret = true; // b ends in a
        if (b_start.getTime() <  a_start.getTime() && a_end.getTime()   <  b_end.getTime()) ret = true; // a in b
    }
    return ret;
}

/**
 * handleSubmit
 * to check any  events are overlapping
 * @param {*} event
 */
function handleSubmit(event) {
    let editing = false;
    if($("[name='course_id']").prop('disabled')) {
        editing = true;
        CourseTemplateSelect.setSelectEnable('course');
        $("[name='course_id']").prop('disabled', false);
    }
    if( (new Date(selectedDate+""+selectedEndTime).getTime()/1000) <= (new Date(selectedDate+""+selectedStartTime).getTime()/1000) ) {
        swal({
            title: exceptionText.timelap_title,
            text: exceptionText.timelap_text,
            icon: "warning",
            dangerMode: true,
        })
        .then(function(pushedButton) {});
        if(editing)
            CourseTemplateSelect.setSelectDisable('course');

        event.preventDefault();
        return false;
    }

    const resourceIds  = [];
    dp.events.find(function(ev) {
        if(ev.data.addedResource) {
            resourceIds.push(ev.data.resource);
        }
    });

    let overlaps = 0;
    for (let i = 0 ; i < resourceIds.length; i++){
        const eventsList = dp.rows.find(resourceIds[i]).events.all();
        if ( eventsList.length >=2  ){
            for ( let j = 0; j < eventsList.length ; j++){
                if(eventsList[j].data.addedResource) {
                    let laps  = overlapsEvent(eventsList[j].data, j, eventsList);
                    if(laps)
                        overlaps++;
                }
            }
        }
    }

    if(overlaps > 0){
        event.preventDefault();
        swal({
            title: "("+overlaps+") "+exceptionText.overlap_title,
            text: exceptionText.overlap_text,
            icon: "warning",
            buttons: true,
            buttons: [exceptionText.overlap_button1, exceptionText.overlap_button2],
            dangerMode: true,
        })
        .then(function(willNotEdit) {
            if (willNotEdit) {
                $('#addCourseInstanceForm').submit()
            } else {
                if(editing)
                    CourseTemplateSelect.setSelectDisable('course');
                return false;
            }
        });
    }
}

/**
 * to cleat the form fields in add event modal
 * clearAddEventForm
 */
function clearAddEventForm() {
}

$('[data-toggle="tooltip"]').tooltip()

$('.stepBugFix').on('change', function(event) {
    adjustMinutes(event);
});

function adjustMinutes(e) {
    let minutes = e.target.value;
    let val = e.target.value;
    if(minutes != 15 || minutes != 30 || minutes != 45 || minutes != 60 
      || minutes != 75 || minutes != 90 || minutes != 105 || minutes != 120) {
          if(minutes > 0 && minutes < 15) 
            val = 15;
          else if(minutes > 15 && minutes < 30)
            val = 30;
          else if(minutes > 30 && minutes < 45)
            val = 45;
          else if(minutes > 45 && minutes < 60)
            val = 60;
          else if(minutes > 60 && minutes < 75)
            val = 75;
          else if(minutes > 75 && minutes < 90)
            val = 90;
          else if(minutes > 90 && minutes < 105)
            val = 105;
          else if(minutes > 105 && minutes < 120)
            val = 120;
    }
    $(e.target).val(val);
}

$("body").on("change","[name='class_size']", function(event){
        classSize = $(event.target).val();
});

var pageFilters = [];
function resetRowFilters() {
    pageFilters = [];
    $("input[type=radio]").each(function() {
        if($(this).prop('name') == "events_filter" || $(this).prop('name') == "phys_filter")
            $(this).prop('checked', false);
    });
    dp.events.filter(null);
    dp.rows.filter(null);

    $("input[name=searchRows]").each(function() {
        $(this).val('');
    });
    return;
}

$("#resetFilters").click(function() {
        resetRowFilters();
});

//filter rows
$('body').on('click', 'input[type="radio"]', function (evnt) {
    var query = {name:$(this).val(), type: $(this).attr('name')};

    for(let idx = 0; idx < pageFilters.length; ++idx) {
        let f = pageFilters[idx];
        if(f.name == query.name || f.type == query.type)
            pageFilters.splice(idx,1);
    }

    dp.rows.filter(null) 
    dp.events.filter(null) 

    if($(this).prop('checked')) {
        pageFilters.push(query);
        dp.rows.filter(query) 

        if(query.name == "This_Event")
            dp.events.filter(query);
        //else
        //    dp.events.filter(null);
    } else {
        if(pageFilters.length) {
            dp.rows.filter(pageFilters[0]) 

            if(query.name == "This_Event")
                dp.events.filter(query);
            else
                dp.events.filter(null);
        } else {
            dp.rows.filter(null);
            dp.events.filter(null);
        }
    }
});


function searchRowHandler(event) {
    var keyCode = event.keyCode || event.which;
    if (keyCode === 13) {
    } else {
        var query = {name: "Search", value: event.target.value};
        for(let idx = 0; idx < pageFilters.length; ++idx) {
            let f = pageFilters[idx];
            if(f.name == query.name)
                pageFilters.splice(idx,1);
        }
        pageFilters.push(query);
        dp.rows.filter(query);
    }
}

$("#clear").click(function () {
    $("#filter").val("");
    dp.rows.filter(null);
    return false;
});


// Make sure data in forms is completed and selected
function isDataComplete() {
    let initial_meeting_room = $("[name='initial_meeting_room']").val();
    setupTime = $('input[name="setup_time"]').val();
    teardownTime = $('input[name="teardown_time"]').val();
    classSize = $('input[name="class_size"]').val();

    if(initial_meeting_room && setupTime && teardownTime && classSize)
        return true;
    else
         return false;   
}

function clearAddedEvents() {
    let newEvs = [];
    let evs = dp.events.list;
    if(evs) {
    for(let idx = 0; idx < evs.length; ++idx) {
        if(!evs[idx].addedResource)
            newEvs.push(evs[idx]);
    }
    dp.events.list = [];
    dp.events.list = newEvs;
    dp.update();
    deleteAllHiddenElements();
    }
}

// Fired when adding via select list
function initResourceHandler(event) {
    if(initialMeetingRoom && initialMeetingRoom != $("[name='initial_meeting_room']").val()) {
        removeEvent(initialMeetingRoom);
    }
    
    if(!roDayPilot) {
        if(isDataComplete()) {
            initialMeetingRoom = $("[name='initial_meeting_room']").val();
            validateEvent(initialMeetingRoom);
        } else {
            $("[name='initial_meeting_room']").prop('selectedIndex',0);
            swal({
                title: exceptionText.missingFields_title,
                text: exceptionText.missingFields_text,
                icon: "warning",
                dangerMode: true,
            })
            .then(function(pushedButton) {});
        }
    }
}

// on click create event form submit, check any events are overlaps
$("body").on("click",".create-event-submit", function(event){
    handleSubmit(event);
});

function resetPresetValues() {
            colorInput.spectrum("set", '');
            //__resetCourseSelect();
            __setSetupTeardownTime('setup_time', 0, false);
            __setSetupTeardownTime('teardown_time', 0, false);
            setupTime = 0;
            teardownTime = 0;
            $("[name='schedule_addClass_InstructorReport']").val(0);
            $("[name='schedule_addClass_InstructorLeave']").val(0);
            $("[name='class_size']").val(0);
            $("[name='public_comments']").val('');
            $("[name='internal_comments']").val('');
            $("[name='initial_meeting_room']").prop('selectedIndex',0);

            let reportRadios = $("[name='instructorReportBA']");
            for(var i = 0; i < reportRadios.length; ++i) {
                let thisElement = $(reportRadios[i]);
                if(thisElement.val() == "Before")
                    thisElement.prop('checked', true);
                else
                    thisElement.prop('checked', false);
            }

            let leaveRadios = $("[name='instructorLeaveBA']");
            for(var i = 0; i < leaveRadios.length; ++i) {
                let thisElement = $(leaveRadios[i]);
                if(thisElement.val() == "Before")
                    thisElement.prop('checked', false);
                else
                    thisElement.prop('checked', true);
            }
}

function sims_spec_needed(value) {
    if(value == 0)
        $("[name='sims_spec_needed']").prop("checked", false);
    else 
        $("[name='sims_spec_needed']").prop("checked", true);
}

function special_requirements(value) {
    if(value == 0)
        $("[name='special_requirements']").prop("checked", false);
    else
        $("[name='special_requirements']").prop("checked", true);
}
function y_fac_report(value) {
            var isBefore = true;
            if(value < 0)
                isBefore = false;

            $("[name='schedule_addClass_InstructorReport']").val(Math.abs(value));
            let reportRadios = $("[name='instructorReportBA']");
            for(var i = 0; i < reportRadios.length; ++i) {
                let thisElement = $(reportRadios[i]);
                if(thisElement.val() == "Before") {
                    if(isBefore)
                        thisElement.prop('checked', true);
                    else
                       thisElement.prop('checked', false);
                } else if(thisElement.val() == "After") {
                    if(isBefore)
                       thisElement.prop('checked', false);
                    else
                       thisElement.prop('checked', true);
                }
            }
}

function z_fac_leave(value) {
            var isBefore = true;
            if(value < 0)
                isBefore = false;

            $("[name='schedule_addClass_InstructorLeave']").val(Math.abs(value));
            let leaveRadios = $("[name='instructorLeaveBA']");
            for(var i = 0; i < leaveRadios.length; ++i) {
                let thisElement = $(leaveRadios[i]);
                if(thisElement.val() == "Before") {
                    if(isBefore)
                        thisElement.prop('checked', true);
                    else
                        thisElement.prop('checked', false);
                } else if(thisElement.val() == "After") {
                    if(isBefore)
                        thisElement.prop('checked', false);
                    else
                        thisElement.prop('checked', true);
                }
            }
}

function populatePresetValues(course) {
    for(let idx = 0; idx < course.course_option.length; ++idx) {
        let option = course.course_option[idx];
        switch(option.course_options.description) {
            case 'color':
                selectedCourse.color = option.value;
                colorInput.spectrum("set", option.value);
            break;
            case 'z_teardown_time':
                teardownTime = option.value;
                __setSetupTeardownTime('teardown_time', option.value);
            break;
            case 'z_setup_time':
                setupTime = option.value;
                __setSetupTeardownTime('setup_time', option.value);
            break;
            case 'y_fac_report':
                y_fac_report(option.value);
            break;
            case 'z_fac_leave':
                z_fac_leave(option.value);
            break;
        }
    }
}

dp.onEventFilter = function(args) {
    if(args.filter.name == "This_Event") {
        if(args.e.data.addedResource) 
            args.visible = true
         else 
            args.visible = false;
    }
}


/**
 *onRowFilter
 */
dp.onRowFilter = function (args) {
    if (Array.isArray(pageFilters)) {
        for(let key = 0; key < pageFilters.length; ++key) {
            let filter = pageFilters[key];
        if(filter.name == "Room" || filter.name == "Equipment" || filter.name == "Personnel") {
            if (args.row.name.toUpperCase() == filter.name.toUpperCase() || (args.row.data.hasOwnProperty('type') &&  args.row.data.type.toUpperCase().indexOf(filter.name.toUpperCase()) === -1 ) ) {
                args.visible = false;
            }
        } else {
            if(filter.name == "Scheduled") {
                if(args.row.events.all().length <= 0)
                    args.visible = false;
            } else if(filter.name == "Available") {
                if(args.row.events.all().length)
                    args.visible = false;
            } else if(filter.name == "This_Event") {
                if(args.row.events.all().length) {
                    let events = args.row.events.all();
                    let found = false;;
                    for(let i = 0; i < events.length; ++i) {
                        if(events[i].data.addedResource) {
                            found = true;
                        }
                    }
                    if(!found)
                        args.visible = false;

                } else {
                    args.visible = false;
                }
            } else if(filter.name == "Search") {
                if (args.row.name.toUpperCase().indexOf(filter.value.toUpperCase()) === -1) {
                    args.visible = false;
                }
            }
        }
        }
    }
};
