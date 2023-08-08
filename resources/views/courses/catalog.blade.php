@extends('frontend.layouts.public')

@section ('title', trans('navs.frontend.course.catalog'))

@section('after-styles')
    <style>
        /*make search box wider*/
        .dataTables_filter input { width: 250px }
    </style>
@stop

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('navs.frontend.course.catalog') }}</h3>
                        <div class="float-right">
                            <div class="form-group form-inline float-right mb-0">
                                {{ Form::label('catalog-filter', trans('labels.courses.filter_category'), ['class' => 'control-label mr-2']) }}
                                {{ Form::select('catalog-filter', [null => trans('labels.courses.all_courses')] + $courseFilter, null, ['class' => 'form-control catalog-filter', 'id' => 'catalog-filter']) }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="courses-table" class="table table-hover">
{{--                                mitcks 02-17-2020 commented out headings for new layout--}}
                                <thead>
                                <tr>
                                    <th>{{ trans('labels.courses.name') }}</th>
                                    <th>{{ trans('labels.courses.abbrv') }}</th>
                                    <th>{{ trans('labels.general.actions') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div><!--table-responsive-->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('after-scripts')

    <script>

        // location dropdown
        $("#catalog-filter").change(function() {

            sort = $('#catalog-filter').val();
            heading = $('#catalog-filter option:selected').text();
            if (!dt) {
                init();
            } else {
                dt.clear();
                dt.destroy();
                dt = null;
                init(sort);
            }
            $("#heading-filter").html(' - ' + heading);
        });

       //loads dataTable on page load
        $(function() {
            init();
        });

        function init(sort) {
            dt = $('#courses-table').DataTable({
                processing: true,
                serverSide: true,

                //mitcks 02-18-2020 this line fixed the problem with the width not staying 100% when filter applied
                autoWidth: false,

                // mitcks 02-17-2020 disabled sorts because column headers hidden
                ordering: false,

                ajax: {
                    url: '{!! url('coursecatalog.data') !!}',
                    data: function (d) {
                        if(sort) d.sort = sort;
                    }
                },

                //remove the external Search label and put it in the search text box as a placeholder
                language: {search: "", searchPlaceholder: "{{ trans('labels.general.search_placeholder') }}"},

                //Set the format for table and surrounding functionality
                dom: '<"col-sm-12"<"col-sm-4"l><"col-sm-8 float-right"f>><r>tip',

                columns: [
                    //these fields are here for search, but not visible
                    {data: 'id', name: 'id', visible: false, searchable: true},
                    {data: 'name', name: 'name', visible: false, searchable: true},
                    {data: 'abbrv', name: 'abbrv', visible: false, searchable: true},
                    {data: 'catalog_description', name: 'catalog_description', visible: false, searchable: true},

                    //this is the text displayed with html
                    {data: 'name',
                        render: function ( data, type, row ) {
                            return '<h5>'+ row.abbrv +'</h5><h6>'+ row.name + '</h6>';
                        }
                    },

                    // mitcks 02-17-2020 commented out old action buttons
                    // { data: 'actions', name: 'actions', orderable: false, searchable: false},

                    //carot image
                    { data: 'actions',
                        render: function ( data, type, row ) {
                            return '<span style="font-size: 2em;"><i class="fas fa-chevron-right"></i></span>';
                        }
                    },
                ]
            });

            //this unbinds the event before reassigning, it fixed the issue with click not working after filter
            $('#courses-table tbody').off('click');

            var table = $('#courses-table').DataTable();

            $('#courses-table tbody').on('click', 'tr', function () {

                //console.log( table.row( this ).data() );
                //console.log( table.row( this ).data().id );
                //console.log( table.row( this ).index());

                //to make the entire row clickable to course landing page
                var course_id =  table.row( this ).data().id;
                window.location = '/courses/catalogShow/' + course_id;

            } );

        }


    </script>
@stop