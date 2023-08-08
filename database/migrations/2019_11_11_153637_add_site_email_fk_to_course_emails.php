<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSiteEmailFkToCourseEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('course_emails', 'course_email_id')) {
            // Drop wrong fk and column
            Schema::table('course_emails', function (Blueprint $table) {
                $table->dropForeign(['course_email_id']);
                $table->dropColumn('course_email_id');
            });
            
            // Add Correct
            Schema::table('course_emails', function (Blueprint $table) {
                $table->integer('site_email_id')->unsigned()->nullable()->after('email_type_id');
            });

            // Foreign Keys
            Schema::table('course_emails', function($table) {
                $table->foreign('site_email_id')->references('id')->on('site_emails')->onDelete('cascade');
            });
        }else if (Schema::hasColumn('course_emails', 'site_email_id')) {
            // Do nothing, table correct
        }
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
