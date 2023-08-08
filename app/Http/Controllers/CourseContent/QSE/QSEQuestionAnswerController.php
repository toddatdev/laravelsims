<?php

namespace App\Http\Controllers\CourseContent\QSE;

use App\Http\Controllers\Controller;
use App\Models\CourseContent\QSE\QSEQuestionAnswer;

class QSEQuestionAnswerController extends Controller
{
    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        return response()->json(QSEQuestionAnswer::where('qse_question_id', request()->get('qqi'))->orderBy('display_order')->get(), 200);
    }

    /**
     *
     * @param QSEQuestionAnswer $questionAnswer
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(QSEQuestionAnswer $questionAnswer) {
        $questionAnswer->delete();

        return response()->json([
            'status' => 'OK',
        ], 200);
    }
}
