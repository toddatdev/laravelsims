<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table -> increments('id');
            $table -> string('abbrv', 50);
            $table -> index('abbrv');
            $table -> string('name', 255);
            $table -> string('organization_name', 255) -> unique() ->comment('for internal reference, e.g. how SIMS staff refers to client');
            $table -> string('email') -> unique();
            $table -> timestamps();
            $table -> softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sites');
    }
}
