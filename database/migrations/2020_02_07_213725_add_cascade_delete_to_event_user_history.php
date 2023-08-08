<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeDeleteToEventUserHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_user_history', function (Blueprint $table) {
            $table->dropForeign('event_user_history_event_user_id_foreign');
            $table->foreign('event_user_id')->references('id')->on('event_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_user_history', function (Blueprint $table) {
            //
        });
    }
}
