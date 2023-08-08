<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventUserQSEAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_user_q_s_e_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('event_user_qse_id');
            $table->unsignedInteger('course_qse_question_id');
            $table->unsignedInteger('qse_question_answer_id');
            $table->timestamps();

            $table->foreign('event_user_qse_id')->references('id')->on('event_user_q_s_e_s')
                ->onDelete('cascade');

            $table->foreign('course_qse_question_id')->references('id')->on('q_s_e_questions')
                ->onDelete('cascade');

            $table->foreign('qse_question_answer_id')->references('id')->on('q_s_e_question_answers')
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
        Schema::dropIfExists('event_user_q_s_e_answers');
    }
}
