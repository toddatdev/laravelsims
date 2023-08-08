<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInputTypeValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('input_type_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('input_type_id')->unsigned();
            $table->string('value', 100);
            $table->timestamps();

            $table->foreign('input_type_id')->references('id')->on('input_types')->onDelete('cascade');
            $table->unique(['input_type_id', 'value']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('input_type_values');
    }
}
