<?php

namespace App\Http\Controllers\CourseContent\QSE;

use App\Http\Controllers\Controller;
use App\Models\CourseContent\QSE\EventUserQSE;
use App\Models\CourseContent\QSE\EventUserQSEAnswer;
use App\Models\CourseContent\QSE\EventUserQSEComment;
use App\Models\CourseContent\QSE\QSEQuestion;
use App\Models\CourseContent\QSE\QSEQuestionAnswer;
use App\Models\CourseInstance\EventUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use net\authorize\util\Log;

class QSEQuestionController extends Controller
{

    public function generateQuestionAnswers($range, $question) {
        foreach ($range as $ls) {
            $ans = [];
            $ans['created_by'] = auth()->user()->id;
            $ans['last_edited_by'] = auth()->user()->id;
            $ans['text'] = $ls;
            $ans['display_order'] = $ls;

            $question->qseQuestionAnswers()->save(new QSEQuestionAnswer($ans));
        }
    }

    public function store(Request $request)
    {

        $request->validate([
            'course_qse_id' => 'required|exists:qse,id',
            'text' => 'required|string|max:4000',
            'answer_type_id' => 'nullable|exists:qse_answer_types,id',

            'answerCorrects' => ['nullable', 'array', function ($attribute, $value, $fail) {
                if (!in_array(1, $value)) {
                    $fail(trans('validation.attributes.qse.answerCorrects'));
                }
            }],

            'answerTexts.*' => 'nullable|string|max:4000',
            'answerFeedbacks.*' => 'nullable|string|max:4000',
            'likert_caption.*' => 'nullable|string|max:30',
            'likert_scale' => 'nullable|string|max:30',
        ]);

        $questionReq = $request->only(['course_qse_id', 'answer_type_id', 'text', 'display_order', 'answer_type_id']);
        $questionReq['created_by'] = auth()->user()->id;
        $questionReq['last_edited_by'] = auth()->user()->id;
        $questionReq['display_order'] = 0;

        if ($request->input('likert_caption')) {
            if (is_array($request->likert_caption)) {
                $questionReq['likert_caption'] = implode(',', $request->likert_caption);
            }
        }

        $question = QSEQuestion::create($questionReq);


        if($request->has('likert_scale')) {
            $this->generateQuestionAnswers(range(1, (int) $request->likert_scale), $question);
        }

        if ($question->answer_type_id == 8) {
            $this->generateQuestionAnswers(range(1, 10), $question);
        }

        if ($question->answer_type_id == 4) {
            $this->generateQuestionAnswers(range(1, 1), $question);
        }

        if ($question->answer_type_id == 10) {

            $question->qseQuestionAnswers()->save(new QSEQuestionAnswer([
                'created_by' => auth()->user()->id,
                'last_edited_by' => auth()->user()->id,
                'text' => 'Yes',
                'display_order' => 1,
                'correct' => 1,
            ]));

            $question->qseQuestionAnswers()->save(new QSEQuestionAnswer([
                'created_by' => auth()->user()->id,
                'last_edited_by' => auth()->user()->id,
                'text' => 'No',
                'display_order' => 2,
            ]));
        }

        $correctCount = 0;
        foreach ($request->get('answerTexts', []) as $key => $answerText) {
            $ans = [];
            $ans['created_by'] = auth()->user()->id;
            $ans['last_edited_by'] = auth()->user()->id;
            $ans['text'] = $answerText;
            $ans['display_order'] = $key;

            if ($request->has('answerCorrects') && count($request->get('answerCorrects')) && isset($request->get('answerCorrects')[$key])) {
                $ans['correct'] = $request->get('answerCorrects')[$key];

                if($request->get('answerCorrects')[$key]) {
                    $correctCount++;
                }
            }

            if ($request->has('answerFeedbacks') && count($request->get('answerFeedbacks')) && isset($request->get('answerFeedbacks')[$key])) {
                $ans['feedback'] = $request->get('answerFeedbacks')[$key];
            }

            $question->qseQuestionAnswers()->save(new QSEQuestionAnswer($ans));
        }

        if ($question->qse->qse_type_id == 1) {
            $question->update([
                'answer_type_id' => $correctCount > 1 ? 1 : 2,
                'display_order' => $question->id,
            ]);
        } else {
            $question->update(['display_order' => $question->id]);
        }

        return response()->json(array_merge($question->toArray(), ['answers' => $question->qseQuestionAnswers->toArray()]));
    }

