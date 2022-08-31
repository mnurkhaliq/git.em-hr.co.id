<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollNpwpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_npwp', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_payroll_npwp')->nullable();
			$table->string('label');
			$table->string('value')->nullable();
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
		Schema::drop('payroll_npwp');
	}

}
