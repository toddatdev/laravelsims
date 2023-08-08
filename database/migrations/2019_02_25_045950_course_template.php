<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CourseTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_instance_id')->unsigned();
            $table->string("name", 20);
            $table->integer("class_size");
            $table->string("public_comments")->nullable();
            $table->string("internal_comments")->nullable();
            $table->time("start_time");
            $table->time("end_time");
            $table->integer("setup_time")->default(0);
            $table->integer("teardown_time")->default(0);
            $table->integer("fac_report")->default(0);
            $table->integer("fac_leave")->default(0);
            $table->string("color", 30)->nullable();
            $table->integer('initial_meeting_room')->unsigned()->nullable();
            $table->integer('initial_meeting_room_type')->unsigned()->nullable();
            $table->integer('created_by')->unsigned();
            $table->timestamps();

            $table->foreign('initial_meeting_room_type')->references('id')->on('resource_template_type');
            $table->index('name');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_templates');
    }
}
