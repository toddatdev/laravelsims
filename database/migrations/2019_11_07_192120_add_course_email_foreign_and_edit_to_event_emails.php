<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCourseEmailForeignAndEditToEventEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('event_emails', 'course_email_id')) {
            Schema::table('event_emails', function (Blueprint $table) {
                $table->integer('course_email_id')->unsigned()->nullable()->after('email_type_id');
                $table->integer('edited')->tinyint()->after('send_at')->default(0);
            });

            // Foreign Keys
            Schema::table('event_emails', function($table) {
                $table->foreign('course_email_id')->references('id')->on('course_emails')->onDelete('cascade');
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
        Schema::table('event_emails', function (Blueprint $table) {
            $table->dropForeign(['course_email_id']);
            $table->dropColumn('course_email_id');
            $table->dropColumn('edited');
        });
    }
}
