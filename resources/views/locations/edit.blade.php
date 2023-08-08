@extends('backend.layouts.app')

@section ('title', trans('menus.backend.location.title') . ' | ' . trans('menus.backend.location.edit'))

@section('page-header')
    <h4>
        {{ trans('menus.backend.location.title') }}
    </h4>
@endsection

@section('content')

    {{-- include the Spectrum color picker CSS, JavaScript files and JQuery. --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/spectrum/spectrum.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/spectrum/larasim-spectrum.css') }}">
    {{ Html::script("js/jquery.js") }}
    <script type="text/javascript" src="{{ asset('/js/spectrum/spectrum.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/spectrum/larasim-spectrum.js') }}"></script>

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

    {{ Form::model($location, ['route' => ['update_location', $location], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) }}
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('menus.backend.location.edit') }}</h3>
                        <div class="float-right">
                            @include('locations.partial-header-buttons-sub')
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @include('locations.partial-form')
                    </div><!-- /.card-body -->
                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to_route('all_locations', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
                        </div><!--pull-left-->
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-md']) }}
                        </div><!--pull-right-->
                    </div><!-- /.card-footer -->
                </div><!-- /.card -->
            </div>
        </div>
    </section>
    {{ Form::close() }}

@stop