    public function update(Request $request, QSEQuestion $question)
    {
        $request->validate([
            'course_qse_id' => 'required|exists:qse,id',
            'text' => 'required|string|max:4000',
            'answer_type_id' => 'nullable|exists:qse_answer_types,id',

            'answerCorrects' => ['array', function ($attribute, $value, $fail) {
                if (!in_array(1, $value)) {
                    $fail(trans('validation.attributes.qse.answerCorrects'));
                }
            }],
            'answerTexts.*' => 'required|string|max:4000',
            'answerFeedbacks.*' => 'nullable|string|max:4000',
            'likert_caption.*' => 'nullable|string|max:30',
            'likert_scale' => 'nullable|string|max:30',
        ]);

        $questionReq = $request->only(['answer_type_id', 'text', 'answer_type_id']);
        $questionReq['last_edited_by'] = auth()->user()->id;
        if ($request->input('likert_caption')) {
            if (is_array($request->likert_caption)) {
                $questionReq['likert_caption'] = implode(',', $request->likert_caption);
            }
        }
        $question->update($questionReq);

        if($request->has('likert_scale')) {

            \DB::statement("delete from qse_question_answers where qse_question_id={$question->id}");

            $this->generateQuestionAnswers(range(1, (int) $request->likert_scale), $question);
        }

        foreach ($request->get('answerTexts', []) as $key => $answerText) {
            $ans = [];
            $ans['last_edited_by'] = auth()->user()->id;
            $ans['text'] = $answerText;
            $ans['display_order'] = $key;

            if ($request->has('answerCorrects') && count($request->get('answerCorrects')) && isset($request->get('answerCorrects')[$key])) {
                $ans['correct'] = $request->get('answerCorrects')[$key];
            }

            if ($request->has('answerFeedbacks') && count($request->get('answerFeedbacks')) && isset($request->get('answerFeedbacks')[$key])) {
                $ans['feedback'] = $request->get('answerFeedbacks')[$key];
            }

            $ans['last_edited_by'] = auth()->user()->id;

            $answer = $request->has('answerIds') && count($request->get('answerIds')) && isset($request->get('answerIds')[$key]) ? QSEQuestionAnswer::where('id', $request->get('answerIds')[$key])->first() : null;

            if ($answer) {
                $answer->update($ans);
            } else {
                $ans['created_by'] = auth()->user()->id;
                $question->qseQuestionAnswers()->save(new QSEQuestionAnswer($ans));
            }

        }
        return response()->json(array_merge($question->toArray(), ['answers' => $question->qseQuestionAnswers->toArray()]));
    }

    public function updateDisplayOrder(Request $request, QSEQuestion $question)
    {
        $question->update($request->all());
        return response()->json(['status' => 'OK']);
    }

    public function retireOrActivate(Request $request, QSEQuestion $question) {
        $request->validate([
           'retire_or_activate' => 'required'
        ]);

        if ($request->retire_or_activate == 'retire') {
            $question->retired_by = Auth::id();
            $question->retired_date = now();
            $question->save();
        } elseif ($request->retire_or_activate == 'activate') {
            $question->retired_by = null;
            $question->retired_date = null;
            $question->save();
        }

        return back();
    }

    public function destroy(QSEQuestion $question)
    {
        $question->delete();
        return back()->with('status', 'Question & their answers deleted successfully.');
    }
    
