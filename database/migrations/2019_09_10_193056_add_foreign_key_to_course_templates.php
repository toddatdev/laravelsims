<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToCourseTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_templates', function($table) {
            $table->foreign('initial_meeting_room')->references('id')->on('resources')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_templates', function (Blueprint $table) {
            $table->dropForeign(['initial_meeting_room']);
            $table->dropColumn('initial_meeting_room');
        });
    }
}
