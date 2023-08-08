<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CourseTemplateResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_template_resources', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_template_id')->unsigned();
            $table->integer("resource_identifier");
            $table->integer("resource_template_type")->unsigned();
            $table->time("start_time");
            $table->time("end_time");
            $table->integer("setup_time")->default(0);
            $table->integer("teardown_time")->default(0);
            $table->integer('created_by')->unsigned();
            $table->timestamps();

            $table->foreign('resource_template_type')->references('id')->on('resource_template_type');
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
        Schema::dropIfExists('course_template_resources');
    }
}
