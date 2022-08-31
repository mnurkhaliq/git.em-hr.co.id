<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiPeriodsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kpi_periods', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('start_date');
			$table->date('end_date');
			$table->smallInteger('min_rate');
			$table->smallInteger('max_rate');
			$table->integer('project_id')->nullable();
			$table->boolean('status');
			$table->boolean('is_lock');
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
		Schema::drop('kpi_periods');
	}

}
