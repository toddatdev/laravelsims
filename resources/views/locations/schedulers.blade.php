@extends('backend.layouts.app')
@section ('title', trans('menus.backend.location.schedulers'))

@section('before-styles')
@endsection

@section('page-header')
    <h4>{{ trans('menus.backend.location.schedulers') }}</h4>
@endsection

@section('content')

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">@if($location->html_color != null)<span style="color:{{ $location->html_color }}"><i class="fas fa-circle"></i></span>&nbsp;@endif{{ $location->name }}</h4>
                        <div class="float-right">
                            @include('locations.partial-header-buttons')
                        </div>
                    </div>
                    <div class="card-body">
                        <h5>{{ trans('labels.locations.current_schedulers') }}</h5>

                        <table class="table table-sm table-hover">
                            @foreach ($schedulers as $scheduler)
                            <tr style='padding:20px;'>
                                <td><a href="/locations/viewscheduler/{{ $scheduler->id }}">{{ $scheduler->last_name }}, {{ $scheduler->first_name }} ({{ $scheduler->email }})</a></td>
                                <td><a href="/locations/removescheduler/{{ $location->id }}/{{ $scheduler->id }}" class="btn-sm btn-danger">
                                    <i class="fa fa-trash fa-fw" data-toggle="tooltip" data-placement="top" title="{{trans('buttons.general.crud.delete') }}"></i></a></td>
                            </tr>
                            @endforeach
                        </table>

                        @if(count($schedulers) == 0)
                           {{-- Let the user know that there are no schedulers.  --}}
                            <div class="container-fluid">
                               <div class="text-center"><span class="btn-sm btn-danger">{{trans('labels.scheduling.no_schedulers', ['location' => $location->name]) }} </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">

                        <h5>{{ trans('labels.locations.add_schedulers') }}</h5>
                        <div>{{ trans('labels.scheduling.only_schedulers') }}</div>

                        <div class="table-responsive">
                            <table id="users_table" class="table table-condensed table-hover">
                            {{-- <table id="locations-table" class="table table-condensed table-hover"> --}}
                                <thead>
                                <tr>
                                    {{-- The id column will be hidden so it will be available in the downloaded data only. --}}
                                    <th>{{ trans('labels.backend.access.users.tabs.content.overview.name') }}</th>
                                    <th>{{ trans('Add') }}</th>

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
            var table = $('#users_table').DataTable({
                processing: true,
                serverSide: true,
                // ajax: 
                ajax: '/availableschedulers.data/{{ $location->id }}',

                //remove the external Search label and put it in the search text box as a placeholder
                language: {search: "", searchPlaceholder: "Search..."},

                //Add an "All" to the "Show entries" pulldown menu
                "lengthMenu": [ [5, 10, 25, -1], [5, 10, 25, "All"] ],

                // set the format for table and surrounding functionality
                dom: '<"top"f>rt<"bottom"lp><"clear">',

                columns: [
                    { data: 'last_name',            name: 'last_name',
                       // Put together the link to the users schedule page and the add button.
                        "render" : function(data, type, full, meta){
                            return '<a href="/locations/viewscheduler/' +full.id+'">'+full.last_name+', '+full.first_name+' ('+full.email+')</a>';
                            }
                    },
                ],
                "columnDefs" : [ {
                    "targets" : 1,
                    "data"    : "id",
                    "render"  : function (data, type, row, meta) {
                        return '<a href=/locations/addscheduler/{{ $location->id }}/' +data+ ' class="btn-sm btn-success"><i class="fa fa-plus fa-fw" data-toggle="tooltip" data-placement="top" title="{{trans('buttons.general.crud.add') }}"></i></a>'
                    }
                }],


                "order": [0, 'asc'],   //Set your sort order (first column is index 0)
                "displayLength": 10,
            }); //$('#users_table').DataTable({

        }); //$(function() {

    </script>
@stop