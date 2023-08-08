@extends('backend.layouts.app')

@section ('title', trans('menus.backend.site.title') . ' | ' . trans('menus.backend.site.create'))

@section('page-header')
    <h1>
        {{ trans('menus.backend.site.title') }}
    </h1>
@endsection

@section('content')
    <section class="content">
        {{ Form::open(['url' => '/sites', 'class' => 'form-horizontal']) }}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.site.create') }}</h3>
                        <div class="float-right">
                            @include('sites.partial-header-buttons')
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @include('sites.partial-form')
                    </div><!-- /.card-body -->
                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('all_sites', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
                        </div><!--pull-left-->
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-md']) }}
                        </div><!--pull-right-->
                    </div><!-- /.card-footer -->
                </div><!-- /.card -->
            </div>
        </div>
        {{ Form::close() }}
    </section>
@stop