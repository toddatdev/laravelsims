<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceSubCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_sub_category', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('resource_category_id')->unsigned();
            $table->string('abbrv', 50);
            $table->string('description', 300);
            $table->timestamp('retire_date')->nullable();
            $table->timestamps();

            /*
             * Add Foreign/Unique/Index
             */
            $table->foreign('resource_category_id')->references('id')->on('resource_category')->onDelete('cascade');
            $table->index('abbrv');
            $table->unique(['resource_category_id', 'abbrv']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resource_sub_category');
    }
}
