@extends ('backend.layouts.app')

@section ('title', trans('menus.backend.course.title'))

@section('after-styles')
@stop

@section('page-header')
    <h4>{{ trans('menus.backend.course.title') }}</h4>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.course.view-inactive') }}</h3>
                        <div class="float-right">
                            @include('courses.partial-header-buttons')
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="courses-table" class="table table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>{{ trans('labels.courses.abbrv') }}</th>
                                    <th>{{ trans('labels.courses.name') }}</th>

                                    {{--The following columns are hidden so they only show up in the downloaded data  --}}
                                    <th>{{ trans('labels.courses.retire_date') }}</th>
                                    <th>{{ trans('labels.courses.catalog_description') }}</th>
                                    <th>{{ trans('labels.courses.author_name') }}</th>
                                    <th>{{ trans('labels.courses.virtual') }}</th>
                                    <th>{{ trans('labels.courses.created_by') }}</th>
                                    <th>{{ trans('labels.courses.created_at') }}</th>
                                    <th>{{ trans('labels.courses.updated_at') }}</th>
                                    {{-- End of the hidden columns --}}

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
@stop

@section('after-scripts')

    <script>
        $(function() {
            $('#courses-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! url('coursesinactive.data') !!}',

                columns: [
                    { data: 'abbrv', name: 'abbrv' },
                    { data: 'name', name: 'name'},

                    //The following columns are hidden so they only show up in the downloaded data
                    { data: 'retire_date',          name: 'retire_date',            visible:false},
                    { data: 'catalog_description',  name: 'catalog_description',    visible:false},
                    { data: 'author_name',          name: 'author_name',            visible:false},
                    { data: 'virtual',              name: 'virtual',                visible:false},
                    { data: 'created_by',           name: 'created_by',             visible:false},
                    { data: 'created_at',           name: 'created_at',             visible:false},
                    { data: 'updated_at',           name: 'updated_at',             visible:false},
                    //End of the hidden columns

                    // Put in the action buttons on the rightmost column
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: "dt-nowrap"},
                ],

                order: [[0, 'asc']]

            });
        });
    </script>
@stop