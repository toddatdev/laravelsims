<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPkCouseOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_option', function (Blueprint $table) {

            //adding increment since composite primary keys are not supported
            $table->increments('id')->first();
            //put back the foreign keys and unique index
            $table-> foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table-> foreign('option_id')->references('id')->on('course_options')->onDelete('cascade');
            $table->unique(['course_id', 'option_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
