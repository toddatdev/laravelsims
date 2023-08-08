<?php

namespace App\Http\Controllers\UserProfile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserProfile\UserProfileQuestion;
use App\Models\userProfile\UserProfileAnswer;
use App\Models\userProfile\UserProfile;
use Session;
use App\Http\Requests\UserProfile\UserProfileRequest;
use Carbon\Carbon;

class UserProfileController extends Controller {

    /**
     * Display a listing of the Questions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $site_id = Session::get('site_id');

        $questions = UserProfileQuestion::orderBy('display_order', 'ASC')->select('question_id', 'question_text', 'site_id','retire_date')->where('site_id', $site_id)->get();

        return view('userprofilequestions.index', compact('questions'));
    }

    /**
     * Display a listing of the Questions and update the Display Order based on Sorting.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateDisplayOrder(Request $request) {
        $site_id = Session::get('site_id');

        $questions = UserProfileQuestion::orderBy('display_order', 'ASC')->select('question_id', 'question_text', 'site_id')->where('site_id', $site_id)->get();

        foreach ($questions as $question) {
            $question->timestamps = false; // To disable update_at field updation
            $question_id = $question->question_id;


            foreach ($request->display_order as $display_order) {
                // var_dump($display_order);
                // var_dump($question_id);

                if ($display_order['question_id'] == $question_id) {
                    // var_dump("ok");
                    //die();
                    $question->update(['display_order' => $display_order['position']]);
                }
            }
        }

        return response('Update Successfully.', 200);

        //    return DataTables::of($questions)
        //        ->addColumn('actions', function($question) {
        //            return $question->action_buttons;
        //        })
        //        ->rawColumns(['actions', 'confirmed'])
        //        ->make(true);
    }

    /**
     * Show the form for creating a new Question.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('userprofilequestions.create');
    }

    /**
     * Store a newly created Question in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserProfileRequest $request) {

        $input = $request->all();

        $site_id = Session::get('site_id');

        $question_id = array_get($input, 'question_id');

        
        $userProfileQuestion = $question_id ? UserProfileQuestion::find($question_id) : new UserProfileQuestion;
        $userProfileQuestion->question_text = $input['question'];
        $userProfileQuestion->site_id = $site_id;
        $userProfileQuestion->response_type = 'test';
        $userProfileQuestion->display_order = 1;
        $userProfileQuestion->save();

        //var_dump($input['answers']);
        
        if($question_id) {
            UserProfileAnswer::where('question_id',$question_id)->delete();
        }
        $questions = $this->renderStructure($input['answers']);
        $this->insertProfileAnswer($questions, $userProfileQuestion->question_id);
        
        $message = $question_id ? 'Profile has been updated.' : 'Profile has been created.';
        
        return ['url' => route('all_user_profile_questions'), 'message' => $message];
    }
    
    /**
     * Build the Tree structure of Question for Edit Page
     *
     */

    protected function buildStructure($id, $data) {

        $return{$id} = array();

        if (isset($data['parents'][$id])) {
            foreach ($data['parents'][$id] as $child) {
                $build = $data['items'][$child];
                if (isset($data['parents'][$child])) {
                    $build['has_children'] = true;
                    $build['children'] = $this->buildStructure($child, $data);
                } else {
                    $build['has_children'] = false;
                }
                $return{$id}[] = $build;
            }
        }
        return (array) $return{$id};
    }

    /**
     * Render the Tree structure of Question for Edit Page
     *
     */

    protected function renderStructure($items) {

        $data = array();

        foreach ($items as $item) {
            $item = (array) $item;
            $data['items'][$item['id']] = $item;
            $data['parents'][$item['parent_id']][] = $item['id'];
        }

        return $this->buildStructure(0, $data);
    }

    /**
     * Insert Profile answers into the UserProfileAnswer Table
     *
     */

