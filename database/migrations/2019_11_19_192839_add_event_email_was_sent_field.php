<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventEmailWasSentField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('event_emails', 'was_sent')) {
            Schema::table('event_emails', function (Blueprint $table) {
                $table->integer('was_sent')->tinyint()->after('send_at')->default(0);
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
        if (Schema::hasColumn('event_emails', 'was_sent')) {
            Schema::table('event_emails', function (Blueprint $table) {
                $table->dropColumn('was_sent');
            });
        }
    }
}