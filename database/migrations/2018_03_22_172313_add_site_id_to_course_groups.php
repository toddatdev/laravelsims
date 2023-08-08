<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSiteIdToCourseGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_category_groups', function (Blueprint $table) {
            if(Schema::hasTable('course_category_groups')){
                //add column for URL root
                $table->integer('site_id')->unsigned()->after('description');
                $table->string('abbrv', 25)->after('id');

                /*
                * Add Foreign/Unique/Index
                */
                $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
                $table->unique(['site_id', 'abbrv']);
                $table->index('abbrv');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_category_groups', function (Blueprint $table) {
            //
        });
    }
}
