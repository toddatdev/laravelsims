<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToEventUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_user', function (Blueprint $table) {
            //
        });

        if (!Schema::hasColumn('event_user', 'status_id')) {
            Schema::table('event_user', function (Blueprint $table) {
                //inititally making this nullable due to existing data, will make required in later migration
                $table->string('request_notes', 400)->after('event_id')->nullable();
                $table->integer('status_id')->unsigned()->after('event_id')->nullable();

                // Foreign Keys
                $table->foreign('status_id')->references('id')->on('event_user_status');
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
        Schema::table('event_user', function (Blueprint $table) {
            $table->dropColumn('status_id');
            $table->dropColumn('request_notes');
            $table->dropForeign(['status_id']);
        });
    }
}
