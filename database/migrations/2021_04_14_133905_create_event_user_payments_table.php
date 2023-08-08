<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventUserPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_user_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('event_user_id')->unsigned()->unique();
            $table->string('fee_type_descrp', 255);
            $table->decimal('amount_before_coupon', 8, 2);
            $table->string('coupon_code', 25)->nullable();
            $table->decimal('amount_after_coupon', 8, 2);
            $table->tinyInteger('transaction_successful')->default(0);
            $table->integer('created_by')->unsigned();
            $table->integer('last_edited_by')->unsigned();
            $table->timestamps();

            $table->foreign('event_user_id')->references('id')->on('event_user')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('last_edited_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_user_payments');
    }
}
