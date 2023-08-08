<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAndChangeColumnEvaluatorTypeIdAndEvaluateeTypeIdToQseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE qse DROP FOREIGN KEY q_s_e_s_evaluator_type_id_foreign');
        \DB::statement('ALTER TABLE qse DROP FOREIGN KEY q_s_e_s_evaluatee_type_id_foreign');

        Schema::table('qse', function (Blueprint $table) {
//            $table->dropForeign(['evaluator_type_id', 'evaluatee_type_id']);
            $table->dropColumn(['evaluator_type_id', 'evaluatee_type_id']);
            $table->string('evaluatee_roles')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qse', function (Blueprint $table) {
            $table->dropColumn('evaluatee_roles');
            $table->unsignedInteger('evaluator_type_id')->nullable();
            $table->unsignedInteger('evaluatee_type_id')->nullable();

            $table->foreign('evaluator_type_id')->references('id')->on('evaluator_types')
                ->onDelete('cascade');

            $table->foreign('evaluatee_type_id')->references('id')->on('evaluatee_types')
                ->onDelete('cascade');
        });
    }
}
