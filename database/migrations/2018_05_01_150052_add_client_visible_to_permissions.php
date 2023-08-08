<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClientVisibleToPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            if(Schema::hasTable('permissions')){
                //add column for URL root
                $table->integer('client_visible')->tinyint()->after('sort')->default(1)->comment('Set to 1 if the clients can see this permission on the web site and add it to roles. Defaults to 1, which indicates yes they can.');
            }
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
