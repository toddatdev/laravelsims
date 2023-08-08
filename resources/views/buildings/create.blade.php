@extends('backend.layouts.app')

@section ('title', trans('menus.backend.building.title') . ' | ' . trans('menus.backend.building.create'))

@section('page-header')
    <h4>
        {{ trans('menus.backend.building.title') }}
    </h4>
@endsection

@section('content')

    <script type="text/javascript" src="{{ asset('/js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: 'textarea',
            browser_spellcheck: true,
            menubar: false,
            plugins: [
                'advlist autolink lists link charmap print preview anchor textcolor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime table paste code help wordcount'
            ],
            toolbar: 'undo redo |  formatselect | bold italic underline backcolor forecolor removeformat  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link code | table',
        });
    </script>

    <section class="content">
        {{ Form::open(['url' => '/buildings', 'class' => 'form-horizontal']) }}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.building.create') }}</h3>
                        <div class="float-right">
                            @include('buildings.partial-header-buttons-sub')
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @include('buildings.partial-form')
                    </div><!-- /.card-body -->
                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('active_buildings', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
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
