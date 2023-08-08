<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfileAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profile_answers', function (Blueprint $table) {
            $table->increments('user_profile_answer_id');
            $table->integer('question_id')->unsigned();
            $table->integer('parent_id')->unsigned();
            $table->string('answer_text',200);
            $table->tinyInteger('comment_needed');
            $table->integer('display_order');
            $table->timestamp('retire_date')->nullable();
            $table->timestamps();

            $table->foreign('question_id')->references('question_id')->on('user_profile_questions')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profile_answers');
    }
}
