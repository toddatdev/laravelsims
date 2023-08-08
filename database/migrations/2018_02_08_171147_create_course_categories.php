<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('abbrv', 25);
            $table->string('name', 100);
            $table->integer('course_category_group_id')->unsigned();
            $table-> integer('site_id')->unsigned();
            $table->timestamps();

            /*
            * Add Foreign/Unique/Index
            */
            $table->foreign('course_category_group_id')->references('id')->on('course_category_groups')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->unique(['site_id', 'abbrv']);
            $table->index('abbrv');
            $table->index('name');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_categories');
    }
}
