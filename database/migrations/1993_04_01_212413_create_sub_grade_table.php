<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubGradeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sub_grade', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('grade_id');
			$table->string('name');
			$table->string('salary_range');
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
		Schema::drop('sub_grade');
	}

}
