@extends ('backend.layouts.app')

@section ('title', trans('menus.backend.resource.title'))

@section('after-styles')
@stop

@section('page-header')
    <h4>{{ trans('menus.backend.resource.title') }}</h4>
@endsection

@section('content')

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.resource.view-inactive') }}</h3>
                        <div class="float-right">
                            @include('resources.partial-header-buttons')
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="resources-table" class="table table-condensed table-hover indent_first_child_2">
                                <thead>
                                <tr>
                                    <th>{{ trans('labels.resources.location') }}</th>
                                    <th>{{ trans('labels.resources.type') }}</th>
                                    <th>{{ trans('labels.resources.category') }}</th>
                                    <th>{{ trans('labels.resources.subcategory') }}</th>
                                    <th>{{ trans('labels.resources.abbrv') }}</th>
                                    <th>{{ trans('labels.resources.description') }}</th>

                                    {{--The following columns are hidden so they only show up in the downloaded data  --}}
                                    <th>{{ trans('labels.resources.id') }}</th>
                                    <th>{{ trans('labels.resources.retire_date') }}</th>
                                    <th>{{ trans('labels.resources.created_by') }}</th>
                                    <th>{{ trans('labels.resources.last_edited_by') }}</th>
                                    <th>{{ trans('labels.resources.created_at') }}</th>
                                    <th>{{ trans('labels.resources.updated_at') }}</th>
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
            $('#resources-table').DataTable({

                ajax: {
                    url: '{!! url('resourcesinactive.data') !!}',
                    data: function (d) {
                    }
                },

                columns: [
                    { data: 'building_location',        name: 'building_location'},
                    { data: 'type',                     name: 'type'},
                    { data: 'category_abbrv',           name: 'category_abbrv'},
                    { data: 'sub_category_abbrv',       name: 'sub_category_abbrv'},
                    { data: 'abbrv',                    name: 'abbrv' },
                    { data: 'description',              name: 'description'},
                    //The following columns are hidden so they only show up in the downloaded data
                    { data: 'id',                       name: 'id',                 visible:false},
                    { data: 'retire_date',              name: 'retire_date',        visible:false},
                    { data: 'created_by',               name: 'created_by',         visible:false},
                    { data: 'last_edited_by',           name: 'last_edited_by',     visible:false},
                    { data: 'created_at',               name: 'created_at',         visible:false},
                    { data: 'updated_at',               name: 'updated_at',         visible:false},
                    //End of the hidden columns

                    // Put in the action buttons on the rightmost column
                    { data: 'actions', name: 'actions', orderable: false, searchable: false},
                ],

                //This section hides the two grouping columns
                "columnDefs": [
                    { "visible": false, "targets": [0,1] }  //Make the grouping column invisible.
                ],
                order: [[ 0, 'asc' ], [1, 'asc'], [2, 'asc'], [3, 'asc'], [4, 'asc']],   //Set your sort order (first column is index 0)
                displayLength: 25,

                //this selects which column to use for grouping, works best if this is the first column (position 0)
                rowGroup: {
                    dataSrc: [ 'building_location', 'type' ],
                },

            });
        });
    </script>
@stop