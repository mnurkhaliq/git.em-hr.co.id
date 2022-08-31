<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalPlafondTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('medical_plafond', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('medical_type_id');
			$table->integer('position_id');
			$table->integer('nominal');
			$table->text('description', 65535)->nullable();
			$table->timestamps();
			$table->integer('user_created')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('medical_plafond');
	}

}
