var agendaTable;
var dayTable;


// Daypilot for week view
function initWeek() {

  if ($('#rooms').val() == 'true') {
    setHeight = 100;
  } else {
    setHeight = 50;
  }

  if(!weekEvents) weekEvents = [];
  var dpWeek = new DayPilot.Scheduler("dpWeek", {
    cellWidthSpec: "Auto",
    autoScroll: "Always",
    timeHeaders: [{"groupBy":"Day"}],
    scale: "Day",
    days: 7,
    startDate: weekStartDate,
    eventHeight: setHeight,
    durationBarVisible: false,
    allowEventOverlap: false,
    eventTextWrappingEnabled: true,
    timeRangeSelectedHandling: "Disabled",
    eventMoveHandling: "Disabled",
    eventClickHandling: "Enabled",
    onEventClicked: function (args) {
      window.location.href = ("href", "/courseInstance/events/event-dashboard/" + args.e.id());
    },
    eventHoverHandling: "Disabled",
    rowHeaderWidth: 0,
    rowHeaderWidthAutoFit: false,
  });

  dpWeek.resources = [
    {name: "Timeslot", id: "TS"},
  ];

  // open array, fix date/time for Daypilot
  for(let i = 0; i < weekEvents.length; ++i) {
    let objWeek = weekEvents[i];
    var startDateTime = objWeek.start.split(' ');
    var startDate = startDateTime[0];
    var startTime = startDateTime[1];
    var endDateTime = objWeek.start.split(' ');
    var endDate = endDateTime[0];
    var endTime = endDateTime[1];

    if (startTime.split(":").length < 3) {
      var start = startTime.split(":");
      start[2] = "00";
      startTime = start.join(":");
    }
    if (endTime.split(":").length < 3) {
      var end = endTime.split(":");
      end[2] = "00";
      endTime = end.join(":");
    }
    objWeek.start = startDate + 'T' + startTime;
    objWeek.end = endDate + 'T' + endTime;
  }

  dpWeek.onBeforeTimeHeaderRender = function(args) {
    args.header.html = "<a href='javascript:void(0);' onclick='gotoDay(\"" + args.header.start.toString("yyyy-M-dd") + "\");'>" + args.header.start.toString("dddd, M/dd/yyyy") + "</a>";
    args.header.toolTip = '';
  };


  dpWeek.cellWidthSpec = 'Auto';
  dpWeek.cellWidthMin = 150;
  dpWeek.events.list = weekEvents;
  dpWeek.init();

};



// Daypilot for month view
function initMonth() {

  if ($('#rooms').val() == 'true') {
    setHeight = 100;
  } else {
    setHeight = 50;
  }

  if(!monthEvents) monthEvents = [];
  var dpMonth = new DayPilot.Month("dpMonth", {
    locale: "en-us",
    viewType: "Month",
    showWeekend: true,
    startDate: inputMonth,
    timeRangeSelectedHandling: "Disabled",
    eventHeight: setHeight,
    width: "100%",
    eventDeleteHandling: "Disabled",
    eventMoveHandling: "Disabled",
    eventResizeHandling: "Disabled",
    eventClickHandling: "Enabled",
    onEventClicked: function (args) {
      window.location.href = ("href", "/courseInstance/events/event-dashboard/" + args.e.id());
    },
    eventHoverHandling: "Disabled",
  });

  // open array, fix date/time for Daypilot
  for(let i = 0; i < monthEvents.length; ++i) {
    let objMonth = monthEvents[i];
    var startDateTime = objMonth.start.split(' ');
    var startDate = startDateTime[0];
    var startTime = startDateTime[1];
    var endDateTime = objMonth.start.split(' ');
    var endDate = endDateTime[0];
    var endTime = endDateTime[1];

    if (startTime.split(":").length < 3) {
      var start = startTime.split(":");
      start[2] = "00";
      startTime = start.join(":");
    }
    if (endTime.split(":").length < 3) {
      var end = endTime.split(":");
      end[2] = "00";
      endTime = end.join(":");
    }
    objMonth.start = startDate + 'T' + startTime;
    objMonth.end = endDate + 'T' + endTime;
  }

  dpMonth.weekStarts = 1; //monday
  dpMonth.onBeforeCellRender = function(args) {
    args.cell.headerHtml = "<a href='javascript:void(0);' onclick='gotoDay(\"" + args.cell.start.toString("yyyy-M-dd") + "\");'>" + args.cell.start.toString("d") + "</a>";
  };

  dpMonth.events.list = monthEvents;
  dpMonth.init();

};



