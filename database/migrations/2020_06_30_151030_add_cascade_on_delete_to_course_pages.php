<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeOnDeleteToCoursePages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_pages', function (Blueprint $table) {
            $table->dropForeign('course_pages_course_contents_id_foreign');
            $table->foreign('course_contents_id')
                ->references('id')->on('course_contents')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_pages', function (Blueprint $table) {
            $table->dropForeign('course_pages_course_contents_id_foreign');
        });
    }
}
