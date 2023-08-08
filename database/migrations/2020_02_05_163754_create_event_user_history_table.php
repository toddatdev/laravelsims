<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventUserHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('event_user_history')) {
            Schema::create('event_user_history', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('event_user_id')->unsigned();
                $table->integer('action_id')->unsigned();
                $table->string('display_text', 400);
                $table->integer('action_by')->unsigned();
                $table->timestamps();

                $table->foreign('event_user_id')->references('id')->on('event_user');
                $table->foreign('action_id')->references('id')->on('event_user_history_actions');
                $table->foreign('action_by')->references('id')->on('users');
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
        if (Schema::hasTable('event_user_history')) {
            Schema::dropIfExists('event_user_history');
        };
    }
}
