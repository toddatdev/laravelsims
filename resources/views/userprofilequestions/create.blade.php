@extends('backend.layouts.app')

@section ('title', trans('menus.backend.user-profile-questions.title') . ' | ' . trans('menus.backend.user-profile-questions.create'))

@section('page-header')
    <h1>
        {{ trans('menus.backend.user-profile-questions.title')  }}
        <small>{{ trans('menus.backend.user-profile-questions.create') }}</small>
    </h1>
@endsection

@section('content')

    {{ Form::open(['url' => '/user-profile-questions', 'id' => 'userProfileForm', 'class' => 'form-horizontal']) }}

     <div class="alert hide alert-danger"></div>
     <div class="alert hide alert-success"></div>
     
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('menus.backend.user-profile-questions.create') }}</h3>

            <div class="box-tools pull-right">
                @include('userprofilequestions.partial-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->
       
       @include('userprofilequestions.partial-form')

    </div><!--/.box-success -->

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('all_user_profile_questions', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right" uid="dfsd">
                {{ Form::submit(trans('buttons.general.crud.create'), ['id' => 'submit-user-proifle-questions','class' => 'btn btn-success btn-xs']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}

  
@stop


@section('after-scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- <script type="text/javascript" src="{{ asset('/js/user-profile.js') }}"></script> -->
    <script type="text/javascript" src="{{ asset('/js/user-profile.js') }}?{{ time() }}"></script>
@endsection

@section('after-styles')
    <link type="text/css" rel="stylesheet" href="{{ asset('/css/user-profile.css') }}">
@endsection