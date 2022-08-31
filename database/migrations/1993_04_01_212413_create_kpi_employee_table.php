<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiEmployeeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kpi_employee', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('structure_organization_custom_id')->unsigned()->nullable();
			$table->integer('kpi_period_id')->unsigned()->nullable()->index('kpi_employee_kpi_period_id_foreign');
			$table->integer('supervisor_id')->unsigned()->nullable();
			$table->date('employee_input_date')->nullable();
			$table->date('supervisor_input_date')->nullable();
			$table->string('employee_feedback')->nullable();
			$table->float('organization_score')->nullable();
			$table->float('manager_score')->nullable();
			$table->float('final_score')->nullable();
			$table->boolean('status')->default(0);
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
		Schema::drop('kpi_employee');
	}

}
