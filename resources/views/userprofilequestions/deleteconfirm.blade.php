@extends('backend.layouts.app')

@section ('title', trans('menus.backend.user-profile-questions.title') . ' | ' . trans('menus.backend.user-profile-questions.edit'))

@section('page-header')
    <h1>
        {{ trans('menus.backend.user-profile-questions.title') }}
        <small>{{ trans('menus.backend.user-profile-questions.delete') }}</small>
    </h1>
@endsection

@section('content')

{{ Form::open(['route' => ['question.delete', $question->question_id], 'method' => 'delete']) }}

    <div class="alert alert-warning alert-block">

        <strong>{{ 'Are you sure you want to delete '. $question->question_text .' ?' }}</strong>

        {{ link_to_route('all_user_profile_questions', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs ml-20']) }}
        {{ Form::submit(trans('buttons.general.crud.delete'), ['class' => 'btn btn-success btn-xs ml-5']) }}

    </div>

{{ Form::close() }}
@stop