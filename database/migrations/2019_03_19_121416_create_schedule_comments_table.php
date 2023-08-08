<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('schedule_request_id')->unsigned()->nullable();
            $table->integer('event_id')->unsigned()->nullable();
            $table->string('comment', 4000);
            $table->integer('created_by')->unsigned();
            $table->integer('last_edited_by')->unsigned();
            $table->timestamps();

            /* Add Foreign/Unique/Index */
            $table->foreign('schedule_request_id')->references('id')->on('schedule_requests')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('last_edited_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_comments');
    }
}
