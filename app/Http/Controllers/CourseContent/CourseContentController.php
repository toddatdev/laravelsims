<?php

namespace App\Http\Controllers\CourseContent;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseContent\StoreCourseContentRequest;
use App\Models\Course\Course;
use App\Models\CourseContent\CourseContent;
use App\Models\CourseContent\CoursePage;
use App\Models\CourseContent\courseFile;
use App\Models\CourseContent\QSE\EventQSEActivation;
use App\Models\CourseContent\QSE\QSE;
use App\Models\CourseContent\QSE\QSEQuestion;
use App\Models\CourseContent\ViewerType;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

/**
 * Class CourseContentController
 * @package App\Http\Controllers\CourseContent
 */
class CourseContentController extends Controller
{

    public function store(StoreCourseContentRequest $request)
    {
        // Add the new course item to the bottom of the module
        if ($display_order = CourseContent::where('parent_id', $request->parent_id)->orderBy('display_order', 'desc')->first()) {
            $display_order = $display_order->display_order + 1;
        } else {
            $display_order = 0;
        }

        $courseContent = new CourseContent();
        $courseContent->course_id = $request->course_id;
        $courseContent->parent_id = $request->parent_id;
        $courseContent->viewer_type_id = $request->viewer_type_id;
        $courseContent->content_type_id = $request->content_type_id;
        $courseContent->created_by = $request->created_by;
        $courseContent->menu_title = $request->menu_title;
        $courseContent->last_edited_by = auth()->id();
        $courseContent->display_order = $display_order;
        $courseContent->save();

        // populate menu_id with the id of the sandbox course_content
        $courseContent->menu_id = $courseContent->id;
        $courseContent->save();

        // If content_type is page, create page too
        if ($request->content_type_id == 2) {
            $courseContent->coursePage()->save(new CoursePage(['locked_by' => auth()->id()]));
        }

        //if content_type is a video or document or anyfile, persist it to database it
        if ($request->content_type_id == 3 ||
            $request->content_type_id == 4 ||
            $request->content_type_id == 5 ||
            $request->content_type_id == 6) {

            //uploaded video/doc url
            $link = $request->aws_file_url;

            //persist the s3 video/document url to database
            $courseContent->courseFile()->save(new courseFile(['links' => $link]));
        }

        // Content Type QSE
        if ($request->content_type_id == 7) {

            $qse = $courseContent->qse()
                ->save(new QSE([
                    'qse_type_id' => $request->qse_type_id,
                    'activation_type_id' => 1,
                    'presentation_type_id' => 2,
                    'feedback_type_id' => 1,
                    'created_by' => auth()->id(),
                    'last_edited_by' => auth()->id(),
                ]));

            if ($request->has('add_to_event_qse_activation') && $request->add_to_event_qse_activation != 'do_not_add_to_any_event') {

                $existingEvents = \App\Models\CourseInstance\Event::with('CourseInstance')
                    ->where('parking_lot', '=', 0)
                    ->whereHas('CourseInstance', function($q) use($request){
                        $q->where('course_id', '=', $request->course_id);
                    });

                if ($request->add_to_event_qse_activation == 'add_to_all_upcoming_events') {
                    $existingEvents = $existingEvents->whereDate('start_time', '>=', \Carbon\Carbon::now());
                }

                $existingEvents = $existingEvents->get();

                foreach ($existingEvents as $event) {
                    $eventQSEActivation = EventQSEActivation::create(['event_id' => $event->id,
                        'qse_id' => $qse->id,
                        'last_edited_by' => auth()->user()->id,
                    ]);
                }


            }
        }

        return back();
    }

    public function update(Request $request, $id)
    {
        $courseContent = CourseContent::find($id);
        $courseContent->menu_title = $request->menu_title;
        $courseContent->last_edited_by = Auth::user()->id;
        $courseContent->save();
        return back();
    }

