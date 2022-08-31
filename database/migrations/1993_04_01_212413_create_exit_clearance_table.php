<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExitClearanceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exit_clearance', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->date('join_date')->nullable();
			$table->date('resign_date')->nullable();
			$table->integer('status')->nullable();
			$table->timestamps();
			$table->string('inventory_it_username_pc')->nullable();
			$table->string('inventory_it_password_pc')->nullable();
			$table->string('inventory_it_email')->nullable();
			$table->string('inventory_it_username_arium')->nullable();
			$table->string('inventory_it_password_arium')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('exit_clearance');
	}

}