    function insertProfileAnswer($questions, $question_id, $parent_id = 0) {

        foreach ($questions as $item) {
            //dd($item);

                $data = [
                    'question_id' => $question_id,
                    'parent_id' => $parent_id,
                    'answer_text' => $item['name'],
                    'response_type' => 'test',
                    'display_order' => 1,
                    'comment_needed' => $item['comment_needed'],
                ];
    
                $userProfileAnswer = UserProfileAnswer::create($data);
    
                $lastInsertID = $userProfileAnswer->user_profile_answer_id;
    
                if ($item['has_children']) {
                    $this->insertProfileAnswer($item['children'], $question_id, $lastInsertID);
                }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified Question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        $userProfileQuestion = UserProfileQuestion::where('question_id', $id)->with('user_profile_answer')->first();
        $answers = $this->buildTree($userProfileQuestion->user_profile_answer->toArray());
        //dd($answers);
        $answersHtml = $this->generateHtml($answers);
        return view('userprofilequestions.edit', compact('userProfileQuestion', 'answersHtml'));
    }

    /**
     * Build the Tree structure of Answers
     *
     */

    function buildTree(array $elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['user_profile_answer_id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    /**
     * Generate the HTML structure of Question based on Tree built above
     *
     */

    function generateHtml($answers, $html = '', $parent_id = 0, $margin = 0, $level = 0) {

        foreach ($answers as $answer) {

            $html .= '<div data-level="' . $level . '" style="margin-left: ' . $margin . 'px;" class="answersFieldHolder item" checkValue="' . $answer['comment_needed'] . '" item-id="' . $answer['user_profile_answer_id'] . '" item-name="' . $answer['answer_text'] . '" parent-id="' . $parent_id . '" >
                <a data-parent-level="" data-level-path="0" data-current-level="' . $level . '" class="addLevel pull-left" href="#"><i
                        class="fa fa-plus-circle" aria-hidden="true" title="Add Answer"></i></a>
                <a class="pull-left removeLevel" href="#"><i class="fa fa-minus-circle" aria-hidden="true" title="Remove Answer"></i></a>';

            if($answer['comment_needed'] == '1')
            {
                $html .= '<a class="sub-Level disable pull-left" href="#"><i class="fa fa-level-down" aria-hidden="true" title="Add Child Answer"></i></a>';
            }
            else
            {
                $html .= '<a class="addSubLevel sub-Level pull-left" href="#"><i class="fa fa-level-down" aria-hidden="true" title="Add Child Answer"></i></a>';
            }

            $html .= '<input class="pull-left qAnsField" type="text" value="' . $answer['answer_text'] . '" name="" />';

                if($answer['comment_needed'] == '1')
                {
                    $html .='<input class="pull-left commentNeeded" type="checkbox" checked  name="has_comment" title="Comment Needed"/><label for="has_comment" class="chkbox">Requires text field</label>';
                    $html .= '<div class="clearfix clear"></div>';
                    $html .= '</div>';
                    
                }
                else
                {
                    if(isset($answer['children']))
                    {
                        $html .='<input class="pull-left commentNeeded" type="checkbox" name="has_comment" title="Comment Needed"/><label for="has_comment" class="chkbox">Requires text field</label>';
                        
                        $html .= '<div class="clearfix clear"></div>';
                        $localLevel = $level + 1;
                        $marginMargin = $localLevel * 10;
                        $html = $this->generateHtml($answer['children'], $html, $answer['user_profile_answer_id'], $marginMargin, $localLevel);
                        $html .= '</div>';
                      
                    }
                    else
                    {
                    
                       $html .='<input class="pull-left commentNeeded" type="checkbox" name="has_comment" title="Comment Needed"/><label for="has_comment" class="chkbox">Requires text field</label>';
                       $html .= '<div class="clearfix clear"></div>';
                       $html .= '</div>';
                       

                    }
                    
                }
           
        }
        return $html;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Delete confirmation page
     * @return \Illuminate\Http\Response
     */
    public function deleteconfirm(UserProfileQuestion $question)
    {
        //dd($question);

        return view('userprofilequestions.deleteconfirm', compact('question'));
    }

    /**
     * Remove the specified Question from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) 
    {
        $user_profile_answer_ids = UserProfileAnswer::where('question_id',$id)->get()->pluck('user_profile_answer_id')->toArray();
        $isNotAllowedToDelete = UserProfile::whereIn('user_profile_answer_id',$user_profile_answer_ids)->get()->toArray();
       if(!$isNotAllowedToDelete) {
          UserProfileQuestion::destroy($id); 
             return redirect()->route('all_user_profile_questions',[$id])
            ->withFlashSuccess(trans('alerts.backend.user-profile.deleted'));
       } else {
              return redirect()->route('all_user_profile_questions',[$id])
            ->withFlashDanger(trans('alerts.backend.user-profile.canNotBeDeleted'));
       }

    }

    /**
     * Retire the specified Question from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function retireQuestion($id) 
    {
        
        UserProfileQuestion::where('question_id', $id)->update(['retire_date' => Carbon::now()]);
        return redirect()->route('retired_user_profile_question',[$id])
            ->withFlashSuccess(trans('alerts.backend.user-profile.retired'));
        
    }

    /**
     * Activate the specified Question from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activateQuestion($id) 
    {
        
        UserProfileQuestion::where('question_id', $id)->update(['retire_date' => NULL]);
        return redirect()->route('active_user_profile_question',[$id])
            ->withFlashSuccess(trans('alerts.backend.user-profile.activated'));
        
    }

}
