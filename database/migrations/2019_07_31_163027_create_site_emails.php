<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned();
            $table->integer('email_type_id')->unsigned();
            $table->string('label', 256);
            $table->string('subject', 400);
            $table->text('body');
            $table->integer('created_by')->unsigned();
            $table->integer('last_edited_by')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        // Foreign Keys
        Schema::table('site_emails', function($table) {
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreign('email_type_id')->references('id')->on('email_types')->onDelete('cascade');
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
        Schema::dropIfExists('site_emails');
    }
}
