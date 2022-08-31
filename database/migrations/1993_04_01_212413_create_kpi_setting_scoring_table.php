<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiSettingScoringTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kpi_setting_scoring', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('kpi_period_id')->unsigned()->nullable()->index('kpi_setting_scoring_kpi_period_id_foreign');
			$table->integer('kpi_module_id')->unsigned()->nullable();
			$table->float('weightage', 10, 0);
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
		Schema::drop('kpi_setting_scoring');
	}

}
