<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_option', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->unsigned();
            $table->integer('site_option_id')->unsigned();
            $table->string('value', 1000)->nullable();
            $table->timestamps();

            /*
            * Add Foreign/Unique/Index
            */
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreign('site_option_id')->references('id')->on('site_options')->onDelete('cascade');
            $table->unique(['site_id', 'site_option_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_option');
    }
}
