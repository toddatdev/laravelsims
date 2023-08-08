@if($userSelectedAnswer)
<div class="ansSelectBox">
    
    <select style= "margin-top:10px" required class="user_profile_answer_id form-control col-md-5" name="answer[{{$question->question_id }}][]">
        <option value="">Select Answer</option>
    @foreach($userSelectedAnswer as $item)
     <?php $commentNeedAnswerId[] = ($item['comment_needed']) ? $item['user_profile_answer_id'] : FALSE ?>
     <option comment_needed="{{ $item['comment_needed'] }}" @if(in_array($item['user_profile_answer_id'],$answerIdsOfUser)) selected @endif value="{{ $item['user_profile_answer_id'] }}">{{ $item['answer_text'] }}</option>
    @endforeach
    </select>
        <?php 
            $commentNeedAnswerId = array_values(array_filter($commentNeedAnswerId));
            $commentNeedAnswerId = count($commentNeedAnswerId) > 0? $commentNeedAnswerId[0] : 0;
        ?>
</div>
<div class="ansSelectBox">
    @if(isset($answerComments[$commentNeedAnswerId]))
     <input type="text" style="margin-top:10px" class="user_profile_answer_id form-control col-md-5" name="comments[{{$question->question_id }}][]" value="{{ $answerComments[$commentNeedAnswerId] }}" />
    @endif
</div> 
@endif
