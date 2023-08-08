<?php

namespace App\Http\Controllers\UserProfile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Models\Site\Site;
use App\Models\UserProfile\UserProfileQuestion;

class UserProfileQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Return the list of Active Questions
     */

    public function activeQuestions()
    {
        $site_id = Session::get('site_id');

        $questions = UserProfileQuestion::orderBy('display_order', 'ASC')->select('question_id', 'question_text', 'site_id')->whereNull('user_profile_questions.retire_date')->where('site_id', $site_id)->get();
        
        return view('userprofilequestions.active', compact('questions'));
    }

    /**
     * Return the list of Retired Questions
     */
    
    public function retiredQuestions()
    {
        $site_id = Session::get('site_id');

        $questions = UserProfileQuestion::orderBy('display_order', 'ASC')->select('question_id', 'question_text', 'site_id')->whereNotNull('user_profile_questions.retire_date')->where('site_id', $site_id)->get();
        
        return view('userprofilequestions.retired', compact('questions'));
    }

    
}
