<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBccToCourseEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_emails', function (Blueprint $table) {
            $table->string('bcc_roles', 2000)->nullable()->after('cc_other');
            $table->string('bcc_other', 2000)->nullable()->after('bcc_roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_emails', function (Blueprint $table) {
            $table->dropColumn('bcc_roles');
            $table->dropColumn('bcc_other');
        });
    }
}
