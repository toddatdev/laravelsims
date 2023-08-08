<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQSEAnswerTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('q_s_e_answer_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('abbrv', 100);
            $table->string('description', 2000);
            $table->boolean('has_response')->default(false);
            $table->unsignedInteger('input_type_id');
            $table->timestamps();

            $table->foreign('input_type_id')->references('id')->on('input_types')
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
        Schema::dropIfExists('q_s_e_answer_types');
    }
}
