<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableAnswerTypeIdToQseQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qse_questions', function (Blueprint $table) {
            $table->unsignedInteger('answer_type_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qse_questions', function (Blueprint $table) {
            $table->unsignedInteger('answer_type_id')->change();
        });
    }
}