// datatable for agenda view
function initAgenda(url) {

  // set table to dataTable
  agendaTable = $('#agenda-table').DataTable({

    ajax: {
      url: url,
      data: function (d) {
        d.start_date = $('#start-date').val();
        d.end_date = $('#end-date').val();
        d.building = $('#building').val();
        d.status = $('#status_id').val();
        d.resolved = $('#resolved').val();
        d.location = $('select[name="location-agenda"]').val();
        d.search = $('#search').val();
      }
    },

    // move search label to placeholder
    language: {search: "", searchPlaceholder: "Search..."},

    // export buttons
    dom: '<"top"Bf>rt<"bottom"lp><"clear">',

    "lengthMenu": [ [25, 50, -1], [25, 50, "All"] ],  // show #
    buttons: [
      {
        text: $("input[name=display_notes_text]").val(),
        className: 'dt_display_notes',
        action: function ( e, dt, node, config ) {
          //on click display child rows, hide display button, show hide button
          agendaTable.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
          $('.dt_display_notes').hide();
          $('.dt_hide_notes').show();
        }
      },
      {
        text: $("input[name=hide_notes_text]").val(),
        className: 'dt_hide_notes',
        action: function ( e, dt, node, config ) {
          //on click hide child rows, show display button, hide hide button
          agendaTable.rows('.parent').nodes().to$().find('td:first-child').trigger('click');
          $('.dt_display_notes').show();
          $('.dt_hide_notes').hide();
        }
      },
      {
        extend: 'excelHtml5',
        title: 'Agenda - ' + $('#start-date').val() + ' - ' + $('#end-date').val(),
        exportOptions: {
          columns: [ 0, 1, 2, 8, 9 ]
        }
      },
      {
        extend: 'pdf',
        title: 'Agenda - ' + $('#start-date').val() + ' - ' + $('#end-date').val(),
        exportOptions: {
          columns: [ 0, 1, 2, 8, 9 ]
        }
      },
      {
        extend: 'copy',
        title: 'Agenda - ' + $('#start-date').val() + ' - ' + $('#end-date').val(),
        exportOptions: {
          columns: [ 0, 1, 2, 8, 9 ]
        }
      },
    ],

    //this hides the hide notes button on load
    initComplete: function() {
      $(".dt_hide_notes").css("display","none");
    },

    columns: [
      { data: 'building_abbrv', name: 'building_abbrv' },
      { data: 'location_abbrv', name: 'location_abbrv' },
      { data: 'courses.abbrv', name: 'courses.abbrv' },
      { data: 'color', name: 'color', orderable: false, searchable: false },
      { data: 'status', name: 'status', orderable: false, searchable: false },
      { data: 'specialist', name: 'specialist', orderable: false, searchable: false },
      { data: 'special_requirements', name: 'special_requirements', orderable: false, searchable: false },
      { data: 'not_resolved', name: 'not_resolved', orderable: false, searchable: false },
      { data: 'mtg_rm_abbrv', name: 'mtg_rm_abbrv', width: '18%'},
      // We are hiding this for now. -jl 
      // { data: 'event_group', name: 'event_group' },
      { data: 'date', name: 'date' },
      { data: 'date_sort', name: 'date_sort', visible:false}, //only used for sorting
      { data: 'start_time', name: 'start_time', visible:false}, //hidden but do not remove - needed for Search
      { data: 'end_time', name: 'end_time', visible:false }, //hidden but do not remove - needed for Search
      { data: 'actions', name: 'actions', orderable: false, searchable: false, width: '150px'},
      { data: 'notes', name: 'notes' },
    ],

    columnDefs: [
      { targets: 9, orderData: [10]}, //order formatted date/time by start_time field
    ],

  });

};

