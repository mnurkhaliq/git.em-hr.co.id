<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingBiayaLainnyaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('training_biaya_lainnya', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('training_id')->nullable();
			$table->string('label')->nullable();
			$table->integer('nominal')->nullable();
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
		Schema::drop('training_biaya_lainnya');
	}

}
