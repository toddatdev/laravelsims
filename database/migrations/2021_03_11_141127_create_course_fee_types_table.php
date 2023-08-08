<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseFeeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_fee_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('site_id')->unsigned();
            $table->string('description', 255);
            $table->integer('created_by')->unsigned();
            $table->integer('last_edited_by')->unsigned();
            $table->date('retire_date')->nullable();
            $table->timestamps();

            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->unique(['site_id', 'description']);
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
        Schema::dropIfExists('course_fee_types');
    }
}
