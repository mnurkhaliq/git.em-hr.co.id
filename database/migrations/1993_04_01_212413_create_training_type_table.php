<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('training_type', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('name', 65535)->nullable();
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
		Schema::drop('training_type');
	}

}
