<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeImrNullableInEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {

            //must drop FK before you can make it nullable
            $table->dropForeign('events_initial_meeting_room_foreign');

            //make nullable
            $table->integer('initial_meeting_room')->nullable()->unsigned()->change();

            //put back FK
            $table->foreign('initial_meeting_room')->references('id')->on('resources')->onDelete('cascade');

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
            //
        });
    }
}
