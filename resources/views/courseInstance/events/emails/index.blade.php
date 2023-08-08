@extends('frontend.layouts.app')

@section ('title', trans('labels.eventEmails.template'))

@section('after-styles')
@endsection

@section('page-header')
    <h1>{{ trans('labels.eventEmails.emails') }}</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>{{ trans('labels.eventEmails.template') }}</h3>
                    {{--<h4>{{ $details }}</h4>--}}
                    @php($create = true)
                    <div class="panel-tools">
                        @include('courseInstance.events.emails.partial-header-buttons')
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="emails-table" class="table table-condensed table-hover">
                            <thead>
                                <tr>
                                    <th>{{ trans('labels.siteEmails.label') }}</th>
                                    <th>{{ trans('labels.siteEmails.email_type') }}</th>
                                    <th>{{ trans('labels.general.actions') }}</th> 
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div> 
    </div>
@endsection

@section('after-scripts')

    <script>
        $(function() {     
            var dt;

            eventEmails();

            function eventEmails() {
                $('#heading').text("Event Email Template Management");
                if (!dt) {
                    init();                        
                }else {
                    dt.clear();
                    dt.destroy();
                    dt = null;
                    init();                                          
                }
            }

        
            function init() {
                dt = $('#emails-table').DataTable({
                    serverSide: true,
                    ajax: {
                        url: '{!! route('event.emails.table') !!}',
                        data: function (d) {
                            d.event_id = '{{ $event->id }}';
                        }
                    },
                    language: {search: "", searchPlaceholder: "Search..."},
                    "lengthMenu": [[5, 10, 25, 50, -1],[5, 10, 25, 50, "All"]],
                    columns: [
                        { data: "label" },
                        { data: "name" },
                        { data: "actions", name: "actions", orderable: false, searchable: false }, 
                    ],
                    ordering: true,
                    destroy: true,
                    info: true,
                    pageLength: 10,
                });
            }
            
        });
    </script>    
@endsection