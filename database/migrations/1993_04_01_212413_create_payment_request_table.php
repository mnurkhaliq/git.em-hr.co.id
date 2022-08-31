<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_request', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->string('tujuan')->nullable();
			$table->string('transaction_type')->nullable();
			$table->string('payment_method')->nullable();
			$table->timestamps();
			$table->string('nama_pemilik_rekening')->nullable();
			$table->string('no_rekening')->nullable();
			$table->string('nama_bank')->nullable();
			$table->integer('nominal_pembayaran')->nullable();
			$table->integer('status')->nullable();
			$table->integer('is_approved_atasan')->nullable();
			$table->dateTime('date_approved_atasan')->nullable();
			$table->integer('approved_atasan_id')->nullable();
			$table->integer('is_proposal_approved')->nullable();
			$table->integer('is_proposal_verification_approved')->nullable();
			$table->integer('is_payment_approved')->nullable();
			$table->smallInteger('proposal_approval_approved')->nullable();
			$table->dateTime('proposal_approval_date')->nullable();
			$table->integer('proposal_approval_id')->nullable();
			$table->smallInteger('proposal_verification_approved')->nullable();
			$table->dateTime('proposal_verification_date')->nullable();
			$table->integer('proposal_verification_id')->nullable();
			$table->smallInteger('payment_approval_approved')->nullable();
			$table->dateTime('payment_approval_date')->nullable();
			$table->integer('payment_approval_id')->nullable();
			$table->smallInteger('approve_direktur')->nullable();
			$table->integer('approve_direktur_id')->nullable();
			$table->text('note_pembatalan', 65535)->nullable();
			$table->dateTime('approve_direktur_date')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payment_request');
	}

}
