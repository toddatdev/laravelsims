<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned();
            $table->string('abbrv', 50);
            $table->string('name', 255);
            $table->string('more_info', 2000)->nullable();
            $table->string('map_url', 2048)->nullable()->comment('Link to web map');
            $table->string('address',100)->nullable();
            $table->string('city',100)->nullable();
            $table->string('state',100)->nullable();
            $table->string('postal_code',100)->nullable();
            $table->integer('display_order')->unsigned();
            $table->boolean('retired')->default(false);
            $table->string('timezone', 50)->comment('Building timezone, in America/New_York format. Look at http://php.net/manual/en/timezones.php for list');
            $table->timestamps();
            $table->softDeletes();

            /*
             * Add Foreign/Unique/Index
             */

            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->index('abbrv');
            $table->unique(['site_id', 'abbrv']);
            $table->index('name');
            $table->unique(['site_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buildings');
    }
}
