<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQSEQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('q_s_e_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_qse_id');
            $table->string('text', 4000);
            $table->integer('display_order');
            $table->unsignedInteger('answer_type_id');
            $table->boolean('required')->default(false);
            $table->string('likert_caption', 100)->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('last_edited_by')->nullable();
            $table->unsignedInteger('published_by')->nullable();
            $table->dateTime('publish_date')->nullable();
            $table->unsignedInteger('retired_by')->nullable();
            $table->dateTime('retired_date')->nullable();
            $table->timestamps();

            $table->foreign('course_qse_id')->references('id')->on('q_s_e_s')
                ->onDelete('cascade');

            $table->foreign('answer_type_id')->references('id')->on('q_s_e_answer_types')
                ->onDelete('cascade');

            $table->foreign('created_by')->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('last_edited_by')->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('published_by')->references('id')->on('users')
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
        Schema::dropIfExists('q_s_e_questions');
    }
}
