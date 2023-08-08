<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profile', function (Blueprint $table) {
            $table->increments('user_profile_id');
            $table->integer('user_id')->unsigned();
            $table->integer('user_profile_answer_id')->unsigned();
            $table->string('comment',2000);
            $table->timestamps();
            $table-> softDeletes();

            $table-> foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table-> foreign('user_profile_answer_id')->references('user_profile_answer_id')->on('user_profile_answers')->onDelete('cascade');
            $table->unique(['user_id', 'user_profile_answer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profile');
    }
}
