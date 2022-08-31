<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestPaySlipgrossItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('request_pay_slipgross_item', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('request_pay_slipgross_id')->nullable();
			$table->integer('tahun')->nullable();
			$table->smallInteger('bulan')->nullable();
			$table->smallInteger('status')->nullable();
			$table->integer('user_id');
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
		Schema::drop('request_pay_slipgross_item');
	}

}
