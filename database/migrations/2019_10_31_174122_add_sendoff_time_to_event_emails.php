<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendoffTimeToEventEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_emails', function (Blueprint $table) {
            $table->timestamp('send_at')->nullable()->after('role_offset');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_emails', function (Blueprint $table) {
            $table->dropColumn('send_at');
        });
    }
}
