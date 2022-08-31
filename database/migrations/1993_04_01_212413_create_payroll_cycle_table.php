<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollCycleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_cycle', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('key_name', 191)->nullable();
			$table->string('label', 191)->nullable();
			$table->boolean('start_date');
			$table->boolean('end_date');
			$table->integer('project_id')->nullable();
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
		Schema::drop('payroll_cycle');
	}

}
