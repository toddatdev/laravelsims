<?php

namespace App\Http\Controllers\CourseContent\QSE;

use App\Http\Controllers\Controller;
use App\Models\CourseContent\CourseContent;
use App\Models\CourseContent\QSE\EventQSEActivation;
use App\Models\CourseContent\QSE\EventUserQSE;
use App\Models\CourseContent\QSE\QSE;
use App\Repositories\Backend\Access\Role\RoleRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QSEController extends Controller {

    protected $roles;

    public function __construct(RoleRepository $roles, Request $request)
    {
        $this->roles = $roles;
    }

    /**
     * Edit
     * @param CourseContent $courseContent
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(CourseContent $courseContent) {
        $qse = QSE::where('course_content_id', $courseContent->id)
            ->with([
                'qseQuestions' => function($q) {
                    $q->orderBy('display_order');
                }
            ])
            ->first();
        //for role select list
        $roles = $this->roles->getRoles(3);
        return view('courseContent.qse.edit', compact('courseContent','qse', 'roles'));
    }

    /**
     * Show
     * @param CourseContent $courseContent
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(CourseContent $courseContent) {
        $qse = QSE::where('course_content_id', $courseContent->id)
            ->with([
                'qseQuestions' => function($q) {
                    $q->orderBy('display_order');
                }
            ])
            ->first();
        return view('courseContent.qse.show', compact('qse'));
    }

    /**
     * Update
     * @param Request $request
     * @param CourseContent $courseContent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, CourseContent $courseContent) {
        $request->validate([
            'instructions' => 'nullable|string|max:4000',
        ]);

        $qse = QSE::where('course_content_id', $courseContent->id)
            ->first();

        abort_if(!$qse, 404);

        $i = $request->all();

        if ($request->has('evaluatee_roles') && $request->evaluatee_roles != null && $request->evaluatee_roles != '') {
            $i['evaluatee_roles'] = implode(',', $request->evaluatee_roles);
        }

        EventQSEActivation::whereHas('event', function ($q) use ($request){
            $q->where('start_time', '>', Carbon::now());
        })->where('qse_id', $qse->id)->update(['activation_state' => $request->activation_type_id == 1 ? $request->activation_state : 1]);

        if ($request->activation_type_id == 2) {
            $i['activation_state'] = 1;
            $minutes = $request->minutes;

            if ($request->time_type == 'hours') {
                $minutes = $minutes * 60;
            } elseif ($request->time_type == 'days') {
                $minutes = $minutes * 1440;
            }

            if ($request->before_or_after == 'before') {
                $minutes = -1 * $minutes;
            }

            $i['minutes'] = $minutes;
        }

        $qse->update($i);

        return back()->with('status', 'QSE updated successfully.');
    }

    public function resultsReport(EventUserQSE $eventUserQse) {
        $result = $eventUserQse->result;
        $eventUserQSEAnswers = $eventUserQse->eventUserQSEAnswers;

        return response()->json(['html' => view('courseInstance.events.qse.results-report',
            compact('eventUserQse', 'result', 'eventUserQSEAnswers'))->render()]);
    }

    public function report(QSE $qse) {
        $eventUserQSEs = EventUserQSE::where('course_qse_id', $qse->id)->get();

        return view('courseInstance.events.qse.report',
            compact('eventUserQSEs', 'qse'));
    }
}