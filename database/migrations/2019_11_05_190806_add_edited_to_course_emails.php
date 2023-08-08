<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEditedToCourseEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('course_emails', 'edited')) {
            Schema::table('course_emails', function (Blueprint $table) {
                $table->integer('edited')->tinyint()->after('role_offset')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('course_emails', 'edited')) {
            Schema::table('course_emails', function (Blueprint $table) {
                $table->dropColumn('edited');
            });
        }
    }
}
