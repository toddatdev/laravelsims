<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeDeleteCourseFeeTypeIdCourseFees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_fees', function (Blueprint $table) {

            $table->dropForeign('course_fees_course_fee_type_id_foreign');
            $table->foreign('course_fee_type_id')
                ->references('id')->on('course_fee_types')
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
        //
    }
}
