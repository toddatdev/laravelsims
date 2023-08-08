<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('event_role_id')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->timestamps();

            /*
            * Add Foreign/Unique/Index
            */
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('event_role_id')->references('id')->on('event_roles')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_users');

    }
}