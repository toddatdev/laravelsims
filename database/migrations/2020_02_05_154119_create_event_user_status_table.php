<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventUserStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('event_user_status')) {
            Schema::create('event_user_status', function (Blueprint $table) {

                $table->increments('id');
                $table->string('status', 100);
                $table->timestamps();
                $table->unique(['status']);
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
        if (Schema::hasTable('event_user_status')) {
            Schema::dropIfExists('event_user_status');
        };
    }
}
