@extends('backend.layouts.app')

@section ('title', trans('menus.backend.course.title') . ' | ' . trans('menus.backend.courseTemplates.title'))

@section('page-header')
    <h4>
        {{ trans('menus.backend.courseTemplates.title') }}
    </h4>
@endsection

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }} </strong>
        </div>
    @endif

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $course->abbrv }}</h3>
                        <div class="float-right">
                             @include('courses.courseTemplates.partial-header-buttons-sub')
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped dt-responsive nowrap table-hover" id="template-table">
                                <thead>
                                    <tr>
                                        <th>Template Name</th>
                                        <th>Created By</th>
                                        <th>Created Date</th>
                                        <th>Last Edited By</th>
                                        <th>Last Edit Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!--table-responsive-->
                    </div>
                </div>
                {{ Form::hidden('course_id', $course->id) }}
            </div>
        </div>
    </section>
@stop

@section('after-scripts')

    {{ Html::script("js/sweetalert/sweetalert.min.js") }}
    {{ Html::script("/js/moment-with-locales.js") }}
    <script>

        var templates = @json($templates);
        console.log(templates);
        $(function() {
            $('#template-table').DataTable({
                processing: true,
                serverSide: false,
                data: templates,

                columns: [
                    //{ data: 'id', name: 'id' },
                    { data: 'name', name: 'name'},
                    { data: 'creator.full_name', name: 'created_by'},
                    { data: 'created_at', name: 'created_at', render:
			function (data, type, row) {
	                	return moment(data).format('M/D/YYYY h:mm a');
        		},
        	    },
                    { data: 'editor.full_name', name: 'last_edited_by'},
		    { data: 'updated_at', name: 'updated_at', render:
			function (data, type, row) {
	                	return moment(data).format('M/D/YYYY h:mm a');
        		},
		    },

                    // Put in the action buttons on the rightmost column
                    { data: 'action_buttons', name: 'actions', orderable: false, searchable: false, className: "dt-nowrap"},
                ]
            });
        });

        $("body").on("click", "a[name='delete_template']", function(e) {

                e.preventDefault();
                var href = $(this).attr("href");

            var templatename = $(this).data('templatename');

                swal({
                    title: "{{ trans('alerts.backend.templates.delete_template_start') }} " + templatename + " {{ trans('alerts.backend.templates.delete_template_end') }}",
                    icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then(function(isConfirmed) {
                        if (isConfirmed) {
                            window.location.href = href;
                        } else {
                        }
                    });
        })


    </script>
@append
