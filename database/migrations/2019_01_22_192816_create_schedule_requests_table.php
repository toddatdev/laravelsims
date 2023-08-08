<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_request_id')->comment('Groups together requests that should be part of the same course_instance');
            $table->integer('course_id')->unsigned();
            $table->integer('location_id')->unsigned();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('template_id')->unsigned()->nullable()->comment('Link to the course template for this request');
            $table->integer('num_rooms')->nullable();
            $table->integer('class_size');
            $table->tinyInteger('sims_spec_needed');
            $table->string('notes', 4000)->nullable();
            $table->integer('event_id')->unsigned()->nullable();
            $table->integer('denied_by')->unsigned()->nullable();
            $table->dateTime('denied_date')->nullable();

            $table->timestamps();
            $table->softDeletes();

            /*
            * Add Foreign/Unique/Index
            */
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('denied_by')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_requests');
    }
}
