<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shift', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('workdays');
			$table->integer('is_holiday');
			$table->integer('is_collective')->default(0);
			$table->timestamps();
			$table->integer('branch_id')->nullable();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shift');
	}

}
