if (!Modernizr.inputtypes.date) {
    $('input[type=date]').datepicker({
        // Consistent format with the HTML5 picker
        dateFormat: 'yy-mm-dd'
    });
}

if (!Modernizr.inputtypes.time) {
    $('input[type=time]').timepicker({
        controlType: 'select',
        currentText: 'Now',
        closeText: 'Done',
        hourMax: 23,
        amNames: ['AM', 'A'],
        pmNames: ['PM', 'P'],
        timeFormat: 'HH:mm',
        timeSuffix: '',
        timeOnlyTitle: 'Choose Time',
        timeText: 'Time',
        hourText: 'Hour',
        minuteText: 'Minute',
        secondText: 'Second',
        millisecText: 'Millisecond',
        microsecText: 'Microsecond',
        timezoneText: 'Time Zone',
        isRTL: false
    });
}