<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollPphTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_pph', function(Blueprint $table)
		{
			$table->increments('id');
			$table->bigInteger('batas_bawah')->nullable();
			$table->bigInteger('batas_atas')->nullable();
			$table->integer('tarif')->nullable();
			$table->integer('pajak_minimal')->nullable();
			$table->integer('akumulasi_pajak')->nullable();
			$table->string('kondisi_lain')->nullable();
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
		Schema::drop('payroll_pph');
	}

}
