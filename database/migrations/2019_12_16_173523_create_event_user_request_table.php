<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventUserRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('event_user_requests')) {
            Schema::create('event_user_requests', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('event_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->integer('role_id')->unsigned();
                $table->text('comments')->nullable();
                $table->integer('approved_by')->unsigned()->nullable();
                $table->timestamp('approved_on')->nullable();
                $table->integer('denied_by')->unsigned()->nullable();
                $table->timestamp('denied_on')->nullable();
                $table->integer('waitlisted_by')->unsigned()->nullable();
                $table->timestamp('waitlisted_on')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });

            // Foreign Keys
            Schema::table('event_user_requests', function($table) {
                $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('denied_by')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('waitlisted_by')->references('id')->on('users')->onDelete('cascade');
            });                        
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('event_user_requests')) {
            Schema::dropIfExists('event_user_requests');
        }
    }
}