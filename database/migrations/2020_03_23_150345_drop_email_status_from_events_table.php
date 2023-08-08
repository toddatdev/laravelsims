<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropEmailStatusFromEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {

            if (Schema::hasColumn('events', 'email_status')) {
                $table->dropColumn('email_status');
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'email_status')) {
                Schema::table('events', function (Blueprint $table) {
                    $table->string('email_status', 255)->after('resolved')->nullable();
                });
            }
        });
    }
}
