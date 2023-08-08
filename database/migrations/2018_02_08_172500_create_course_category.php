<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_category', function (Blueprint $table) {
            $table-> integer('course_id')->unsigned();
            $table-> integer('course_category_id')->unsigned();
            $table->timestamps();

            /*
            * Add Foreign/Unique/Index
            */
            $table-> foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table-> foreign('course_category_id')->references('id')->on('course_categories')->onDelete('cascade');
            $table-> primary(['course_id', 'course_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_category');
    }
}
