<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->unsigned();
            $table->integer('menu_id')->nullable();
            $table->string('menu_title', 75);
            $table->integer('viewer_type_id')->unsigned();
            $table->integer('content_type_id')->unsigned();
            $table->integer('parent_id');
            $table->integer('display_order');
            $table->integer('created_by')->unsigned();
            $table->integer('published_by')->unsigned()->nullable();
            $table->dateTime('published_date')->nullable();
            $table->integer('last_edited_by')->unsigned()->nullable();
            $table->integer('retired_by')->unsigned()->nullable();
            $table->dateTime('retired_date')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('viewer_type_id')->references('id')->on('viewer_types');
            $table->foreign('content_type_id')->references('id')->on('content_types');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('published_by')->references('id')->on('users');
            $table->foreign('last_edited_by')->references('id')->on('users');
            $table->foreign('retired_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_contents');
    }
}