    public function edit($id)
    {
        //set previous page in session for breadcrumbs
        if (strpos(url()->previous(), '/mycourses')) {
            session(['breadCrumbPage' => 'My Courses']);
        } elseif (strpos(url()->previous(), '/courses')) {
            session(['breadCrumbPage' => 'Manage Courses']);
        }

        $contentTypeModule = 1;
        $contentTypePage = 2;
        $contentTypeVideo = 3;
        $contentTypeDoc = 4;
        $contentTypeAnyFile = 5;
        $contentTypePresenter = 6;
        $contentTypeQSE = 7;

        $course = Course::find($id);
        $courseContents = (new CourseContent)->courseContents($id);

        $viewerType = ViewerType::all()->sortBy('display_order');

        return view('courseContent.edit',
            compact('course',
                'courseContents',
                'viewerType',
                'contentTypeModule',
                'contentTypeVideo',
                'contentTypePage',
                'contentTypeDoc',
                'contentTypeAnyFile',
                'contentTypePresenter',
                'contentTypeQSE'
            )
        )->withId($id);
    }

    public function retire($id)
    {
        // retire sandbox AND published content
        $allContent = CourseContent::where('menu_id', $id)->get();
        foreach ($allContent as $content)
            (new CourseContent)->retire($content->id);

        // delete published content
        $publishedContent = CourseContent::where('menu_id', $id)->whereNotNull('published_date')->get();
        foreach ($publishedContent as $content)
            if($content->qse){
//                $content->published_by = null;
//                $content->published_date = null;
//                $content->save();
            } else {
                (new CourseContent)->destroy($content->id);
            }

        return back();
    }

    public function activate($id)
    {
        (new CourseContent)->activate($id);
        return back();
    }

    public function retireModule($id)
    {
        $courseContent = CourseContent::findorFail($id);

        // Unpublish Module
        $courseContent->published_date = null;
        $courseContent->published_by = null;
        $courseContent->save();

        // Retire Module
        (new CourseContent)->retire($id);

        // Retire sandbox children
        foreach ($courseContent->contentItems as $contentItem)
            (new CourseContent)->retire($contentItem->id);

        // Delete the modules published items
        $publishedContent = (new CourseContent)->where('parent_id', $id)->whereNotNull('published_date')->get();
        foreach ($publishedContent as $contentItem)
            (new CourseContent)->destroy($contentItem->id);

        return back();
    }

    public function activateModule($id)
    {
        (new CourseContent)->activate($id);

        $courseContent = CourseContent::findorFail($id);
        foreach ($courseContent->contentItems as $contentItem)
            (new CourseContent)->activate($contentItem->id);

        return back();
    }


    public function destroy($id)
    {
        // remove sandbox AND published content
        $allContent = CourseContent::where('menu_id', $id)->get();

        foreach ($allContent as $content) {

            //destroy course content
            CourseContent::destroy($content->id);

            if ($content->content_type_id != 2) {
                //delete course video/office file from database and AWS
                courseFile::deleteFile($content->id);
            }

        }

        return back();
    }

    /**
     * Updates the display order of the Curriculum items
     *
     * @param Request $request
     */
    public function updateOrder(Request $request)
    {
        $parentId = $request->parentId;
        $type = $request->viewerType;
        $sortOrder = $request->sortOrder;

        foreach ($sortOrder as $order => $id) {
            if ($id == 0) {
                continue;
            }
            $content = CourseContent::find($id);
            $content->timestamps = false;
            $content->display_order = $order;
            $content->viewer_type_id = $type;
            if ($parentId) $content->parent_id = $parentId;
            $content->save();

            $this->syncOrderPublishedItems($id, $order);
        }
    }

    public function syncOrderPublishedItems($id, $order)
    {
        $publishedTwin = CourseContent::where('menu_id', $id)->whereNotNull('published_date')->first();

        // not published
        if (!$publishedTwin)
            return;

        // published
        $publishedTwin->display_order = $order;
        $publishedTwin->timestamps = false;
        $publishedTwin->save();
        return;
    }

