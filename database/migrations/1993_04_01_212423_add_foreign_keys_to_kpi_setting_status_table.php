<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToKpiSettingStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('kpi_setting_status', function(Blueprint $table)
		{
			$table->foreign('kpi_setting_scoring_id')->references('id')->on('kpi_setting_scoring')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('kpi_setting_status', function(Blueprint $table)
		{
			$table->dropForeign('kpi_setting_status_kpi_setting_scoring_id_foreign');
		});
	}

}
