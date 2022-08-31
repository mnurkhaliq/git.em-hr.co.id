<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiEmployeeScoringTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kpi_employee_scoring', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('kpi_employee_id')->unsigned()->nullable()->index('kpi_employee_scoring_kpi_employee_id_foreign');
			$table->integer('kpi_item_id')->unsigned()->nullable()->index('kpi_employee_scoring_kpi_item_id_foreign');
			$table->float('self_score')->nullable();
			$table->float('supervisor_score', 10, 0)->nullable();
			$table->string('justification')->nullable();
			$table->string('comment')->nullable();
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
		Schema::drop('kpi_employee_scoring');
	}

}
