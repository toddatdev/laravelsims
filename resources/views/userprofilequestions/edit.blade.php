@extends('backend.layouts.app')

@section ('title', trans('menus.backend.user-profile-questions.title') . ' | ' . trans('menus.backend.user-profile-questions.update'))

@section('page-header')
    <h1>
        {{ trans('menus.backend.user-profile-questions.title')  }}
        <small>{{ trans('menus.backend.user-profile-questions.update') }}</small>
    </h1>
@endsection

@section('content')

    {{ Form::open(['url' => '/user-profile-questions', 'id' => 'userProfileForm', 'class' => 'form-horizontal']) }}
{{ Form::hidden('question_id', $userProfileQuestion->question_id) }}
     <div class="alert hide alert-danger"></div>
     <div class="alert hide alert-success"></div>
     
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('menus.backend.user-profile-questions.update') }}</h3>

            <div class="box-tools pull-right">
                @include('userprofilequestions.partial-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->
       
       
        <div class="box-body">
    <div class="form-group">
        <div class="col-md-2 col-lg-2 pull-left">
            {{ Form::label('question', trans('labels.user-profile-questions.question'), ['class' => 'col-lg-2 control-label']) }}
        </div>
        <div class="col-md-6 col-lg-6">
            {{ Form::input('question', 'question', $userProfileQuestion->question_text, ['class' => 'form-control', 'placeholder' => trans('labels.user-profile-questions.question')]) }}
        </div><!--col-lg-10-->
    </div><!--form-group-->

    <div class="form-group ansFieldHolder" >

        <div class="col-md-2 col-lg-2 pull-left">
            {{ Form::label('answers', trans('labels.user-profile-questions.answer'), ['class' => 'col-lg-2 control-label']) }}
        </div>
         <div class="col-lg-8 col-md-8 col-sm-10 col-xs-12">
             {!! $answersHtml !!}
         </div>
        
    </div><!--form-group-->
</div><!-- /.box-body -->


    </div><!--/.box-success -->

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('all_user_profile_questions', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
            </div><!--pull-left-->

            <div class="pull-right" uid="dfsd">
                {{ Form::submit( $userProfileQuestion->question_id ? trans('buttons.general.crud.update'):trans('buttons.general.crud.create'), ['id' => 'submit-user-proifle-questions','class' => 'btn btn-success btn-xs']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {{ Form::close() }}

  
@stop


@section('after-scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('/js/user-profile.js') }}?{{ time() }}"></script>
@endsection

@section('after-styles')
    <link type="text/css" rel="stylesheet" href="{{ asset('/css/user-profile.css') }}">
@endsection