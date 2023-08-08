<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnEvaluateeIdToEventUserQseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_user_qse', function (Blueprint $table) {
            $table->dropForeign(['evaluatee_id']);
            $table->dropColumn('evaluatee_id');
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
