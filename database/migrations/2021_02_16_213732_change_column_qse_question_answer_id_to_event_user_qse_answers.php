<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnQseQuestionAnswerIdToEventUserQseAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_user_qse_answers', function (Blueprint $table) {
            $table->unsignedInteger('qse_question_answer_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_user_qse_answer', function (Blueprint $table) {
            $table->unsignedInteger('qse_question_answer_id')->nullable(false)->change();
        });
    }
}