    public function duplicate($id)
    {
        $courseContent = CourseContent::with('coursePage', 'courseFile')->find($id);
        $newCourseContent = $courseContent->replicate();
        $newCourseContent->menu_title = $courseContent->menu_title . ' (copy)';
        $newCourseContent->save();
        $newCourseContent->menu_id = $newCourseContent->id;
        $newCourseContent->save();

        // Duplicate page
        if ($newCourseContent->content_type_id === 2) {
            $newPage = $courseContent->coursePage->replicate();
            $newPage->course_contents_id = $newCourseContent->id;
            $newPage->save();
        }

        $courseFile = $courseContent->courseFile;

        if (!$courseFile) {
            $courseFile = courseFile::where('course_contents_id', $courseContent->menu_id)->first();
        }

        // Duplicate video/file
        if ($newCourseContent->content_type_id != 2 && $courseFile && filter_var($courseFile->links, FILTER_VALIDATE_URL)) {

            $url = parse_url($courseFile->links);

            $filePath = ltrim($url['path'], '/');
            $basename = pathinfo($filePath)['basename'];
            if (strpos($filePath, '.html') !== false) {

                $dirPath = str_replace('/' . $basename, '', $filePath);

                if (Storage::disk('s3')->exists($dirPath) > 0) {
                    $files = $files = Storage::disk('s3')->allFiles($dirPath);
                    $dir = explode('/', $dirPath);
                    $oldDir = $dir[count($dir) - 1];
                    $dirTimeStampAndName = explode('-', $oldDir);
                    $dirTimeStampAndName[0] = time();

                    // New Directory
                    $dir[count($dir) - 1] = implode('-', $dirTimeStampAndName);
                    $dir = implode('/', $dir);

                    foreach ($files as $file) {
                        $copied_file = str_replace($dirPath, $dir, $file);
                        if (!Storage::disk('s3')->exists($copied_file)) Storage::disk('s3')->copy($file, $copied_file);
                    }

                    $newFilePath = $dir . '/' . $basename;
                }

            } else if (Storage::disk('s3')->exists($filePath)) {
                $newFilePath = $filePath . time() . '-' . str_replace(substr($basename, 0, 11), '', $basename);
                Storage::disk('s3')->copy($filePath, $newFilePath);
            }


            $newAWSFileURL = Storage::disk('s3')->url($newFilePath);

            $newFile = $courseFile->replicate();
            $newFile->course_contents_id = $newCourseContent->id;
            $newFile->links = $newAWSFileURL;
            $newFile->save();
        }

        return back();
    }

    public function indent($id)
    {
        $courseContent = CourseContent::findorFail($id);
        $courseContent->indent_level++;
        $courseContent->save();

        return back();
    }

    public function outdent($id)
    {
        $courseContent = CourseContent::findorFail($id);
        $courseContent->indent_level--;
        $courseContent->save();

        return back();
    }

    public function uploadFile(Request $request, $id)
    {
        $site_id = $request->session()->get('site_id');

        if (!$request->hasFile('file')) {
            return false;
        }

        $file = $request->file('file');

        //set directory based on type
        switch ($request->upload_file_type) {
            case 'video':
                $directory = 'video';
                break;
            case 'document':
                $directory = 'office';
                break;
            case 'anyFile':
                $directory = 'download';
                break;
            case 'articulate':
                $directory = 'tmp';
                break;
        }

        if ($request->upload_file_type == 'articulate') {
            $fileName = strtolower(str_replace('.zip', '', str_replace(' ', '_', str_replace('_', '-', $file->getClientOriginalName()))));

            $dirName = time() . '-' . $fileName;

            $tmpZipLocation = $file->store('tmp');

            //formulate file path
            $filesDirPathS3 = 'site-' . $site_id . '/courses/' . $id .
                '/' . $request->file_type . '/' . $dirName;

            $tmpDirPath = 'tmp/' . $dirName;

            Storage::disk('local')->makeDirectory($tmpDirPath);

            $zip = new ZipArchive;
            if ($zip->open(storage_path() . '/app/' . $tmpZipLocation)) {
                $zip->extractTo(storage_path() . '/app/' . $tmpDirPath . '/');
                $zip->close();
            }

            $files = Storage::disk('local')->allFiles($tmpDirPath);

            foreach ($files as $file) {
                Storage::disk('s3')->put(str_replace($tmpDirPath, $filesDirPathS3, $file), Storage::disk('local')->get($file), 'public');
            }

            switch ($request->file_type) {
                case 'articulate':
                    $filePath = $filesDirPathS3 . '/presentation_html5.html';
                    break;
                case 'engage':
                    $filePath = $filesDirPathS3 . '/interaction_html5.html';
                    break;
                case 'quizmaker':
                    $filePath = $filesDirPathS3 . '/quiz_html5.html';
                    break;
                case 'rise':
                    $filePath = $filesDirPathS3 . '/content/index.html';
                    break;
                case 'storyline':
                    $filePath = $filesDirPathS3 . '/story.html';
                    break;
            }

            Storage::disk('local')->deleteDirectory($tmpDirPath);
            Storage::delete($tmpZipLocation);
        } else {

            //formulate file path
            $filePath = 'site-' . $site_id . '/courses/' . $id .
                '/' . $directory . '/' . time() . '-' . str_replace(' ', '-', $file->getClientOriginalName());

            //push the file into s3 storage
            $s3 = Storage::disk('s3')->put($filePath, fopen($file,"r+"), 'public');
        }

        $awsFileURL = Storage::disk('s3')->url($filePath);

        //get the uploaded file URL
        return response()->json([
            'aws_url' => $awsFileURL
        ]);
    }

