<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToSiteEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_emails', function (Blueprint $table) {
            $table->integer('time_amount')->nullable()->after('cc_other');
            $table->integer('time_type')->nullable()->after('time_amount');
            $table->integer('time_offset')->nullable()->after('time_type'); // b_start || a_start || b_end || a _end
            $table->integer('role_id')->unsigned()->nullable()->after('time_offset');
            $table->integer('role_amount')->nullable()->after('role_id');
            $table->integer('role_offset')->nullable()->after('role_amount'); // < || > amount given
        });

        // Foreign Keys
        Schema::table('site_emails', function($table) {
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_emails', function (Blueprint $table) {
            $table->dropColumn('time_amount');
            $table->dropColumn('time_type');
            $table->dropColumn('time_offset');
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            $table->dropColumn('role_amount');
            $table->dropColumn('role_offset');
        });
    }
}
