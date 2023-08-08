<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventUserHistoryActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('event_user_history_actions')) {
            Schema::create('event_user_history_actions', function (Blueprint $table) {
                $table->increments('id');
                $table->string('action', 100);
                $table->timestamps();
                $table->unique(['action']);
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
        if (Schema::hasTable('event_user_history_actions')) {
            Schema::dropIfExists('event_user_history_actions');
        };
    }
}
