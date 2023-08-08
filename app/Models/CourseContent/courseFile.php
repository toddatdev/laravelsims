<?php

namespace App\Models\CourseContent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class courseFile extends Model
{
    protected $table = 'course_external_content';

    protected $fillable = [
        'links'
    ];

    protected $touches = ['courseContent'];

    public function courseContent()
    {
        return $this->belongsTo(CourseContent::class, 'course_contents_id', 'id');
    }

    public static function deleteFile($course_id)
    {
        $courseFile = courseFile::where('course_contents_id', $course_id)->first();

        if ($courseFile) {
            if (filter_var($courseFile->links, FILTER_VALIDATE_URL)) {
                $url = parse_url($courseFile->links);
                $filePath = $url['path'];
                if (strpos($filePath, '.html') !== false) {
                    $basename = pathinfo($filePath)['basename'];
                    $dirPath = str_replace('/' . $basename, '', $filePath);

                    if (Storage::disk('s3')->exists($dirPath) > 0) {
                        Storage::disk('s3')->deleteDirectory($dirPath);
                    }
                } else if (Storage::disk('s3')->exists($filePath)) {
                    //remove video/office docs from s3
                    Storage::disk('s3')->delete($filePath);
                }
            }
            //destroy video/office URLS from database
            return $courseFile->delete();
        }
    }
}
