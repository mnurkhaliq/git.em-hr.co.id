<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRequestBensinTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_request_bensin', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->integer('payment_request_id')->nullable();
			$table->date('tanggal')->nullable();
			$table->integer('odo_start')->nullable();
			$table->integer('odo_end')->nullable();
			$table->integer('liter')->nullable();
			$table->integer('cost')->nullable();
			$table->timestamps();
			$table->integer('payment_request_form_id')->unsigned()->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payment_request_bensin');
	}

}
