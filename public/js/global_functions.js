'use_strict'
function componentToHex(c) {
    var hex = c.toString(16);
    return hex.length == 1 ? "0" + hex : hex;
}

function rgbToHex(r, g, b) {
    return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

function lightenDarkenColor(col, amt) {
  
    var usePound = false;
  
    if (col[0] == "#") {
        col = col.slice(1);
        usePound = true;
    }
 
    var num = parseInt(col,16);
 
    var r = (num >> 16) + amt;
 
    if (r > 255) r = 255;
    else if  (r < 0) r = 0;
 
    var b = ((num >> 8) & 0x00FF) + amt;
 
    if (b > 255) b = 255;
    else if  (b < 0) b = 0;
 
    var g = (num & 0x0000FF) + amt;
 
    if (g > 255) g = 255;
    else if (g < 0) g = 0;
 
    return (usePound?"#":"") + (g | (b << 8) | (r << 16)).toString(16);
}

function invertColor(hex) {
    if (hex.indexOf('#') === 0) {
        hex = hex.slice(1);
    }
    // convert 3-digit hex to 6-digits.
    if (hex.length === 3) {
        hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    }
    if (hex.length !== 6) {
        throw new Error('Invalid HEX color.');
    }
    // invert color components
    var r = (255 - parseInt(hex.slice(0, 2), 16)).toString(16),
        g = (255 - parseInt(hex.slice(2, 4), 16)).toString(16),
        b = (255 - parseInt(hex.slice(4, 6), 16)).toString(16);
    // pad each with zeros and return
    return '#' + padZero(r) + padZero(g) + padZero(b);
}

function padZero(str, len) {
    len = len || 2;
    var zeros = new Array(len).join('0');
    return (zeros + str).slice(-len);
}

function barColor(i) {
    var colors = ["#3c78d8", "#6aa84f", "#f1c232", "#cc0000"];
    return colors[i % 4];
}

function barBackColor(i) {
    var colors = ["#a4c2f4", "#b6d7a8", "#ffe599", "#ea9999"];
    return colors[i % 4];
}


function removeEvent(resource_id) {
    if(roDayPilot)
        return;

    dp.events.find(function(ev) {
        if(ev.data.resource == resource_id && ev.data.addedResource) {
            deleteHiddenElements(ev.data.id);
            dp.events.remove(ev);
        }
    });
}
function isResourceBusy(resource_id, start, end) {
    let row = dp.rows.find(resource_id);
    if(row)
        return row.events.all().length;
    else
        return 0;
}
var myNode = false;
function getNextFreeCategory(resource_id) {
    let row = dp.rows.find(resource_id);
    let category = '';
    let resource = {};
    for(let i = 0; i < resources.length; ++i) {
        if(resources[i].id == resource_id) {
            category = resources[i].category;
            resource = resources[i];
        }
    }

    console.log("Searching "+category.abbrv+" category for next available resource");
    let lastResource = {};
    for(let i = 0; i < resources.length; ++i) {
        if(resources[i].category.id == resource.resource_category_id) {
            rsc = resources[i];
            row = dp.rows.find(rsc.id);
            lastResource = resources[i];
            if(!row || row.events.all().length == 0) {
                console.log("Found: "+rsc.id);
                return rsc.id;
            }
        }
    }
    console.log("None Found");
    return resource_id;
    //return lastResource.id;
}
function getNextFreeSubCategory(resource_id) {
    let row = dp.rows.find(resource_id);
    let subcategory = '';
    let resource = {};
    let lastResource = {};
    for(let i = 0; i < resources.length; ++i) {
        if(resources[i].id == resource_id) {
            subcategory = resources[i].subcategory;
            resource = resources[i];
        }
    }

    console.log("Searching "+subcategory.abbrv+" subcategory for next available resource");
    for(let i = 0; i < resources.length; ++i) {
        if(resources[i].subcategory.id == resource.resource_subcategory_id) {
            rsc = resources[i];
            row = dp.rows.find(rsc.id);
            lastResource = resources[i];
            if(!row || row.events.all().length == 0) {
                console.log("Found: "+rsc.id);
                return rsc.id;
            }
        }
    }
    console.log("None Found");
    return resource_id;
    //return lastResource.id;
}
/**
 * addEvent
 * @param {*} params
 */
function addEvent(params, shouldClear) {
    let guid = DayPilot.guid();
    let cssClass = params.cssClass || '';
    let backColor = params.backColor || '';
    let readonly = params.readOnly || false;
    let moveDisabled = params.moveDisabled || false;
    let resizeDisabled = params.resizeDisabled || false;

    var e = new DayPilot.Event({
        id: guid,
        start: new DayPilot.Date(params.start).addMinutes(-Math.abs(params.setup)),
        end: new DayPilot.Date(params.end).addMinutes(Math.abs(params.teardown)),
        resource: params.resource,
        text: params.eventName,
        barColor: ($("#html_color").val().length ? $("#html_color").val() : "rgb(16, 102, 168)"),
        readonly: readonly,
        moveDisabled: moveDisabled,
        resizeDisabled: resizeDisabled,
        addedResource: true,
        setupTime: params.setup,
        teardownTime: params.teardown,
        cssClass: cssClass,
        backColor: backColor,
        areas:params.area
    });

    if (dp) {
        dp.events.add(e);
        try {
        createHiddenElements(guid, params.resource, e.data.start.value, e.data.end.value, params.setup, params.teardown);
        }catch(err) {}
        dp.update();
    }
}
/**
 * calculateDPSeparators
 * @param {*} params
 */
function calculateDPSeparators(params) {
    let start_time = params.selectedStartTime;
    let end_time = params.selectedEndTime;
    if (params.selectedStartTime.split(":").length < 3) {
        const start = params.selectedStartTime.split(":");
        start[2] = "00";
        start_time = start.join(":");
    }
    if (params.selectedEndTime.split(":").length < 3) {
        const end = params.selectedEndTime.split(":");
        end[2] = "00";
        end_time = end.join(":");
    }

    const separators = [
        {
            color: "Red",
            location: "" + selectedDate + start_time + ""
        }, {
            color: "Red",
            location: "" + selectedDate + end_time + ""
        }
    ];

    if (params.showBlueLine && params.setupTime) {
        separators.push({
            color: "Blue",
            location: "" + params.setupTime + ""
        });
    } if (params.showBlueLine && params.teardownTime) {
        separators.push({
            color: "Blue",
            location: "" + params.teardownTime + ""
        });
    }
    return separators;
}

function initSpectrum(element) {
    element.spectrum({
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

}

function initDayPilotDayView() {
    if(typeof roDayPilot == "undefined") 
        roDayPilot = true;

    //date and time variables for separators
    var d = new Date();
    var month = d.getMonth() + 1;
    var day = d.getDate();

    if(typeof DPStartDate == 'undefined') {
        selectedDate = d.getFullYear() + '-' +
            (('' + month).length < 2 ? '0' : '') + month + '-' +
            (('' + day).length < 2 ? '0' : '') + day;
    }

    var newEventStartTime, newEventEndTime, newEventResourceId, newEventName, newEventCourseId, newEventDate;
    var selectedRangeArgs = {};

    //DayPilot
    dp.startDate = new DayPilot.Date(selectedDate);
    dp.treeEnabled = true;
    dp.treePreventParentUsage = true; //makes location headers non-selectable
    //dp.treeImageHeight = 0;
    //dp.treeImageWidth = 0;

    //highlights row and column position in grid (options are: Header, Full and Disabled)
    dp.crosshairType = "Full";
    dp.autoScroll = "Disabled";
    //The line below scroll to current time (only available in DayPilot Pro)
    
    dp.scrollX = 550;
    //dp.scrollTo("{{ \Carbon\Carbon::now()->toDateString() }}T{{ $businessBeginHour }}:00:00")

    //this shades non business hours
    dp.businessBeginsHour = businessBeginHour;
    dp.businessEndsHour = businessEndHour;
    //dp.showNonBusiness = false;

    //Adds date and hour headers
    dp.timeHeaders = [{
        groupBy: "Day", format: "dddd MMMM d, yyyy"
    },
    {
        groupBy: "Hour"
    }
    ];
    
    //15 minutes increments
    dp.scale = "CellDuration";
    dp.cellDuration = 15;

    dp.cellWidthSpec = 'Auto';
    dp.cellWidthMin = 20;

    if(!roDayPilot) {
        dp.separators = [{
            color: "Red",
            location: "" + selectedDate + selectedStartTime + ""
        },
        {
            color: "Red",
            location: "" + selectedDate + endTime + ""
        }];
    }

    dp.eventStackingLineHeight = 50;
    dp.eventArrangement = "SideBySide";
    dp.allowEventOverlap = true;

    if(!roDayPilot) {
        dp.contextMenu = new DayPilot.Menu( {
        items: [
            {text:"Delete", onclick: function() {
                let e = this.source; 
                if(e.data.resource == initialMeetingRoom && e.data.addedResource) {
                    dp.message("You cannot delete the initial meeting room");
                } else if(e.data.readonly && !e.data.addedResource) {
                    dp.message("You cannot delete a resource from another course");
                } else {
                    deleteHiddenElements(e.data.id);
                    dp.events.remove(e); 
                    dp.update();
                    let resource = getLocationOrResourceById(e.data.resource);
                    dp.message("Removed resource from course: "+e.data.text);
                }
            }},
            {text:"Edit", onclick: function() {
                console.log(this.source);
                let e = this.source; 
                $('<input>', {type: 'hidden', name: 'resource_id', value: e.data.id}).appendTo('#editResourceForm');
                $('input[name="resourceEditSetupTime"]').val(getHiddenElementValue(e.data.id, 'setup'));
                $('input[name="resourceEditTeardownTime"]').val(getHiddenElementValue(e.data.id, 'teardown'));
                $('#resourceEditResourceName').text(e.data.text);
                $('#resourceEditStart').val(e.data.start.value);
                $('#resourceEditEnd').val(e.data.end.value);
                $('#resourceModal').modal('show')
            }}
        ],
            cssClassPrefix: "menu_default"
        });
    }

    //load row headers
    dp.resources = locationsAndResources;
    dp.timeRangeSelectedHandling = "CallBack";
    dp.init();

}

function getEventsAndResourcesByDate(selectedDate, callback) {
    console.log("Gathering resources for "+selectedDate);
    //changeAddedResourceDates(selectedDate);
    var callback = callback || {};
    if(typeof callback == 'function')
        console.log("Callback function will trigger");
    else
        console.log("No callback function defined");

    removeExistingEventResources();
        $.get('/courseInstance/getEventsAndResourcesByDate/'+selectedDate, function (data) {
            courses = data;
            console.log("Found "+data.length+" course instances");

            if(addedResources.length) {
                for(let i = 0; i < addedResources.length; ++i) {
                    if(isResourceAlreadyAdded(addedResources[i].data.backend_id)) {
                        let thisResource = addedResources[i];
                        let startTime = thisResource.data.start.value.split("T");
                            let endTime = thisResource.data.end.value.split("T");
                        editHiddenElement(thisResource.data.id, 'start', selectedDate+"T"+startTime[1]);
                        editHiddenElement(thisResource.data.id, 'end', selectedDate+"T"+endTime[1]);
                        thisResource.data.start = new DayPilot.Date(selectedDate+"T"+startTime[1]);
                        thisResource.data.end = new DayPilot.Date(selectedDate+"T"+endTime[1]);
                    
                        let start = new DayPilot.Date(thisResource.data.start.value).addMinutes(Math.abs(thisResource.data.setupTime));
                        let end = new DayPilot.Date(thisResource.data.end.value).addMinutes(-Math.abs(thisResource.data.teardownTime));
                        let backColor = (thisResource.data.barColor ? thisResource.data.barColor : "rgb(16, 102, 168)");
                        let area = drawResourceColors(start, end, thisResource.data.setupTime, thisResource.data.teardownTime, backColor);
                        thisResource.data.areas = area;

                        dp.events.add(thisResource);

                    }
                }
            }

	    deleteAllHiddenElements();
            populateExistingResources();
            populateExistingEventResources();
            if(pageFilters.length)
                dp.rows.filter(pageFilters[0]);

            if(typeof callback == 'function') {
                callback();
            }
        })
}

Date.prototype.addHours= function(h){
    this.setHours(this.getHours()+h);
    return this;
}

function populateExistingResources() {
        existingEvents = [];
        for(var i = 0; i < courses.length; ++i) {
            let thisCourse = courses[i];
            for(var j = 0; j < thisCourse.course_instances.length; ++j) {
                let thisInstance = thisCourse.course_instances[j];
                for(var k = 0; k < thisInstance.events.length; ++k) {
                    let thisEvent = thisInstance.events[k];
                    thisEvent.courseAbbrv = thisCourse.abbrv;

                    for(var l = 0; l < thisCourse.course_option.length; ++l) {
                        let thisOption = thisCourse.course_option[l];
                        if(thisOption.option_id == 7) {
                            thisEvent.color = thisOption.value;
                        }
                    }

                    existingEvents.push(thisEvent);
                }
            }
        }
}

function removeExistingEventResources() {
    dp.events.list = [];
    dp.update();
}

function drawResourceColors(myStartTime, myEndTime, mySetupTime, myTearDownTime, myColor) {
    let area = [{start:0,  end: 0, top: 0, bottom: 0},{start:0,  end: 0, top: 0, bottom: 0}];
    let res = '00000f';
    let colorArr = [16,102,168];
    if(myColor)
        colorArr = myColor.match(/\d+/g).map(Number);

    res = rgbToHex(colorArr[0], colorArr[1], colorArr[2]);

    let myStart = new DayPilot.Date(myStartTime).addMinutes(-Math.abs(mySetupTime));
    let myEnd = new DayPilot.Date(myEndTime).addMinutes(Math.abs(myTearDownTime));

    if(mySetupTime != '0'){
        area[0].start = myStart;
        area[0].end = myStart.addMinutes(mySetupTime);
        //area[0].backColor  =  lightenDarkenColor(res, 50);
        area[0].style = "height:4px;background: repeating-linear-gradient(45deg, "+res+", "+res+", 2.5px, "+invertColor(res)+" 2.5px, "+invertColor(res)+" 5px);";
        area[0].style += "-ms-repeating-linear-gradient(45deg, "+res+", "+res+", 2.5px, "+invertColor(res)+" 2.5px, "+invertColor(res)+" 5px);";
        area[0].style += "-o-repeating-linear-gradient(45deg, "+res+", "+res+", 2.5px, "+invertColor(res)+" 2.5px, "+invertColor(res)+" 5px);";
        area[0].style += "-moz-repeating-linear-gradient(45deg, "+res+", "+res+", 2.5px, "+invertColor(res)+" 2.5px, "+invertColor(res)+" 5px);";
        area[0].style += "-webkit-repeating-linear-gradient(45deg, "+res+", "+res+", 2.5px, "+invertColor(res)+" 2.5px, "+invertColor(res)+" 5px);";
    }
    if (myTearDownTime != '0'){
        area[1].start = myEnd.addMinutes(-Math.abs(myTearDownTime));
        area[1].end = myEnd;
        area[1].style = "height:4px;background: repeating-linear-gradient(45deg, "+res+", "+res+", 2.5px, "+invertColor(res)+" 2.5px, "+invertColor(res)+" 5px);";
        area[1].style += "-ms-repeating-linear-gradient(45deg, "+res+", "+res+", 2.5px, "+invertColor(res)+" 2.5px, "+invertColor(res)+" 5px);";
        area[1].style += "-o-repeating-linear-gradient(45deg, "+res+", "+res+", 2.5px, "+invertColor(res)+" 2.5px, "+invertColor(res)+" 5px);";
        area[1].style += "-moz-repeating-linear-gradient(45deg, "+res+", "+res+", 2.5px, "+invertColor(res)+" 2.5px, "+invertColor(res)+" 5px);";
        area[1].style += "-webkit-repeating-linear-gradient(45deg, "+res+", "+res+", 2.5px, "+invertColor(res)+" 2.5px, "+invertColor(res)+" 5px);";
    }
    return area;
}
function getResourceById(id) {
    let ret = {};
    for(let i = 0; i < resources.length; ++i) {
        if(resources[i].id == id) {
            ret = resources[i];
            return ret;
        }
    }
    return ret;
}
/**
 * dateHandler
 * @param
 */
function dateHandler(e) {
    console.log("New date selected");
    selectedDate = e.target.value;
    let timeStamp = new Date(selectedDate)
    if(isNaN(timeStamp) == false) {
        dp.startDate = selectedDate;
        getEventsAndResourcesByDate(selectedDate, function() {
            if(!roDayPilot)
                handleVerticalLines(); 

            if(typeof dateHuntCallback == 'function')
                dateHuntCallback(selectedDate);
        });
    }
}

