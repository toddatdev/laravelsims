<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {

            $table->increments('id');
            $table->string('abbrv', 25);
            $table->string('description', 150);
            $table->integer('location_id')->unsigned();
            $table->integer('resource_category_id')->unsigned();
            $table->integer('resource_sub_category_id')->unsigned()->nullable();
            $table->timestamp('retire_date')->nullable();
            $table->integer('created_by')->unsigned();
            $table->integer('last_edited_by')->unsigned();
            $table->timestamps();

            /*
             * Add Foreign/Unique/Index
             */
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('resource_category_id')->references('id')->on('resource_category')->onDelete('cascade');
            $table->foreign('resource_sub_category_id')->references('id')->on('resource_sub_category')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('last_edited_by')->references('id')->on('users');
            $table->index('abbrv');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resources');

    }
}
