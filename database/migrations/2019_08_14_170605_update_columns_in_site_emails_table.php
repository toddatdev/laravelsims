<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnsInSiteEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_emails', function (Blueprint $table) {
            $table->string('to_roles', 2000)->nullable()->after('body');
            $table->string('to_other', 2000)->nullable()->after('to_roles');
            $table->string('cc_roles', 2000)->nullable()->after('to_other');
            $table->string('cc_other', 2000)->nullable()->after('cc_roles');
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
            $table->dropColumn('to_roles');
            $table->dropColumn('to_other');
            $table->dropColumn('cc_roles');
            $table->dropColumn('cc_other');
        });
    }
}
