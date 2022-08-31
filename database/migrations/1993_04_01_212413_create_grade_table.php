<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('grade', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('salary_range');
			$table->timestamps();
			$table->integer('user_created')->nullable();
			$table->text('benefit', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('grade');
	}

}
