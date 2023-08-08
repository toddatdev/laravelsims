<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned();
            $table->string('abbrv', 30);
            $table->string('name', 255);
            $table->timestamp('creation_date')->useCurrent()->comment = "Date the course was initially created";
            $table->date('retire_date')->nullable()->comment = "Date the course was retired";
            $table->string('catalog_description',10000)->nullable();
            $table->string('catalog_image',2000)->nullable();
            $table->string('author_name',2000)->nullable();
            $table->tinyInteger('virtual')->nullable();
            $table->integer('setup_time')->nullable()->comment = "Minutes required to setup prior to class.";
            $table->integer('tear_down_time')->nullable()->comment = "Minutes required to tear down after a class.";
            $table->integer('instructor_report_time')->nullable()->comment = "Minutes instructors should report before or after start time.";
            $table->string('instructor_report_time_before_after', 1)->nullable()->comment = "B for before and A for after the class start time.";
            $table->integer('instructor_leave_time')->nullable()->comment = "Minutes instructors should leave before or after end time.";
            $table->string('instructor_leave_time_before_after', 1)->nullable()->comment = "B for before and A for after the class end time.";
            $table->integer('created_by')->unsigned();
            $table->timestamps();

            /*
            * Add Foreign/Unique/Index
            */
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->index('abbrv');
            $table->unique(['site_id', 'abbrv']);
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
        Schema::dropIfExists('courses');
    }
}
