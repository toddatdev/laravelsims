@extends('frontend.layouts.app')

@section ('title', 'Course Email Template Management')

@section('after-styles')
@endsection

@section('page-header')
    @if(strpos(url()->previous(), 'courseInstanceEmails') == false) {{-- do not reset when returning from credit or edit--}}
        @if (strpos(url()->previous(), 'mycourses') !== false)
            <?php session(['breadcrumbLevel1' => 'mycourses']); ?>
        @elseif (strpos(url()->previous(), '/courses/') !== false)
            <?php session(['breadcrumbLevel1' => 'courses']); ?>
        @endif
    @endif

    <div class="row">
        <div class="col-lg-9">
            <h4>
                <text id="pageHeader"></text>
            </h4>
        </div><!-- /.col -->
        <div class="col-lg-3">
            <ol class="breadcrumb float-sm-right">
                @if (Session::get('breadcrumbLevel1') == 'mycourses')
                    <li class="breadcrumb-item">{{ link_to('/mycourses', trans('navs.frontend.course.my_courses'), ['class' => '']) }}</li>
                @elseif (Session::get('breadcrumbLevel1') == 'courses')
                    <li class="breadcrumb-item">{{ link_to('/courses/active?id='.$course->id, trans('menus.backend.course.title'), ['class' => '']) }}</li>
                @endif
                <li class="breadcrumb-item active">{{ $course->abbrv }}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}
            </strong>
        </div>
    @endif
@endsection

@section('content')
    <section class="content">
        @php($create = true)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $course->name }} ({{ $course->abbrv }})</h3>
                        <div class="float-right">
                            @include('courses.emails.new.partial-header-buttons')
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="emails-table" class="table table-striped dt-responsive nowrap table-hover">
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
            <input type="hidden" id="email_type" value="{{ Session::get('type') }}">
        @endif
    </section>
@endsection

@section('after-scripts')
    <script>
        $(function() {
            var dt;
            let email_type = "{{ app('request')->input('type') }}";
            let course_id = '';
            @if(isset($id))
                course_id = "{{ $id }}";
                localStorage.setItem("course_id", course_id);
            @else
                course_id = localStorage.getItem("course_id");
            @endif
            
            
            if (email_type == '') {
                email_type = $('#email_type').val();
            }

            if (email_type == '2') {
                courseEmails();
            } 
            else if (email_type == '3') {
                eventEmails();
            }else {
                email_type = '2';
                courseEmails(); // default site Emails
            }


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

            function courseEmails() {
                goTo('course', 'Course Email Template Management', '/courses/courseInstanceEmails?type='+email_type);
                $('#pageHeader').text("{{ trans('navs.frontend.course-emails.manage_course') }}");
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
                goTo('event', 'Event Email Template Management', '/courses/courseInstanceEmails?type='+email_type);
                $('#pageHeader').text("{{ trans('navs.frontend.event-emails.manage_event') }}");
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
                $('#create-email').attr('href', '/courses/courseInstanceEmails/create/'+email_type);
                $('#create-email-mobile').attr('href', '/courses/courseInstanceEmails/create/'+email_type);

                dt = $('#emails-table').DataTable({
                    // processing: true, // blocking stupid processing text that pops up
                    serverSide: true,
                    ajax: {
                        url: '{!! url("courseEmailTable.data") !!}',
                        data: function (d) {
                            d.type = email_type;
                            d.course_id = course_id;
                        }
                    },

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
