<?php

namespace App\Http\Controllers\CourseContent;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseContent\PageRequest;
use App\Models\CourseContent\CourseContent;
use App\Models\CourseContent\courseFile;
use App\Models\CourseContent\CoursePage;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('courseContent.pages.edit');
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

    public function show($content_id, $status = 'published')
    {

        $courseContent = CourseContent::with('courseFile')->findOrFail($content_id);

        if ($status === 'published') {

            $menuCourseContent = (new CourseContent)->menuPublishedContentItems($courseContent->course_id,
                $courseContent->viewer_type_id);

            $page = $courseContent->coursePage()->first();

            $allCourseItems = $menuCourseContent->pluck('publishedContentItems');

        } elseif ($status == 'preview') {
            //get a preview of unpublished content items
            $menuCourseContent = (new CourseContent)->menuPublishedContentItems($courseContent->course_id,
                $courseContent->viewer_type_id);

            $page = $courseContent->coursePage()->first();

            $allCourseItems = $menuCourseContent->pluck('unPublishedContentItems');
        }

        $module = [];
        $content = [];

        // Get module names
        foreach ($menuCourseContent as $parent) {
            $module[$parent->id] = $parent->menu_title;
        }

        // Build item array
        $i = 0;

        foreach ($allCourseItems as $items) {
            foreach ($items as $item) {
                $i++;
                $content[$i]['content_id'] = $item->id;
                $content[$i]['content_type_id'] = $item->content_type_id;
                $content[$i]['menu_title'] = $item->menu_title;
                $content[$i]['module'] = $module[$item->parent_id];
            }
        }

        $allItems = collect($content);
        $totalItems = count($allItems);

        $item = $allItems->where('content_id', $content_id)->first();

        // Get the previous and next item
        $currentItem = $allItems->where('content_id', $content_id)->keys()->first();

        $currentItem > 1
            ? $prev = $allItems[$currentItem - 1]
            : $prev = null;

        $currentItem < $totalItems
            ? $next = $allItems[$currentItem + 1]
            : $next = null;

//        $view = ($status === 'preview') ? view('courseContent.pages.preview') : view('courseContent.pages.show');

        $courseFile = $courseContent->courseFile;

        if (!$courseFile) {
            $courseFile = courseFile::where('course_contents_id', $courseContent->menu_id)->first();
        }

        return view('courseContent.pages.show')->withPage($page)
            ->withItem($item)
            ->withPrev($prev)
            ->withNext($next)
            ->withMenuCourseContent($menuCourseContent)
            ->withCourseContent($courseContent)
            ->withCourseFile($courseFile)
            ->withStatus($status);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Get CourseContent and the related Pages
        $courseContent = CourseContent::with('coursePage')->with('course')->find($id);

        return view('courseContent.pages.edit')
            ->withCourseContent($courseContent);
    }

    /**
     * Update the specified resource in storage.
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
    public function destroy($id)
    {
        //
    }
}