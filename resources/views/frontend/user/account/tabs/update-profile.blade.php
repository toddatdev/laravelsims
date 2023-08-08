{{ Form::open(['route' => ['frontend.user.profile.update.answers'],'role'=>'form', 'class' => 'form-horizontal', 'method' => 'patch']) }}


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


<div class="form-group">
    <div class="col-md-3 col-md-offset-2">
        {{ Form::submit(trans('labels.general.buttons.update'), ['class' => 'btn btn-success btn-xs']) }}
    </div>
</div>

{{ Form::close() }}

