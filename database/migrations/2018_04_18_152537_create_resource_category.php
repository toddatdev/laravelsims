<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_category', function (Blueprint $table) {

            $table->increments('id');
            $table->string('abbrv', 50);
            $table->string('description', 300);
            $table->integer('site_id')->unsigned();
            $table->timestamp('retire_date')->nullable();
            $table->timestamps();

            /*
             * Add Foreign/Unique/Index
             */
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->index('abbrv');
            $table->unique(['site_id', 'abbrv']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resource_category');
    }
}
