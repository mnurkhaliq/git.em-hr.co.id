<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollEarningsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payroll_earnings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->nullable();
			$table->smallInteger('taxable')->default(1);
			$table->timestamps();
			$table->integer('user_created')->nullable();
			$table->integer('project_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payroll_earnings');
	}

}
