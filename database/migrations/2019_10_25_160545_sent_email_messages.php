<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SentEmailMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_email_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_email_id')->unsigned()->nullable();
            $table->integer('course_email_id')->unsigned()->nullable();
            $table->integer('event_email_id')->unsigned()->nullable();
            $table->string('primary_recipient'); // we should store who email was intended for
            $table->string('to', 2000);
            $table->string('cc', 2000)->nullable();
            $table->string('bcc', 2000)->nullable();
            $table->string('subject', 400);
            $table->text('body');
            $table->string('mailgun_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('sent_email_messages', function($table) {
            $table->foreign('site_email_id')->references('id')->on('site_emails')->onDelete('cascade');
            $table->foreign('course_email_id')->references('id')->on('course_emails')->onDelete('cascade');
            $table->foreign('event_email_id')->references('id')->on('event_emails')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sent_email_messages');
    }
}
