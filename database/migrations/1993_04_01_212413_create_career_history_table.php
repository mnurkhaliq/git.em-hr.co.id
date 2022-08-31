<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareerHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('career_history', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('cabang_id');
			$table->integer('structure_organization_custom_id');
			$table->dateTime('effective_date');
			$table->timestamps();
			$table->text('job_desc', 65535);
			$table->text('status', 65535);
			$table->dateTime('start_date')->nullable();
			$table->dateTime('end_date')->nullable();
			$table->integer('sub_grade_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('career_history');
	}

}
