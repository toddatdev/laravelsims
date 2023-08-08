<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeleteToEventResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_resources', function($table) {
            $table->softDeletes();
            $table->tinyInteger('isIMR')->after('teardown_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_resources', function($table) {
            $table->dropSoftDeletes();
            $table->drop('isIMR');
        });
    }
}
