<?php

namespace App\Models\CourseContent;

use App\Models\Course\Course;
use App\Models\CourseContent\QSE\QSE;
use App\Models\CourseContent\QSE\QSEQuestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use function Clue\StreamFilter\fun;
use function foo\func;

class CourseContent extends Model
{
    protected $table = 'course_contents';

    protected $fillable = [
        'course_id',
        'viewer_type_id',
        'content_type_id',
        'parent_id',
        'display_order',
        'menu_id',
        'menu_title'
    ];

    /**
     * Scope a query to only include active content
     * this is called in a query with ->active()
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('retired_date', null);
    }

    /**
     * Scope a query to only include QSE content type (content_type_id = 7)
     * this is called in a query with ->QSE()
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeQSE($query)
    {
        return $query->where('content_type_id', 7);
    }

    /**
     * Scope a query to only include active courses
     * this is called in a query with ->published()
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('published_date', '<>', null);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function parent()
    {
        return $this->hasOne(self::class, 'id', 'parent_id')
            ->where('parent_id', 0);
    }

    public function contentItems()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('display_order');
    }

    public function publishedContentItems()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->whereNotNull('published_date')
            ->whereNull('retired_date')
            ->orderBy('display_order');
    }

    public function unPublishedContentItems()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->whereNull('published_date')
            ->whereNull('retired_date')
            ->orderBy('display_order');
    }


    public function menuPublishedContentItems($course_id, $viewer_type_id)
    {
        $publishedCourseContent = (new CourseContent)->viewer($course_id);
        return $publishedCourseContent->where('viewer_type_id', $viewer_type_id);
    }

    public function menuUnPublishedContentItems($course_id, $viewer_type_id)
    {
        $unPublishedCourseContent = (new CourseContent)->viewerUnpublished($course_id);

        return $unPublishedCourseContent->where('viewer_type_id', $viewer_type_id);
    }

//    TODO: Remove after further testing...
//    it has been refactored with the function above menuPublishedContentItems

//    public function menuPublishedContentItemsBackup($course_id, $viewer_type_id)
//    {
//        $publishedCourseContent = (new CourseContent)->viewer($course_id);
//        $publishedCourseContent = $publishedCourseContent->where('viewer_type_id', $viewer_type_id);
//        $contentItems = $publishedCourseContent->pluck('publishedContentItems');
//
//        // Get module names
//        foreach($publishedCourseContent as $parent) {
//            $module[$parent->id] = $parent->menu_title;
//        }
//        // Build item array
//        $i = 0;
//        foreach ($contentItems as $items) {
//            foreach ($items as $item) {
//                $i++;
//                $content[$i]['content_id'] = $item->id;
//                $content[$i]['content_type_id'] = $item->content_type_id;
//                $content[$i]['menu_title'] = $item->menu_title;
//                $content[$i]['module'] = $module[$item->parent_id];
//            }
//        }
//
//        return collect($content);
//    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function publishedTwin()
    {
        return $this->hasOne(self::class, 'menu_id')->whereNotNull('published_date')->first();
    }

    public function publishedChildren()
    {
        $userId = \auth()->user()->id;

        return $this->children()
                    ->whereRaw(<<<EOF
                        CASE
                            WHEN exists(select 1 from qse where course_content_id = `course_contents`.`id`)
                            THEN
                                CASE
                                    WHEN EXISTS (
                                            SELECT 1 FROM qse as q
                                            JOIN event_user_qse as euq ON euq.course_qse_id = q.id WHERE `course_contents`.`id` = q.course_content_id
                                        )
                                    THEN
                                        CASE
                                            WHEN EXISTS (
                                                    SELECT 1 FROM qse as q
                                                    JOIN event_user_qse as euq ON euq.course_qse_id = q.id
                                                    JOIN event_user eu ON euq.event_user_id = eu.id WHERE `course_contents`.`id` = q.course_content_id AND eu.user_id = $userId
                                                ) 
                                            THEN 
                                                1
                                            WHEN NOT EXISTS (
                                                    SELECT 1 FROM qse as q
                                                    JOIN event_user_qse as euq ON euq.course_qse_id = q.id
                                                    JOIN event_user eu ON euq.event_user_id = eu.id WHERE `course_contents`.`id` = q.course_content_id AND eu.user_id = $userId
                                                )
                                            THEN 
                                                course_contents.retired_date is null AND course_contents.published_date is not null
                                        END
                                    WHEN NOT EXISTS (
                                                SELECT 1 FROM qse as q
                                                JOIN event_user_qse as euq ON euq.course_qse_id = q.id WHERE `course_contents`.`id` = q.course_content_id
                                            )
                                    THEN 
                                        course_contents.retired_date is null AND course_contents.published_date is not null
                                END
                            WHEN NOT EXISTS (
                                    SELECT 1 FROM qse WHERE course_content_id = `course_contents`.`id`
                                )
                            THEN 
                                course_contents.retired_date is null AND course_contents.published_date is not null
                        END
EOF
                    );

    }

    public function allChildren()
    {
        return $this->children()->with('allChildren')->orderBy('display_order');
    }

    public function viewerType()
    {
        return $this->hasOne(ViewerType::class)->orderBy('display_order');
    }

    public function contentType()
    {
        return $this->belongsTo(ContentType::class);
    }

    public function coursePage()
    {
        return $this->hasOne(CoursePage::class, 'course_contents_id', 'id');
    }

    public function courseFile()
    {
        return $this->hasOne(courseFile::class, 'course_contents_id', 'id');
    }

    public function getPublishedStatusAttribute()
    {
        $courseContent = $this->where('menu_id', $this->id)->get();

        if ($courseContent[0]->qse) {

            // not published
            if ($courseContent[0]->published_date == null)
                return 1;


            // older version is published
            if (QSEQuestion::where('course_qse_id', $courseContent[0]->qse->id)->whereNull('publish_date')->count())
                return 3;

            // sandbox version is published
            if ($courseContent[0]->published_date)

                return 2;

        }

        // not published
        if (empty($courseContent[1]))
            return 1;

        // sandbox version is published
        if ($courseContent[0]->updated_at == $courseContent[1]->published_date)
            return 2;

        // older version is published
        if ($courseContent[0]->updated_at != $courseContent[1]->published_date)
            return 3;
    }

    // gets all modules and content items for course
    public function courseContents($course_id)
    {
        return CourseContent::whereCourseId($course_id)
            ->where('parent_id', 0)
            ->orderBy('display_order')
            ->with('ContentItems')
            ->get()->groupBy('viewer_type_id');
    }

    public function viewer($course_id)
    {
        return CourseContent::whereCourseId($course_id)
            ->where('parent_id', 0)
            ->whereNotNull('published_date')
            ->whereNull('retired_date')
            ->orderBy('display_order')
            ->with('publishedContentItems')
            ->get();
    }

    public function viewerUnpublished($course_id)
    {
        return CourseContent::whereCourseId($course_id)
            ->where('parent_id', 0)
            ->whereNull('published_date')
            ->whereNull('retired_date')
            ->orderBy('display_order')
            ->with('unPublishedContentItems')
            ->get();
    }

    public function retire($id)
    {
        $courseContent = CourseContent::findorFail($id);
        $courseContent->retired_date = now();
        $courseContent->retired_by = Auth::id();
        $courseContent->save();

        return;
    }

    public function activate($id)
    {
        $courseContent = CourseContent::findorFail($id);
        $courseContent->retired_date = null;
        $courseContent->retired_by = null;
        $courseContent->save();

        return;
    }

    public function qseCanBePublish()
    {
        $qse = QSE::where('course_content_id', $this->id)->with('qseQuestions')->first();

        if (!$qse) return 0;

        return $qse->qseQuestions->count();
    }

    /**
     * QSE's
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function qse()
    {
        return $this->hasOne(QSE::class);
    }
}
