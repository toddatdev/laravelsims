{{--<p>Loop through and display each non-retired & published QSE for this course</p>--}}

<div class="table-responsive">
    <table id="qse-table" class="table nowrap table-hover" width="100%">
        <thead>
        <tr>
            <th></th> {{--for row expander--}}
            <th>{{ trans('labels.qse.title') }}</th>
            <th>{{ trans('labels.qse.current_status') }}</th>
            <th>{{ trans('labels.qse.default_setting') }}</th>
            <th>{{ trans('labels.qse.change_availability') }}</th>
            <th>{{ trans('labels.qse.completion') }}</th>
            <th>{{ trans('labels.qse.avg_score') }}</th>
        </tr>
        </thead>
    </table>
</div>
<div class="form-group form-check">
    <input type="checkbox" class="form-check-input" id="show-retired-qses">
    <label class="form-check-label" for="show-retired-qses">{{ trans('labels.qse.show_retired_qses') }}</label>
</div>

<script type="text/javascript">

    /* Formatting function for row details - modify as you need */
    function format ( d ) {
        // `d` is the original data object for the row
        return '<table id="subtable-'+d.qse_id+'" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
            '<thead><th>{{ trans('labels.qse.name') }}</th><th>{{ trans('labels.qse.completion') }}</th><th>{{ trans('labels.qse.score') }}</th><th>{{ trans('labels.qse.report') }}</th></thead>'+
            '</table>';
    }

    function sub_DataTable(event_id, qse_id, qse_type_id, table_id) {

        var columns = [
            { data: "name"},
            { data: "status" },
            { data: "firstName", visible:false}, //for search/sort only
            { data: "lastName", visible:false}, //for search/sort only
        ];

        if (qse_type_id === 1) {
            columns = [
                { data: "name"},
                { data: "status" },
                { data: "grade"},
                { data: "report" },
                { data: "firstName", visible:false}, //for search/sort only
                { data: "lastName", visible:false}, //for search/sort only
            ];
        }

        var subtable = $('#'+table_id).DataTable({
            "ajax": {
                "type": "GET",
                url: '{!! route('event.user.qse.table') !!}',
                data: function (d) {
                    d.event_id = '{{ $event->id }}',
                    d.qse_id = qse_id;
                }
            },
            paging: false,
            dom: '<"top">rt<"bottom"><"clear">', //no buttons, no search, no paging
            columns,
            // order: qse_type_id === 1 ? [[3, 'asc'],[2, 'asc']] : [[4, 'asc'],[3, 'asc']], //lastname, firstname
        });
    }

    $(function() {

        function loadDataTable(retired = 0) {

           return $('#qse-table').DataTable({
                serverSide: true,
                ajax: {
                    url: '{!! route('event.qse.table') !!}?retired=' + retired,
                    data: function (d) {
                        d.event_id = '{{ $event->id }}';
                    }
                },

                //language: {search: "", searchPlaceholder: "{!!trans('labels.general.search_placeholder')!!}"},
                //lengthMenu: [[5, 10, 25, 50, -1],[5, 10, 25, 50, "All"]],

                dom: '<"top">rt<"bottom"><"clear">', //no buttons, no search, no paging
                paging: false,
                ordering: false,
                responsive: false, //turning off responsive because using the child row for other details
                columns: [
                    // (mitcks 2021-03-31) Below is from the dataTable example here:https://datatables.net/examples/api/row_details.html
                    // however the images would not display (even with workarounds from comments) so I created my own expand_button
                    // {
                    //     "className":      'details-control',
                    //     "orderable":      false,
                    //     "data":           null,
                    //     "defaultContent": ''
                    // },
                    { data: "expand_button"},
                    { data: "menu_title" },
                    { data: "current_status"},
                    { data: "activation_type"},
                    { data: "change_availability"},
                    { data: "completion"},
                    { data: "avg_score"},
                ],
                columnDefs: [
                    {
                        "targets": ([3,4,5,6]), // centers the availability and activation button columns
                        "className": "text-center",
                    },
                ],

            });
        }

        var qseTable = loadDataTable();

        // Add event listener for opening and closing details
        // $('#qse-table tbody').on('click', 'td.details-control', function () {
        $("body").on("click", "[name='expand_button']", function(e) {

            // alert('clicked');

            var tr = $(this).closest('tr');
            var row = qseTable.row( tr );
            var button = this;
            var qseId = $(this).data('qse_id');
            var qseTypeId = $(this).data('qse_type_id');
            var eventId = {{ $event->id }};

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                $(button).html("<i class='fas fa-plus-circle fa-lg text-success'>");
            } else {
                // Open this row
                //var qse_id = row.data()[0];

                // alert(qseId);
                //var qse_id = row.data('qse_id');
                var subtable_id = "subtable-"+qseId;
                row.child( format(row.data()) ).show();
                $(button).html("<i class='fas fa-minus-circle fa-lg text-primary'>");
                sub_DataTable(eventId, qseId, qseTypeId, subtable_id); /*HERE I was expecting to load data*/
            }
        });

        //required for Ajax functionality
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // ACTIVATE/DEACTIVATE QSE
        $("body").on("click", "[name='activate_qse']", function(e) {

            e.preventDefault();

            //these are set where the mark_attendance button is created in the EventUser model
            var qseId = $(this).data('qse_id');
            var action = $(this).data('action');
            var eventId = {{ $event->id }}

            // need to set variable for the button here outside of ajax so it can be updated inside the ajax success
            var button = this;

            $.ajax({
                url: '{{ url('updateQSEActivation') }}',
                method: 'post',
                data: {
                    qse_id: qseId,
                    event_id: eventId,
                    action: action
                },
                success:function(response){
                    if(response.success){

                        //alert('Success');
                        location.href = response.redirect_url

                    }else{
                        //alert("Error")
                    }
                },
                error:function(error){
                    console.log(error)
                }
            });
        });

        // Add missing QSE to Event Activation Table
        $("body").on("click", "[name='add_qse']", function(e) {

            e.preventDefault();

            //these are set where the mark_attendance button is created in the EventUser model
            //Note: yes it seems odd to use content id here instead of qse_id, but it's not available in dataTable for this case
            var contentId = $(this).data('content_id');
            var eventId = {{ $event->id }};

            console.log ('inside on click: '+contentId+ " "+ eventId);

            // need to set variable for the button here outside of ajax so it can be updated inside the ajax success
            //var button = this;

            $.ajax({
                url: '{{ url('addQSEActivation') }}',
                method: 'post',
                data: {
                    content_id: contentId,
                    event_id: eventId
                },
                success:function(response){
                    if(response.success){

                        //alert('Success');
                        location.href = response.redirect_url

                    }else{
                        //alert("Error")
                    }
                },
                error:function(error){
                    console.log(error)
                }
            });
        });

        $('#show-retired-qses').on('change', function (e) {
            qseTable.clear();
            qseTable.destroy();
            qseTable = loadDataTable(e.target.checked ? 1 : 0)
        });
    });

</script>