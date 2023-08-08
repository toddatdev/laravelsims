<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropRetiredNToQseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE qse DROP FOREIGN KEY q_s_e_s_retired_by_foreign;');
        Schema::table('qse', function (Blueprint $table) {
            $table->dropColumn(['retired_by', 'retired_date']);
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
            $table->unsignedInteger('retired_by')->nullable();
            $table->dateTime('retired_date')->nullable();
        });
    }
}
