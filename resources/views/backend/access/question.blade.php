@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.users.profile-management') . ' | ' . trans('labels.backend.access.users.profile-edit'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.access.users.profile-management') }}
        <small>{{ trans('labels.backend.access.users.profile-edit') }}</small>
    </h1>
@endsection

@section('content')
 {{ Form::model($user, ['route' => ['admin.access.user.question-answers-save', $user], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) }}

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">{{ trans('labels.backend.access.users.profile-edit') }}</h3>

        <div class="box-tools pull-right">
            @include('backend.access.includes.partials.user-header-buttons')
        </div><!--box-tools pull-right-->
</div><!-- /.box-header -->

<div class="box-body">
                 
@foreach($userProfileQuestions as $question)
<div class="form-group">
    <label for="profilequestion" class="col-md-2 control-label">{{ $question->question_text }}</label>
    
   <div id="{{ $question->question_id }}" question_id="{{ $question->question_id }}" class="col-md-3 questionsAnswerHolder">
    <div class="ansSelectBox">
        
          @if(isset($userSelectedAnswers[$question->question_id]))
        @foreach($userSelectedAnswers[$question->question_id] as $k=>$userSelectedAnswer)
              @include('frontend.user.account.tabs.partial.ansSelectbox')
         @endforeach
        @else
        <select required="required" class="user_profile_answer_id form-control col-md-5" name="answer[{{$question->question_id}}][]">
            <option value="">Select Answer</option>
            @foreach($question->user_profile_answer as $user_profile_answer)
            <option comment_needed="{{ $user_profile_answer->comment_needed }}" value="{{ $user_profile_answer->user_profile_answer_id }}">{{ $user_profile_answer->answer_text }}</option>
            @endforeach
        </select>
      @endif
    </div>
   </div>
</div>
@endforeach

</div><!-- /.box-body -->
 </div><!--box-->

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                {{ link_to_route('admin.access.user.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-warning btn-md']) }}
            </div><!--pull-left-->

            <div class="pull-right">
                {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-md']) }}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

  

    {{ Form::close() }}
@endsection

@section('after-scripts')
   

    <!-- <script type="text/javascript" src="{{ asset('/js/user-profile.js') }}"></script> -->
    <script type="text/javascript" src="{{ asset('/js/user_profile_update.js') }}?{{ time() }}"></script>
@endsection

@section('after-styles')
    <link type="text/css" rel="stylesheet" href="{{ asset('/css/user_profile_update.css') }}">
@endsection