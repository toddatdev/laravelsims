<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventUserQSESTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_user_q_s_e_s', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('event_user_id');
            $table->unsignedInteger('course_qse_id');
            $table->unsignedInteger('evaluatee_id')->nullable();
            $table->tinyInteger('complete');
            $table->timestamps();

            $table->foreign('event_user_id')->references('id')->on('event_user')
                ->onDelete('cascade');

            $table->foreign('course_qse_id')->references('id')->on('q_s_e_s')
                ->onDelete('cascade');

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
        Schema::dropIfExists('event_user_q_s_e_s');
    }
}
