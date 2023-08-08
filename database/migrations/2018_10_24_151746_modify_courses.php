<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->renameColumn('instructor_report_time', 'fac_report');
            $table->renameColumn('instructor_leave_time', 'fac_leave');
            $table->integer('last_edited_by')->after('created_by');
            $table->dropColumn('instructor_report_time_before_after');
            $table->dropColumn('instructor_leave_time_before_after');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            //
        });
    }
}
