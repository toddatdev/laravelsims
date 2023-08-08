<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentIdAuthNetTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auth_net_transactions', function (Blueprint $table) {
            $table->bigInteger('event_user_payment_id')->unsigned()->unique()->after('id');
            $table->foreign('event_user_payment_id')->references('id')->on('event_user_payments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auth_net_transactions', function (Blueprint $table) {
            //
        });
    }
}
