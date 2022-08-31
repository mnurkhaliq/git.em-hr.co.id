<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensiSettingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('absensi_setting', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('shift', 50)->nullable();
			$table->string('clock_in', 10)->nullable();
			$table->string('clock_out', 10)->nullable();
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
		Schema::drop('absensi_setting');
	}

}
