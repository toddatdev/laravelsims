@extends ('backend.layouts.app')
@section ('title', trans('menus.backend.building.title'))

@section('after-styles')

@stop

@section('page-header')
    <h4>{{ trans('menus.backend.building.title') }}</h4>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.building.view-all') }}</h3>
                        <div class="float-right">
                            @include('buildings.partial-header-buttons')
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="buildings_table" class="table table-striped dt-responsive nowrap table-hover">
                                {{-- <table id="buildings-table" class="table table-condensed table-hover"> --}}
                                <thead>
                                <tr>
                                    <th>{{ trans('labels.buildings.id') }}</th> {{-- The id column will be hidden so it will be available in the downloaded data only. --}}
                                    <th data-priority="1">{{ trans('labels.buildings.abbrv') }}</th>
                                    <th>{{ trans('labels.buildings.order') }}</th>
                                    <th>{{ trans('labels.buildings.name') }}</th>

                                    {{--The following columns are hidden so they only show up in the downloaded data  --}}
                                    <th>{{ trans('labels.buildings.street_address') }}</th>
                                    <th>{{ trans('labels.buildings.city') }}</th>
                                    <th>{{ trans('labels.buildings.state') }}</th>
                                    <th>{{ trans('labels.buildings.postal_code') }}</th>
                                    <th>{{ trans('labels.buildings.map_url') }}</th>
                                    <th>{{ trans('labels.buildings.more_info') }}</th>
                                    <th>{{ trans('labels.buildings.retire_date') }}</th>
                                    <th>{{ trans('labels.buildings.timezone') }}</th>
                                    {{-- End of the hidden columns --}}

                                    <th data-priority="3">{{ trans('labels.general.actions') }}</th>
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
            $('#buildings_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! url('buildingsall.data') !!}',

                columns: [
                    { data: 'id',            name: 'id', visible: false}, //Hiding the ID column so it will be available in the downloaded data only.
                    { data: 'abbrv',         name: 'abbrv' },
                    { data: 'display_order', name: 'display_order' },
                    { data: 'name',          name: 'name',
                        // Creates a link of the building name which links to the show page with the building ID
                        "render" : function(data, type, full, meta){
                            returnStr = "";
                            url = '<a href="/buildings/show/' +full.id+'">'+data+'</a>';
                            return (full.retire_date !== null) ? url+'&nbsp;<span style="color:red;font-style:italic">(Retired)</span>' : url;
                        }
                    },
                    
                    //The following columns are hidden so they only show up in the downloaded data
                    { data: 'address',       name: 'address',     visible:false},
                    { data: 'city',          name: 'city',        visible:false},
                    { data: 'state',         name: 'state',       visible:false},
                    { data: 'postal_code',   name: 'postal_code', visible:false},
                    { data: 'map_url',       name: 'map_url',     visible:false},
                    { data: 'more_info',     name: 'more_info',   visible:false},
                    { data: 'retire_date',   name: 'retire_date', visible:false},
                    { data: 'timezone',      name: 'timezone',    visible:false},
                    //End of the hidden columns 

                    // Put in the action buttons on the rightmost column
                    { data: 'actions', name: 'actions', orderable: false, searchable: false},
                ],

            }); //$('#foobar_table').DataTable({
        }); //$(function() {

    </script>
@stop
