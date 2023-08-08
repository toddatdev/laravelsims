@extends ('backend.layouts.app')

@section ('title', trans('menus.backend.site.title'))

@section('after-styles')
@stop

@section('page-header')
{{--    <h4>{{ trans('labels.sites.edit-options') }}</h4>--}}
@endsection

@section('content')

    <section class="content">
        {{ Form::open(array('route' => 'update_all_options')) }}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ trans('labels.sites.edit-options') }}</h3>
                        <div class="float-right">
                            @include('sites.partial-header-buttons')
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                {{-- Put a banner with the log and the colors at the top, so we know clearly what site we are working with. --}}
                                <div style='background-color:{{ $site->getSiteOption(1) }};color:{{ $site->getSiteOption(2) }} '>
                                    <img src={{ URL::to('https://'.config('filesystems.disks.s3.bucket').'.s3.amazonaws.com/site-'.$site->id  .'/banner-logo.png')}}>
                                    {{-- <span style='font-size:1.5em'>{{ $site->organization_name }}</span> --}}
                                </div>
                            </div>
                        </div>

                        {{ Form::open(array('route' => 'update_all_options')) }}

                            @foreach ($siteOptions as $siteOption)
                                <div class="form-group row">
                                    {{ Form::label('name', $siteOption->name, ['class' => 'col-lg-3 control-label']) }}
                                    <div class="col-lg-9">
                                        {{ Form::text('value_'.$loop->iteration, $siteOption->value, null, ['class' => 'form-control' ])}}
                                        {{ Form::hidden('option_id_'.$loop->iteration, $siteOption->id)}}

                                        <!-- Option Description Modal -->
                                        <!-- Trigger the modal with a button -->
                                        <button type="button" class="btn btn-xs" data-toggle="modal" data-target="#option_desc_{{$loop->iteration}}"><i class="fa fa-lg fa-question-circle" aria-hidden="true"></i></button>
                                        <div class="modal fade" id="option_desc_{{$loop->iteration}}" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{ trans('menus.backend.site.option_description') }}</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{!! $siteOption->description !!}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('buttons.general.close') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        @endforeach
                            {{ Form::hidden('optionCount', $optionCount) }}
                            {{ Form::hidden('siteId', $site->id) }}

                    </div><!-- /.card-body -->
                    <div class="card-footer">
                        <div class="float-left">
                            {{ link_to(URL::previous(), trans('buttons.general.cancel'), ['class' => 'btn btn-warning btn-md']) }}
                        </div><!--pull-left-->
                        <div class="float-right">
                            {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-md']) }}
                        </div><!--pull-right-->
                    </div><!-- /.card-footer -->
                </div><!-- /.card -->
            </div>
        </div>
        {{ Form::close() }}
    </section>

@stop

@section('after-scripts')
@stop