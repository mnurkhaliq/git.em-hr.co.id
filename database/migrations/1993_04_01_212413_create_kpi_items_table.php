<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kpi_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('kpi_setting_scoring_id')->unsigned()->nullable()->index('kpi_items_kpi_setting_scoring_id_foreign');
			$table->integer('structure_organization_custom_id')->unsigned()->nullable();
			$table->string('name');
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
		Schema::drop('kpi_items');
	}

}
