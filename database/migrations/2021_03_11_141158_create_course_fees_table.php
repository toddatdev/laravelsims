<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_fees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('course_id')->unsigned();
            $table->bigInteger('course_fee_type_id')->unsigned();
            $table->integer('amount');
            $table->tinyInteger('deposit')->default(0);
            $table->integer('created_by')->unsigned();
            $table->integer('last_edited_by')->unsigned();
            $table->date('retire_date')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('course_fee_type_id')->references('id')->on('course_fee_types');
            $table->unique(['course_id', 'course_fee_type_id']);
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('last_edited_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_fees');
    }
}
