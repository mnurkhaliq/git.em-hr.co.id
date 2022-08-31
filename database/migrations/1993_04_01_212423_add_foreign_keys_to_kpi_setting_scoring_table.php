<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToKpiSettingScoringTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('kpi_setting_scoring', function(Blueprint $table)
		{
			$table->foreign('kpi_period_id')->references('id')->on('kpi_periods')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('kpi_setting_scoring', function(Blueprint $table)
		{
			$table->dropForeign('kpi_setting_scoring_kpi_period_id_foreign');
		});
	}

}
