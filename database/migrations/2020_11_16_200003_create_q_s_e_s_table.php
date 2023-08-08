<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQSESTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('q_s_e_s', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_content_id');
            $table->unsignedInteger('qse_type_id');
            $table->unsignedInteger('activation_type_id');
            $table->boolean('activation_state')->default(false);
            $table->integer('minutes')->nullable();
            $table->string('automatic_activation_time', 1)->nullable();
            $table->unsignedInteger('evaluator_type_id')->nullable();
            $table->unsignedInteger('evaluatee_type_id')->nullable();
            $table->unsignedInteger('presentation_type_id');
            $table->boolean('allow_multiple_submits')->default(false)->comment('1=yes, 0=no');
            $table->boolean('random')->default(false)->comment('1=yes, 0=no');
            $table->integer('threshold')->default(0);
            $table->unsignedInteger('feedback_type_id')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('last_edited_by');
            $table->unsignedInteger('retired_by')->nullable();
            $table->dateTime('retired_date')->nullable();
            $table->timestamps();

            $table->foreign('course_content_id')->references('id')->on('course_contents')
                ->onDelete('cascade');

            $table->foreign('qse_type_id')->references('id')->on('q_s_e_types')
                ->onDelete('cascade');

            $table->foreign('activation_type_id')->references('id')->on('activation_types')
                ->onDelete('cascade');

            $table->foreign('evaluator_type_id')->references('id')->on('evaluator_types')
                ->onDelete('cascade');

            $table->foreign('evaluatee_type_id')->references('id')->on('evaluatee_types')
                ->onDelete('cascade');

            $table->foreign('presentation_type_id')->references('id')->on('presentation_types')
                ->onDelete('cascade');

            $table->foreign('feedback_type_id')->references('id')->on('feedback_types')
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
        Schema::dropIfExists('q_s_e_s');
    }
}
