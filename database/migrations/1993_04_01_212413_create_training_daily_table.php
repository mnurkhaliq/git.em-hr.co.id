<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingDailyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('training_daily', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('training_id');
			$table->date('date');
			$table->integer('daily_plafond')->nullable();
			$table->integer('daily')->nullable();
			$table->integer('daily_approved')->nullable();
			$table->text('note', 65535)->nullable();
			$table->text('file_struk', 65535)->nullable();
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
		Schema::drop('training_daily');
	}

}
