<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResourceTypeIdToResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Add resource_type_id to the resources table
        Schema::table('resources', function (Blueprint $table) {
            $table->integer('resource_type_id')->unsigned()->after('location_id');

            //set keys and references.
            $table->foreign('resource_type_id')->references('id')->on('resource_types')->onDelete('cascade');
        });
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
