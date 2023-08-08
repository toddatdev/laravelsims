<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventUserQSECommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_user_q_s_e_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('event_user_qse_answer_id');
            $table->string('comment', 2000);
            $table->timestamps();

            $table->foreign('event_user_qse_answer_id')->references('id')->on('event_user_q_s_e_answers')
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
        Schema::dropIfExists('event_user_q_s_e_comments');
    }
}
