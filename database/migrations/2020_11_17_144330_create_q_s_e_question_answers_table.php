<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQSEQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('q_s_e_question_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('qse_question_id');
            $table->string('text', 4000);
            $table->integer('display_order');
            $table->boolean('correct')->default(false);
            $table->string('feedback', 4000)->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('last_edited_by');
            $table->unsignedInteger('retired_by')->nullable();
            $table->unsignedInteger('retired_date')->nullable();
            $table->timestamps();

            $table->foreign('qse_question_id')->references('id')->on('q_s_e_questions')
                ->onDelete('cascade');

            $table->foreign('created_by')->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('last_edited_by')->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('retired_by')->references('id')->on('users')
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
        Schema::dropIfExists('q_s_e_question_answers');
    }
}
