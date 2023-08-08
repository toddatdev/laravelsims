<?php

namespace App\Http\Controllers\CourseContent;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseContent\PageRequest;
use App\Models\CourseContent\CourseContent;
use App\Models\CourseContent\CoursePage;
use App\Models\CourseContent\courseFile;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseFilesController extends Controller
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
        return view('courseContent.videos.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PageRequest $request
     * @return Application|\Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function store(PageRequest $request)
    {
        $coursePage = new CoursePage;
        $coursePage->course_contents_id = $request->course_contents_id;
        $coursePage->text = $request->text;
        $coursePage->locked_by = auth()->id();
        $coursePage->save();

        return redirect("/course/content/{$request->course_id}/edit");
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($type, $id)
    {
        // Get CourseContent and the related Pages
        $courseContent = CourseContent::with('courseFile', 'contentType', 'course')->find($id);

        return view('courseContent.files.edit')
            ->withCourseContent($courseContent);
    }

    /**
     * Update the specified resource in storage.
     *
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $page = CoursePage::findorFail($id);
        $page->text = $request->text;;
        $page->save();

        return back()->with('saved', 1);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destory($id)
    {

    }


}
