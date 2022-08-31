<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeaportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seaports', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code', 191);
			$table->string('name', 191);
			$table->string('cityCode', 191);
			$table->string('cityName', 191);
			$table->string('countryName', 191)->nullable();
			$table->string('countryCode', 191)->nullable();
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
		Schema::drop('seaports');
	}

}
