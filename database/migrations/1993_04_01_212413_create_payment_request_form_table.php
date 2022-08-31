<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRequestFormTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_request_form', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('payment_request_id')->nullable();
			$table->text('description', 65535)->nullable();
			$table->integer('quantity')->nullable();
			$table->integer('estimation_cost')->nullable();
			$table->integer('amount')->nullable();
			$table->timestamps();
			$table->text('note', 65535)->nullable();
			$table->integer('nominal_approved')->nullable();
			$table->text('file_struk', 65535)->nullable();
			$table->string('type_form', 25);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payment_request_form');
	}

}
