@section('before-styles')
@endsection

<!-- datatable panel/table for Event Emails -->

<div class="card">
    @php($create = true)
    <div class="card-header">
        <div class="float-right">
            @include('courseInstance.events.emails.partial-header-buttons')
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="emails-table" class="table table-striped dt-responsive nowrap table-hover">
                <thead>
                <tr>
                    <th>{{ trans('labels.siteEmails.email_type') }}</th>
                    <th>{{ trans('labels.siteEmails.label') }}</th>
                    <th>{{ trans('labels.eventEmails.subject') }}</th>
                    <th>{{ trans('labels.general.actions') }}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


{{--mitcks 2020-01-08: the JavaScript that loads the data for the emails-table is located in partial-users.blade.php, see comment there for explanation --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ trans('labels.eventEmails.sent_email') }}</h3>
    </div>
    <div class="card-body">
        <!-- datatable panel/table for Sent Event Emails -->
        <div class="table-responsive">
            <table id="sent-emails-table" class="table table-striped dt-responsive nowrap table-hover">
                <thead>
                    <tr>
                        <th>{{ trans('labels.siteEmails.email_type') }}</th>
                        <th>{{ trans('labels.siteEmails.label') }}</th>
                        <th>{{ trans('labels.eventEmails.subject') }}</th>
                        <th>{{ trans('labels.eventEmails.sent_at') }}</th>
                        <th>{{ trans('labels.eventEmails.to') }}</th>
                        <th>{{ trans('labels.general.actions') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(function() {

        var emailTable;

        eventEmails();

        function eventEmails() {
            $('#heading').text("Event Email Template Management");
            if (!emailTable) {
                init();
            }else {
                emailTable.clear();
                emailTable.destroy();
                emailTable = null;
                init();
            }
        }


        function init() {
            emailTable = $('#emails-table').DataTable({
                serverSide: true,
                ajax: {
                    url: '{!! route('event.emails.table') !!}',
                    data: function (d) {
                        d.event_id = '{{ $event->id }}';
                    }
                },
                language: {search: "", searchPlaceholder: "{!!trans('labels.general.search_placeholder')!!}"},
                lengthMenu: [[5, 10, 25, 50, -1],[5, 10, 25, 50, "All"]],
                responsive: true,
                columns: [
                    { data: "type_name" },
                    { data: "label" },
                    { data: "subject" },
                    { data: "actions", name: "actions", orderable: false, searchable: false },
                ],
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 10001, targets: 2 },
                    { responsivePriority: 2, targets: 3 }
                ],
                ordering: true,
                destroy: true,
                info: true,
                pageLength: 10,
            });
        }

        // email delete item / sweet alert (mitcks 2020-1-8: had to put this here because this page was using a different sweet alert version, Joel made a note, I'm not sure why but leaving as is?)
        $("body").on("click", "a[name='email_delete']", function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            swal({
                title: "{{ trans('labels.siteEmails.delete_wall') }}",
                icon: "warning",
                buttons: true,
                showCancelButton: true,
                dangerMode: true,
                confirmButtonColor: "#DD6B55",
            }).then(function(confirmed) {
                if (confirmed)
                    window.location = href;
            });
        });
        //end of email dataTable section
        //**********************************


        sentEmailTable = $('#sent-emails-table').DataTable({

            ajax: {
                url: '{!! route('event.sent.emails.table') !!}',
                data: function (d) {
                    d.event_id = '{{ $event->id }}';
                }
            },
            language: {
                search: "",
                "searchPlaceholder" : "{!!trans('labels.general.search_placeholder')!!}",
                "emptyTable"        : "{!!trans('labels.eventEmails.no_sent_emails')!!}",
            },

            pageLength: 10,
            lengthMenu: [[10, 25, -1],[10, 25, "All"]],

            responsive: true,

            columns: [
                { data: "type_name"},
                { data: "label"},
                { data: "subject" },
                { data: "created_at"},
                { data: "to" },
                { data: "actions", name: "actions", orderable: false, searchable: false },
            ],

            columnDefs: [
                { visible: false, targets: 0 },  // grouping column hidden
                { responsivePriority: 1, targets: 1 }, //label
                { responsivePriority: 2, targets: 3 }  //created_at
            ],

            rowGroup: {
                dataSrc: 'type_name'
            },

            order: [[0, 'asc'],[3, 'desc'] ],

            ordering: true,

        });

    });
</script>