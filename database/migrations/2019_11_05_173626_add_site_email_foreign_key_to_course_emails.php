<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSiteEmailForeignKeyToCourseEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_emails', function (Blueprint $table) {
            $table->integer('course_email_id')->unsigned()->nullable()->after('email_type_id');
            $table->integer('edited')->tinyint()->after('role_offset')->default(0);
        });

        // Foreign Keys
        Schema::table('course_emails', function($table) {
            $table->foreign('course_email_id')->references('id')->on('course_emails')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('course_emails', 'site_email_id')) {
            Schema::table('course_emails', function (Blueprint $table) {
                $table->dropForeign(['site_email_id']);
                $table->dropColumn('site_email_id');
            });
        }
    }
}