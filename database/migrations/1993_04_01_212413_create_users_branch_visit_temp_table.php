<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersBranchVisitTempTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_branch_visit_temp', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id_temp')->nullable();
			$table->integer('cabang_id')->unsigned()->nullable();
			$table->timestamps();
			$table->integer('user_created')->unsigned()->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users_branch_visit_temp');
	}

}