// datatable for day view
function initDay(url, empty) {

  // set table to dataTable
  dayTable = $('#day-table').DataTable({
    lengthChange: false,
    paging: false,
    info: false,
    ajax: {
      url: url,
      data: function (d) {
        d.start_date = start_date;
        d.location = input_location;
      }
    },

    dom: '<"col-sm-4"B><"pull-right"><r>tip',        // layout of buttons

    //custom buttons to show/hide child rows (in this case notes)
    buttons: [
      {
        text: $("input[name=display_notes_text]").val(),
        className: 'dt_display_notes',
        action: function ( e, dt, node, config ) {
          //on click display child rows, hide display button, show hide button
          dayTable.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
          $('.dt_display_notes').hide();
          $('.dt_hide_notes').show();
        }
      },
      {
        text: $("input[name=hide_notes_text]").val(),
        className: 'dt_hide_notes',
        action: function ( e, dt, node, config ) {
          //on click hide child rows, show display button, hide hide button
          dayTable.rows('.parent').nodes().to$().find('td:first-child').trigger('click');
          $('.dt_display_notes').show();
          $('.dt_hide_notes').hide();
        }
      },
      {
        extend: 'excelHtml5',
        // title: 'Agenda - ' + $('#start-date').val() + ' - ' + $('#end-date').val(),
        exportOptions: {
          columns: [ 0, 9, 10, 11 ]
        }
      },
      {
        extend: 'pdf',
        // title: 'Agenda - ' + $('#start-date').val() + ' - ' + $('#end-date').val(),
        exportOptions: {
          columns: [ 0, 9, 10, 11 ]
        }
      },
      {
        extend: 'copy',
        // title: 'Agenda - ' + $('#start-date').val() + ' - ' + $('#end-date').val(),
        exportOptions: {
          columns: [ 0, 9, 10, 11 ]
        }
      },
    ],

    //this hides the hide notes button on load
    initComplete: function() {
      $(".dt_hide_notes").css("display","none");
    },

    // move search label to placeholder
    language: {search: "", searchPlaceholder: "Search...", emptyTable: empty},

    columns: [
      { data: 'time', name: 'time' },
      { data: 'start_time', name: 'start_time', visible:false },
      { data: 'end_time', name: 'end_time', visible:false },
      { data: 'color', name: 'color', orderable: false, searchable: false },
      { data: 'status', name: 'status', orderable: false, searchable: false },
      { data: 'specialist', name: 'specialist', orderable: false, searchable: false },
      { data: 'special_requirements', name: 'special_requirements', orderable: false, searchable: false },
      { data: 'not_resolved', name: 'not_resolved', orderable: false, searchable: false },
      { data: 'course_name', name: 'course_name', visible:false },
      { data: 'courses.abbrv', name: 'courses.abbrv', "render": function (data, type, full) {
          return '<span data-toggle="tooltip" title="' + full.course_name + '">' + data + '</span>';
        }
      },
      { data: 'building_location', name: 'building_location' },
      { data: 'initial_meeting_room', name: 'initial_meeting_room', "render": function (data, type, full) {
          return '<span data-toggle="tooltip" title="' + full.event_rooms + '">' + data + '</span>';
        }
      },
      { data: 'actions', name: 'actions', orderable: false, searchable: false },
      { data: 'notes', name: 'notes' },
    ],

    columnDefs: [
      { targets: 0, orderData: [1,2]},
    ],

  });

};

function editCourseInstance(courseInstanceID) {
  window.location.href = "/courseInstance/main/editEvent/"+courseInstanceID;
};

function createTemplateFrom(courseInstanceID) {
  window.location.href = "/courseInstance/template/create/"+courseInstanceID;
};

function getCourseInstanceCopyDate(courseInstanceID) {
  // $("#clone").modal("show");
  // $("#cloneInstanceId").val(courseInstanceId);
  window.location.href = "/courseInstance/main/duplicateEvent/"+courseInstanceID;
}

function cloneCourseInstance(event) {
  console.log(event);
}

$(function() {
  // Added Keycode shorcuts similar to Google Cal
  $('body').on('keyup', function(e) {
    // Dont trigger event if in searchbox

    // Month View M key
    if (e.keyCode == 77 && !$('input[type=search]').is(':focus') && !$('input[type=text]').is(':focus')) {
      $('#month-show').trigger('click');
    }

    // Week View W key
    if (e.keyCode == 87 && !$('input[type=search]').is(':focus') && !$('input[type=text]').is(':focus')) {
      $('#week-show').trigger('click');
    }

    // Day View D key
    if (e.keyCode == 68 && !$('input[type=search]').is(':focus') && !$('input[type=text]').is(':focus')) {
      $('#day-show').trigger('click');
    }

    // Agenda View A key
    if (e.keyCode == 65 && !$('input[type=search]').is(':focus') && !$('input[type=text]').is(':focus')) {
      $('#agenda-show').trigger('click');
    }

    // today T key
    if (e.keyCode == 84 && !$('input[type=search]').is(':focus') && !$('input[type=text]').is(':focus')) {
      if ($('#current-view').val() == 'agenda') {
        e.preventDefault()
      }else {
        $('#today').trigger('click');
      }
    }

    // Previous Days J or  left arrow
    if (e.keyCode == 74 || e.keyCode == 37) {
      if (!$('input[type=search]').is(':focus') && !$('input[type=text]').is(':focus')) {
        $('#prev').trigger('click');
      }
    }

    // Future Days k or right arrow
    if (e.keyCode == 75|| e.keyCode == 39) {
      if (!$('input[type=search]').is(':focus') && !$('input[type=text]').is(':focus')) {
        $('#next').trigger('click');
      }
    }
  });
});

// Display help modal to display keyboardshort cuts
// $(document).on('keydown', function(e) {
//   if (e.keyCode == 16) {
//     $(this).on('keyup', function(evt) {
//       if (evt.keyCode == 191) {
//         console.log('Show Help');
//         $('#shortcutHelp').modal('show');
//       }
//     });
//   }
//   return false;
// });
// $('#shortcutHelp').modal('hide');