    public function submitAnswer(Request $request) {
        $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'qse_id' => ['required', 'exists:qse,id'],
            'q_id' => ['required', 'exists:qse_questions,id'],
            'event_user_qse_id' => ['required', 'exists:event_user_qse,id'],
        ]);

        if ($request->presentation_type_id == 1) {

            $key = $request->q_answer_key;

            if (str_contains($key, 'checkbox')) {
                $multiAnswers = $request->input($key);
                if (is_array($multiAnswers)) {
                    foreach ($multiAnswers as $ma) {

                        EventUserQSEAnswer::create([
                            'event_user_qse_id' => $request->event_user_qse_id,
                            'course_qse_question_id' => $request->q_id,
                            'qse_question_answer_id' => $ma,
                        ]);
                    }
                } else {
                    EventUserQSEAnswer::create([
                        'event_user_qse_id' => $request->event_user_qse_id,
                        'course_qse_question_id' => $request->q_id,
                        'qse_question_answer_id' => $multiAnswers,
                    ]);
                }
            } elseif (str_contains($key, 'radio')) {
                $singleAnswer = $request->input($key);
                EventUserQSEAnswer::create([
                    'event_user_qse_id' => $request->event_user_qse_id,
                    'course_qse_question_id' => $request->q_id,
                    'qse_question_answer_id' => $singleAnswer,
                ]);
            }
        } elseif ($request->presentation_type_id == 2) {
            EventUserQSEAnswer::where([
                'event_user_qse_id' => $request->event_user_qse_id,
                'course_qse_question_id' => $request->q_id,
                'evaluatee_id' => $request->input('evaluatee_id')
            ])->delete();
            $key = $request->q_answer_key;

            if (str_contains($key, 'checkbox')) {
                $multiAnswers = $request->input($key);
                if (is_array($multiAnswers)) {
                    foreach ($multiAnswers as $ma) {

                        EventUserQSEAnswer::create([
                            'event_user_qse_id' => $request->event_user_qse_id,
                            'course_qse_question_id' => $request->q_id,
                            'qse_question_answer_id' => $ma,
                            'evaluatee_id' => $request->input('evaluatee_id')
                        ]);
                    }
                } else {
                    EventUserQSEAnswer::create([
                        'event_user_qse_id' => $request->event_user_qse_id,
                        'course_qse_question_id' => $request->q_id,
                        'qse_question_answer_id' => $multiAnswers,
                        'evaluatee_id' => $request->input('evaluatee_id')
                    ]);
                }
            } elseif (str_contains($key, 'radio')) {
                $singleAnswer = $request->input($key);
                $euqa = EventUserQSEAnswer::create([
                    'event_user_qse_id' => $request->event_user_qse_id,
                    'course_qse_question_id' => $request->q_id,
                    'qse_question_answer_id' => $singleAnswer,
                    'evaluatee_id' => $request->input('evaluatee_id')
                ]);

                if ($request->written_response != null && $request->written_response != '') {
                    $euqa->eventUserQSEComment()->save(new EventUserQSEComment(['comment' => $request->written_response]));
                }
            }

            if ($request->evaluatee_id !== '' && $request->evaluatee_id !== null) {
                EventUserQSE::where('id', $request->event_user_qse_id)->update(['evaluatee_id' => $request->evaluatee_id]);
            }
        }

        return response()->json(EventUserQSEAnswer::where('event_user_qse_id', $request->event_user_qse_id)->first());
    }

    public function takeQuiz(Request $request) {
        $eventUser = EventUser::where(['user_id' => \auth()->user()->id, 'event_id' => $request->event_id])->first();
        $eventUserQSE = EventUserQSE::create([
            'event_user_id' => $eventUser->id,
            'course_qse_id' => $request->qse_id,
            'complete' => 0,
        ]);

        return response()->json($eventUserQSE);
    }

    public function submitQuiz(Request $request) {
        $eventUserQSE = EventUserQSE::findOrFail($request->event_user_qse_id);
        $eventUserQSE->complete = 1;
        $eventUserQSE->save();

        if ($request->qse_type_id === "4") {
            $eventUsers = \App\Models\CourseInstance\EventUser::whereIn('role_id', explode(',', $request->evaluatee_roles))
                ->where('event_id', $request->event_id)
                ->where('user_id', '<>', auth()->user()->id)
                ->where('status_id', 1)
                ->join('users', 'users.id', '=', 'user_id')
                ->orderBy('users.first_name')
                ->orderBy('users.last_name')
                ->get();

            foreach ($eventUsers as $eventUser) {
                $submittedQuestionsIds = $eventUserQSE->eventUserQSEAnswers()->where('evaluatee_id', $eventUser->id)->pluck('course_qse_question_id');
                $notSubmittedQuestions = QSEQuestion::byQSE($eventUserQSE->course_qse_id, $eventUserQSE->qse->random)
                    ->whereNotIn('id', $submittedQuestionsIds)
                    ->published()
                    ->active()
                    ->get();

                foreach ($notSubmittedQuestions as $q) {
                    if ($q->answer_type_id != 5 && $q->answer_type_id != 6) {
                        EventUserQSEAnswer::create([
                            'event_user_qse_id' => $eventUserQSE->id,
                            'course_qse_question_id' => $q->id,
                            'evaluatee_id' => $eventUser->id
                        ]);
                    }
                }
            }
        } else{
            $submittedQuestionsIds = $eventUserQSE->eventUserQSEAnswers()->pluck('course_qse_question_id');
            $notSubmittedQuestions = QSEQuestion::byQSE($eventUserQSE->course_qse_id, $eventUserQSE->qse->random)
                ->whereNotIn('id', $submittedQuestionsIds)
                ->published()
                ->active()
                ->get();

            foreach ($notSubmittedQuestions as $q) {
                if ($q->answer_type_id != 5 && $q->answer_type_id != 6) {
                    EventUserQSEAnswer::create([
                        'event_user_qse_id' => $eventUserQSE->id,
                        'course_qse_question_id' => $q->id,
                    ]);
                }
            }
        }


        return response()->json($eventUserQSE);
    }

    public function duplicate(Request $request, QSEQuestion $question) {
        $newModel = $question->replicate();
        $question->load('qseQuestionAnswers');
        $newModel->text = "{$newModel->text} (Duplicate)";
        $newModel->published_by = null;
        $newModel->publish_date = null;
        $newModel->retired_by = null;
        $newModel->retired_date = null;
        $newModel->push();

        foreach($question->qseQuestionAnswers->toArray() as $answer){
            unset($answer['id']);
            $newModel->qseQuestionAnswers()->save(new QSEQuestionAnswer($answer));
        }
        return redirect()->back();
    }
}
