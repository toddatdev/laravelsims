@extends ('backend.layouts.app')
@section ('title', trans('menus.backend.location.title'))

@section('after-styles')

@stop

@section('page-header')
    <h4>{{ trans('menus.backend.location.title') }}</h4>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.location.view-all') }}</h3>
                        <div class="float-right">
                            @include('locations.partial-header-buttons')
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="locations_table" class="table dt-responsive nowrap table-hover indent_first_child">
                                <thead>
                                <tr>
                                    {{-- The id column will be hidden so it will be available in the downloaded data only. --}}
                                    <th>{{ trans('labels.locations.id') }}</th>
                                    <th>{{ trans('labels.locations.order') }}</th>
                                    <th>{{ trans('labels.locations.building_abbrv') }}</th>
                                    <th>{{ trans('labels.locations.abbrv') }}</th>
                                    <th>{{ trans('labels.locations.name') }}</th>

                                    {{--The following columns are hidden so they only show up in the downloaded data  --}}
                                    <th>{{ trans('labels.locations.more_info') }}</th>
                                    <th>{{ trans('labels.locations.directions_url') }}</th>
                                    <th>{{ trans('labels.locations.display_order') }}</th>
                                    <th>{{ trans('labels.locations.html_color') }}</th>
                                    <th>{{ trans('labels.locations.retire_date') }}</th>
                                    <th>{{ trans('labels.locations.created_at') }}</th>
                                    <th>{{ trans('labels.locations.updated_at') }}</th>
                                    <th>{{ trans('labels.locations.deleted_at') }}</th>
                                    {{-- End of the hidden columns --}}

                                    <th>{{ trans('labels.general.actions') }}</th> {{-- Added from the locationTableController--}}
                                </tr>
                                </thead>
                            </table>
                        </div><!--table-responsive-->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div>
        </div>
    </section>

@stop

@section('after-scripts')

    <script>
        $(function() {
            var table = $('#locations_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! url('locationsall.data') !!}',

                columns: [
                    { data: 'id',             name: 'id', visible: false}, //Hiding the ID column so it will be available in the downloaded data only.
                    { data: 'display_order',  name: 'display_order' },
                    { data: 'building_abbrv', name: 'building_abbrv' },
                    { data: 'abbrv',          name: 'abbrv' },
                    { data: 'name',           name: 'name',
                       // Put the html_color as the background color for the name.
                        "render" : function(data, type, full, meta){
                            if (full.html_color != null)
                            {
                                returnStr = '<span style="color:' +full.html_color+'"><i class="fas fa-circle"></i></span>&nbsp;'+data;
                            }
                            else
                            {
                                returnStr = '<span style="color:transparent"><i class="fas fa-circle"></i></span>&nbsp;'+data;
                            }

                            // check if there is at least one location scheduler for this location, if not warn them
                            returnStr += (full.scheduler_cnt == 0) ? "&nbsp;&nbsp;<a href=/locations/schedulers/"+full.id+" style=color:red;font-style:italic;font-weight:bold>NO SCHEDULER!!</a>" : "";
                            return returnStr;
                       }
                    },

                    //The following columns are hidden so they only show up in the downloaded data
                    { data: 'more_info',      name: 'more_info', visible: false},
                    { data: 'directions_url', name: 'directions_url', visible: false},
                    { data: 'display_order',  name: 'display_order', visible: false},
                    { data: 'html_color',     name: 'html_color', visible: false},
                    { data: 'retire_date',    name: 'retire_date', visible: false},
                    { data: 'created_at',     name: 'created_at', visible: false},
                    { data: 'updated_at',     name: 'updated_at', visible: false},
                    { data: 'deleted_at',     name: 'deleted_at', visible: false},
                    //End of the hidden columns 

                    // Put in the action buttons on the rightmost column
                    { data: 'actions', name: 'actions', orderable: false, searchable: false},
                ],

                //This section groups the data by the Building Name, which is in column 3 (index=2)
                "columnDefs": [
                    { "visible": false, "targets": 2 }  //Make the grouping column invisible. 
                ],
                "order": [[ 2, 'asc' ], [1, 'asc']],   //Set your sort order (first column is index 0)
                "displayLength": 25,

                rowGroup: {
                    dataSrc: [ 'building_abbrv' ],
                },

            }); //$('#foobar_table').DataTable({
                        
        }); //$(function() {

    </script>
@stop
