<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOldClassIdToCourseInstances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_instances', function (Blueprint $table) {
            $table->integer('old_class_id')->nullable()->after('old_sims_id')->comment('Need to keep this to link back to original class without recurrence.');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_instances', function (Blueprint $table) {
            //
        });
    }
}
