<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('section', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('directorate_id')->nullable();
			$table->integer('division_id')->nullable();
			$table->integer('department_id')->nullable();
			$table->string('name')->nullable();
			$table->text('description', 65535)->nullable();
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
		Schema::drop('section');
	}

}
