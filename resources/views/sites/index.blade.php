@extends ('backend.layouts.app')

@section ('title', trans('menus.backend.site.title'))

@section('after-styles')
@stop

@section('page-header')
    <h4>{{ trans('menus.backend.site.title') }}</h4>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.site.view-all') }}</h3>
                        <div class="float-right">
                            @include('sites.partial-header-buttons')
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="sites-table" class="table table-striped dt-responsive nowrap">
                                <thead>
                                <tr>
                                    <th data-priority="0">{{ trans('labels.sites.id') }}</th>
                                    <th data-priority="1">{{ trans('labels.sites.abbrv') }}</th>
                                    <th data-priority="3">{{ trans('labels.sites.organization_name') }}</th>
                                    <th data-priority="4">{{ trans('labels.sites.email') }}</th>
                                    <th data-priority="2">{{ trans('labels.general.actions') }}</th>
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
            $('#sites-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! url('sitetables.data') !!}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'abbrv', name: 'abbrv' },
                    { data: 'organization_name', name: 'organization_name' },
                    { data: 'email', name: 'email' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@stop