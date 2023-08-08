@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.users.management') . ' | ' . trans('labels.backend.access.users.view'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.access.users.management') }}
    </h1>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('labels.backend.access.users.view') }}</h3>
                        <div class="float-right">
                            @include('backend.access.includes.partials.user-header-buttons')
                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">

                        <div class="card-header p-0 pt-1">

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="nav-item">
                                    <a class="nav-link active" href="#overview" aria-controls="overview" role="tab" data-toggle="tab">{{ trans('labels.backend.access.users.tabs.titles.overview') }}</a>
                                </li>

                                <li role="presentation" class="nav-item">
                                    <a class="nav-link" href="#history" aria-controls="history" role="tab" data-toggle="tab">{{ trans('labels.backend.access.users.tabs.titles.history') }}</a>
                                </li>
                            </ul>

                            <div class="tab-content">

                                <div role="tabpanel" class="tab-pane mt-30 active" id="overview">
                                    @include('backend.access.show.tabs.overview')
                                </div><!--tab overview profile-->

                                <div role="tabpanel" class="tab-pane mt-30" id="history">
                                    @include('backend.access.show.tabs.history')
                                </div><!--tab panel history-->

                            </div><!--tab content-->
                        </div><!-- /.card -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection