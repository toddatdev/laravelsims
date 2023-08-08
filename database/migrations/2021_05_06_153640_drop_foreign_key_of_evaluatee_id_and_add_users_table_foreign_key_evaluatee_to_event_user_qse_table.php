<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignKeyOfEvaluateeIdAndAddUsersTableForeignKeyEvaluateeToEventUserQseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_user_qse', function (Blueprint $table) {
            $table->dropForeign('event_user_q_s_e_s_event_user_id_foreign');
            $table->dropForeign('event_user_q_s_e_s_evaluatee_id_foreign');
            $table->foreign('evaluatee_id')->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_user_qse', function (Blueprint $table) {
            //
        });
    }
}
