<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToCourseTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_templates', function (Blueprint $table) {
            $table->renameColumn('course_instance_id', 'course_id');
            $table->string('name', 50)->change();
            $table->string('expectations', 4000)->nullable()->after('internal_comments');
            $table->string('public_comments', 50)->change();
            $table->string('internal_comments', 4000)->change();
            $table->tinyInteger('sims_spec_needed')->after('color');
            $table->tinyInteger('special_requirements')->after('sims_spec_needed');
            $table->integer('last_edited_by')->unsigned()->after('created_by')->default(1);

            $table->unique(['course_id', 'name']);
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
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
        Schema::table('course_templates', function (Blueprint $table) {
            //
        });
    }
}
