<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned();
            $table->integer('building_id')->unsigned();
            $table->string('abbrv', 50);
            $table->string('name', 255);
            $table->string('more_info', 2000)->nullable();
            $table->string('directions_url', 2048)->nullable()->comment('Link to an item on the web that will help with directions.');
            $table->decimal('display_order', 5, 2);
            $table->string('html_color', 30)->nullable()->comment('Can be many different types of color respresentation, e.g. #34f16c, rgba(123, 231, 19, 0.75), etc.');
            $table->timestamp('retire_date')->nullable()->comment('Date and time this location was retired. Used to stop display on the site.');
            $table->timestamps();
            $table->softDeletes();

            /*
             * Add Foreign/Unique/Index
             */
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
            $table->index('abbrv');
            $table->unique(['building_id', 'abbrv']);
            $table->index('name');
            $table->unique(['building_id', 'display_order']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
