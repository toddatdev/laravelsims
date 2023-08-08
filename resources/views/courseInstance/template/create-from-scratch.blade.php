@extends('frontend.layouts.app')

@section ('title', trans('labels.template.template') . ' | ' . trans('labels.template.create'))

@section('page-header')
    <h4>
        {{ trans('labels.template.template') . ' - ' . trans('labels.general.create') }}
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
        {{ Form::open(['url' => route('template.store'), 'method' => 'post', 'class' => 'form-horizontal', 'name'=> 'templateForm']) }}
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">{{ $course->name }} ({{ $course->abbrv }})</h3>
                    </div>

                    <div class="card-body">
                            @include('courseInstance.template.partial-form')
                    </div>

                    {{--cancel and submit buttons--}}
                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to(URL::previous(), trans('buttons.general.cancel'), ['class' => 'btn btn-warning btn-md']) }}
                        </div>
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.save'), ['class' => 'btn btn-success btn-md']) }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
        {{ Form::close() }}
    </section>

@endsection

@section('after-scripts')




@endsection
