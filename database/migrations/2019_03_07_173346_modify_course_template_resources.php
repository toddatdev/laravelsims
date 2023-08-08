<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyCourseTemplateResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_template_resources', function (Blueprint $table) {

            $table->renameColumn('resource_template_type', 'resource_identifier_type');
            $table->integer('last_edited_by')->unsigned()->after('created_by')->default(1);

            $table->foreign('course_template_id')->references('id')->on('course_templates')->onDelete('cascade');
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
        Schema::table('course_template_resources', function (Blueprint $table) {
            //
        });
    }
}
