<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('airports', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('code', 50)->nullable();
			$table->string('name', 200)->nullable();
			$table->string('cityCode', 50)->nullable();
			$table->string('cityName', 200)->nullable();
			$table->string('countryName', 200)->nullable();
			$table->string('countryCode', 200)->nullable();
			$table->string('timezone', 8)->nullable();
			$table->string('lat', 32)->nullable();
			$table->string('lon', 32)->nullable();
			$table->integer('numAirports')->nullable();
			$table->enum('city', array('true','false'))->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('airports');
	}

}
