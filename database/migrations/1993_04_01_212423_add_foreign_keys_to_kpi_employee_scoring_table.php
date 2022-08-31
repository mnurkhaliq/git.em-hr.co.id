<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToKpiEmployeeScoringTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('kpi_employee_scoring', function(Blueprint $table)
		{
			$table->foreign('kpi_employee_id')->references('id')->on('kpi_employee')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('kpi_item_id')->references('id')->on('kpi_items')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('kpi_employee_scoring', function(Blueprint $table)
		{
			$table->dropForeign('kpi_employee_scoring_kpi_employee_id_foreign');
			$table->dropForeign('kpi_employee_scoring_kpi_item_id_foreign');
		});
	}

}
