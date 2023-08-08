@extends ('backend.layouts.app')

@section ('title', 'Email Template Management')

@section('after-styles')
@endsection

@section('page-header')
{{--    <h1>Email Template Management</h1>--}}
@endsection

@section('content')
    <section class="content">

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}
                </strong>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title" id="heading"></h3>
                        @php($create = true)
                        <div class="float-right">
                            @include('sites.emails.new.partial-header-buttons')
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
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

        @if(Session::has('type'))
            <input type="hidden" id="email_type" value ="{{Session::get('type')}}">
        @endif

    </section>
@endsection

@section('after-scripts')

    <script>
        
        $(function() {       
            var dt;
            let email_type = "{{ app('request')->input('type') }}";

            if (email_type == '') {
                email_type = $('#email_type').val();
            }

            if (email_type == '1') {
                siteEmails();
            } else if (email_type == '2') {
                courseEmails();
            } else if (email_type == '3') {
                eventEmails();
            } else {
                email_type = '1';
                siteEmails(); // default site Emails
            }

            $('#site, #site-mobile').click(function(e) {
                e.preventDefault();
                email_type = '1';
                siteEmails();
            });


            $('#course, #course-mobile').click(function(e) {
                e.preventDefault();
                email_type = '2';
                courseEmails();
            });

            $('#event, #event-mobile').click(function(e) {
                e.preventDefault();
                email_type = '3';
                eventEmails();
            });

            function siteEmails() {
                goTo('site', '{{ trans('menus.backend.siteEmails.create-site') }}', '/admin/site/emails?type='+email_type);
                $('#heading').text("{{ trans('navs.frontend.site-emails.manage_site') }}");
                if (!dt) {
                    init();                        
                }else {
                    dt.clear();
                    dt.destroy();
                    dt = null;
                    init();    
                }
                
            }

            function courseEmails() {
                goTo('course', '{{ trans('menus.backend.siteEmails.create-course') }}', '/admin/site/emails?type='+email_type);
                $('#heading').text("{{ trans('navs.frontend.course-emails.manage_course') }}");
                if (!dt) {
                    init();                        
                }else {
                    dt.clear();
                    dt.destroy();
                    dt = null;
                    init();                      
                }

            }

            function eventEmails() {
                goTo('event', '{{ trans('menus.backend.siteEmails.create-event') }}', '/admin/site/emails?type='+email_type);
                $('#heading').text("{{ trans('navs.frontend.event-emails.manage_event') }}");
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
                $('#create-email').attr('href', 'emails/create/'+email_type);
                $('#create-email-mobile').attr('href', 'emails/create/'+email_type);
                dt = $('#emails-table').DataTable({
                    // processing: true, // blocking stupid processing text that pops up
                    serverSide: true,
                    ajax: {
                        url: '{!! url("emailTable.data") !!}',
                        data: function (d) {
                            d.type = email_type;                           
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

            function goTo(page, title, url) {
                if ("undefined" !== typeof history.pushState) {
                    history.pushState({page: page}, title, url);
                } else {
                    window.location.assign(url);
                }
            }

        });
    </script>
@endsection