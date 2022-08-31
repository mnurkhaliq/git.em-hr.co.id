<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashAdvancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_advance', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id')->nullable();
			$table->string('tujuan')->nullable();
			$table->string('transaction_type')->nullable();
			$table->string('payment_method')->nullable();
			$table->string('nama_pemilik_rekening')->nullable();
			$table->string('no_rekening')->nullable();
			$table->string('nama_bank')->nullable();
			$table->integer('nominal_pembayaran')->nullable();
			$table->integer('status')->nullable();
            $table->dateTime('date_approved')->nullable();
            $table->integer('status_claim')->nullable();
			$table->dateTime('date_claim')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_advance');
    }
}
