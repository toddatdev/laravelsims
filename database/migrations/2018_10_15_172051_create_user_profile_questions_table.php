<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfileQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profile_questions', function (Blueprint $table) {
            $table->increments('question_id');
            $table->string('question_text',200);
            $table->integer('site_id')->unsigned();
            $table->string('response_type',100);
            $table->integer('display_order');
            $table->timestamp('retire_date')->nullable();
            $table->timestamps();

            $table-> foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profile_questions');
    }
}