    public function uploadImage(Request $request, $id)
    {
        if ($request->file) {
            $site_id = $request->session()->get('site_id');
            $image = $request->file;
            $imageName = 'site-' . $site_id . '/courses/' . $id . '/' . $image->getClientOriginalName();

            $s3 = Storage::disk('s3')->put($imageName, file_get_contents($image), 'public');
            $imageName = Storage::disk('s3')->url($imageName);

            return response()->json([
                'location' => $imageName,
            ]);
        }
    }


    public function publish($id, $type)
    {
        // $id is the sandbox course_content_id

        // Delete published page already published, duplicate sandbox
        $oldPublishedCourseContent = CourseContent::with('coursePage')
            ->where('menu_id', $id)
            ->where('published_date', '!=', null)
            ->get();

        if ($type != 'qse' && isset($oldPublishedCourseContent[0]->id)) {
            $oldPublishedCourseContent[0]->destroy($oldPublishedCourseContent[0]->id);
        }

        //page type
        if ($type === 'page') {
            // set published_by, published_date on published course_content
            $courseContent = CourseContent::with('coursePage')->find($id);
            $publishedCourseContent = $courseContent->replicate();
            $publishedCourseContent->menu_id = $id;
            $publishedCourseContent->published_by = Auth::id();
            $publishedCourseContent->published_date = now();
            $publishedCourseContent->save();
        }


        //video type or file type
        if ($type === 'video' || $type === 'office') {

            // set published_by, published_date on published course_content
            $courseContent = CourseContent::with('courseFile')->find($id);
            $publishedCourseContent = $courseContent->replicate();
            $publishedCourseContent->menu_id = $id;
            $publishedCourseContent->published_by = Auth::id();
            $publishedCourseContent->published_date = now();
            $publishedCourseContent->save();

        }

        if ($type === 'qse') {
            // set published_by, published_date on published course_content
            $publishedCourseContent = CourseContent::with('qse')->findOrFail($id);
            if ($publishedCourseContent->published_date == null) {
                $publishedCourseContent->published_by = Auth::id();
                $publishedCourseContent->published_date = now();
                $publishedCourseContent->save();
            }

            if ($publishedCourseContent->qse) {
                $qse = $publishedCourseContent->qse;

                QSEQuestion::where('course_qse_id', $qse->id)->whereNull('publish_date')->update([
                    'publish_date' => now(),
                    'published_by' => Auth::id(),
                ]);
            }

        } else {
            // sync sandbox edit time with published time
            $courseContent->updated_at = $publishedCourseContent->published_date;
            $courseContent->save();
        }


        // if page then duplicate page too
        if ($publishedCourseContent->content_type_id == 2) {
            $publishedPage = $courseContent->coursePage->replicate();
            $publishedPage->course_contents_id = $publishedCourseContent->id;
            $publishedPage->save();
        }

        //if video/office then duplicate the video/office too
        if (in_array($publishedCourseContent->content_type_id, [3, 4, 5, 6])) {

            $publishedFile = $courseContent->courseFile->replicate();
            $publishedFile->course_contents_id = $publishedCourseContent->id;
            $publishedFile->save();

        }

        return back();
    }

    public function publishModule($id)
    {
        $module = CourseContent::find($id);
        $module->published_by = Auth::id();
        $module->published_date = now();
        $module->save();

        return back();
    }
}
