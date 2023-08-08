<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeletePkCourseCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_category', function (Blueprint $table) {

            //must drop foreign keys before you can drop primary key
            //doing this so that in the next migration a new primary key can be added
            $table->dropForeign('course_category_course_category_id_foreign');
            $table->dropForeign('course_category_course_id_foreign');
            $table->dropPrimary(['course_id', 'course_category_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_category', function (Blueprint $table) {


        });
    }
}
