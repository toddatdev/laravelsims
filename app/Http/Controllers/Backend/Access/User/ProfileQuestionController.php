<?php

namespace App\Http\Controllers\Backend\Access\User;

use App\Http\Controllers\Controller;
use App\Models\Site\Site;
use App\Models\userProfile\UserProfileQuestion;
use App\Models\userProfile\UserProfileAnswer;
use App\Models\userProfile\UserProfile;
use App\Models\Access\User\User;
use Illuminate\Http\Request;
use SESSION;

/**
 * Class UserController.
 */
class ProfileQuestionController extends Controller
{

    public function index(Request $request, $id)
    {
       
       
        $site_id = SESSION::get('site_id');

        $user = User::find($id);
        
        $user_id = $user->id;
        
        // Put the site info for the banner, etc. -jl 2018-04-16 15:28 
        $site = Site::find($site_id);

        $userProfileQuestions = UserProfileQuestion::where(['site_id' => $site_id])
                ->with(['user_profile_answer' => function($q) {
                        $q->where(['parent_id' => 0]);
                    }])->get();
                    
        $userProfileAnswersIdsInfo = UserProfile::where(['user_id' => $user_id])->get();
          
        $answerIdsOfUser = [];
        $answerComments = [];
        foreach($userProfileAnswersIdsInfo as $userProfileAnswerInfo) {
           $answerIdsOfUser[] = explode(',', $userProfileAnswerInfo->path);
           $answerComments[$userProfileAnswerInfo->user_profile_answer_id] = $userProfileAnswerInfo->comment;
        }

       $answerIdsOfUser =  array_flatten($answerIdsOfUser);
 
        $userProfileAnswersParentIds = UserProfileAnswer::whereIn('user_profile_answer_id', $answerIdsOfUser)
                ->get()->pluck('parent_id','user_profile_answer_id')->toArray();
        
     
        
        $userProfileAnswers = UserProfileQuestion::where(['site_id' => $site_id])
                ->with(['user_profile_answer' => function($q) use ($userProfileAnswersParentIds) {
                        $q->whereIn('parent_id', $userProfileAnswersParentIds);
                    }])->get()->toArray();
                    
       $userSelectedAnswers = [];
        foreach($userProfileAnswers as $k) {
           foreach($k['user_profile_answer'] as $v) {
               $userSelectedAnswers[$v['question_id']][$v['parent_id']][] = $v;
           }
        }
       
        return view('backend.access.question', compact('site','user','answerComments','answerIdsOfUser', 'userSelectedAnswers', 'userProfileQuestions'));

    }
    
    public function store(Request $request,$id) {

        $input = $request->all();
        
      
        $comments = array_get($input,'comments',[]);

        $user = User::find($id);
        
        $user_id = $user->id;

        $data = [];
        foreach ($input['answer'] as $question_id => $answers) {
            
            $data[] = [
                'user_profile_answer_id' => end($answers),
                'user_id' => $user_id,
                'comment' => isset($comments[$question_id]) ? $comments[$question_id][0] : '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'path' => implode(",", $answers)
            ];
        }
  
        // remove old 
        UserProfile::where(['user_id' => $user_id])->delete();

        // create new
        UserProfile::insert($data);

        return redirect()->route('admin.access.user.index')->withFlashSuccess(trans('strings.frontend.user.profile_updated'));
    }
    
}
