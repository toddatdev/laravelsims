<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyCourseOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_options', function (Blueprint $table) {

            $table->integer('input_type_id')->unsigned()->after('description');

            //set keys and references.
            $table->foreign('input_type_id')->references('id')->on('input_types')->onDelete('cascade');
            $table->unique(['description']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_options', function (Blueprint $table) {
            //
        });
    }
}
