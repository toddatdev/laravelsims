<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventWaitListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('event_wait_list')) {
            Schema::create('event_wait_list', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('event_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->integer('role_id')->unsigned();
                $table->integer('enrolled_by')->unsigned()->nullable();
                $table->timestamp('enrolled_on')->nullable();
                $table->integer('delete_by')->unsigned()->nullable();                
                $table->timestamps();
                $table->softDeletes();
            });

            // Foreign Keys
            Schema::table('event_wait_list', function($table) {
                $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->foreign('enrolled_by')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('delete_by')->references('id')->on('users')->onDelete('cascade');
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
        if (Schema::hasTable('event_wait_list')) {
            Schema::dropIfExists('event_wait_list');
        }
    }
}
