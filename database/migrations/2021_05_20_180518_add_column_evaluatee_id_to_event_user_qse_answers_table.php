<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnEvaluateeIdToEventUserQseAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_user_qse_answers', function (Blueprint $table) {
            $table->unsignedInteger('evaluatee_id')->nullable();

            $table->foreign('evaluatee_id')
                ->references('id')
                ->on('users')
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
        Schema::table('event_user_qse_answers', function (Blueprint $table) {
            //
        });
    }
}
