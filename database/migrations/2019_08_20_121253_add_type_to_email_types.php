<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToEmailTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * 1 = Site
         * 2 = Course
         * 3 = Event
         */
        Schema::table('email_types', function (Blueprint $table) {
            $table->integer('type')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_types', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
