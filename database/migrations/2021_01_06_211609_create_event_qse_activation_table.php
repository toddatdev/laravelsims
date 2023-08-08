<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventQseActivationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_qse_activation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned();
            $table->integer('qse_id')->unsigned();
            $table->boolean('activation_state')->default(false);
            $table->integer('last_edited_by')->unsigned();
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')
                ->onDelete('cascade');
            $table->foreign('qse_id')->references('id')->on('qse')
                ->onDelete('cascade');
            $table->foreign('last_edited_by')->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_qse_activation');
    }
}
