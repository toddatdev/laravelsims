<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropForeignKeyCourseTemplateResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_template_resources', function (Blueprint $table) {
            $table->dropForeign('course_template_resources_resource_template_type_foreign');
            $table->foreign('resource_identifier_type')->references('id')->on('resource_identifier_types');
        });

        Schema::table('course_templates', function (Blueprint $table) {
            $table->dropForeign('course_templates_initial_meeting_room_type_foreign');
            $table->foreign('initial_meeting_room_type')->references('id')->on('resource_identifier_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_template_resources', function (Blueprint $table) {
            //
        });
    }
}
