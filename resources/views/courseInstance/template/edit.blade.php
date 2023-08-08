@extends('frontend.layouts.app')

@section ('title', trans('labels.template.template') . ' | ' . trans('labels.general.edit'))

@section('page-header')
    <h4>
        {{ trans('labels.template.template') . ' - ' . trans('labels.general.edit') }}
    </h4>
@endsection

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>
                {!! $message !!}
            </strong>
        </div>
    @endif

    <section class="content">
        {{ Form::model($template, ['route' => ['template.update', $template], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) }}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $template->course->name }} ({{ $template->course->abbrv }})</h3>
                        <div class="float-right">
                            {!! $template->getExportButtonAttribute() !!}
                        </div>
                    </div>

                    <div class="card-body">
                            @include('courseInstance.template.partial-form')
                    </div>

                    {{--cancel and submit buttons--}}
                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to(URL::previous(), trans('buttons.general.cancel'), ['class' => 'btn btn-warning btn-md']) }}
                        </div><!--pull-left-->
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-md']) }}
                        </div><!--pull-right-->
                    </div><!-- /.card-footer -->

                </div>
            </div>
        </div>
        {{ Form::close() }}
    </section>

@endsection

@section('after-scripts')




@endsection
