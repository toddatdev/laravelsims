<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned();
            $table->integer('email_type_id')->unsigned();
            $table->string('label', 256);
            $table->string('subject', 400);
            $table->text('body');
            $table->string('to_roles', 2000)->nullable();
            $table->string('to_other', 2000)->nullable();
            $table->string('cc_roles', 2000)->nullable();
            $table->string('cc_other', 2000)->nullable();
            $table->string('bcc_roles', 2000)->nullable();
            $table->string('bcc_other', 2000)->nullable();
            $table->integer('time_amount')->nullable();
            $table->integer('time_type')->nullable();
            $table->integer('time_offset')->nullable();
            $table->integer('role_id')->unsigned()->nullable();
            $table->integer('role_amount')->nullable();
            $table->integer('role_offset')->nullable();
            $table->integer('created_by')->unsigned();
            $table->integer('last_edited_by')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        // Foreign Keys
        Schema::table('event_emails', function($table) {
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('email_type_id')->references('id')->on('email_types')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('last_edited_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_emails');
    }
}
