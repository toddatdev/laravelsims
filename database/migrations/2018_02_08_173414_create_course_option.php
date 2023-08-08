<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_option', function (Blueprint $table) {
            $table-> integer('course_id')->unsigned();
            $table-> integer('option_id')->unsigned();
            $table->timestamps();

            /*
            * Add Foreign/Unique/Index
            */
            $table-> foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table-> foreign('option_id')->references('id')->on('course_options')->onDelete('cascade');
            $table-> primary(['course_id', 'option_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_option');
    }
}
