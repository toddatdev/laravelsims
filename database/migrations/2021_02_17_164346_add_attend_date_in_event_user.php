<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttendDateInEventUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_user', function (Blueprint $table) {

            //add attend_date
            $table->dateTime('attend_date')->nullable()->after('status_id');

            //add who_marked_attended
            $table->integer('who_marked_attend')->unsigned()->nullable()->after('attend_date');
            $table->foreign('who_marked_attend')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_user', function (Blueprint $table) {
            //
        });
    }
}
