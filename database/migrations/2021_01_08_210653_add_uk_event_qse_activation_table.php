<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUkEventQseActivationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_qse_activation', function (Blueprint $table) {
            $table->unique(['event_id', 'qse_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_qse_activation', function (Blueprint $table) {
            $table->dropUnique(['event_id', 'qse_id']);
        });
    }
}
