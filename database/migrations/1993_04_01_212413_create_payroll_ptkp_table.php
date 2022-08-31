<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollPtkpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_ptkp', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('bujangan_wanita')->nullable();
			$table->integer('menikah')->nullable();
			$table->integer('menikah_anak_1')->nullable();
			$table->integer('menikah_anak_2')->nullable();
			$table->integer('menikah_anak_3')->nullable();
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
		Schema::drop('payroll_ptkp');
	}

}
