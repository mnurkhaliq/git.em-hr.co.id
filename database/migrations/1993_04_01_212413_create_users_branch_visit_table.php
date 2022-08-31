<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersBranchVisitTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_branch_visit', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable()->index('users_branch_visit_user_id_foreign');
			$table->integer('cabang_id')->unsigned()->nullable()->index('users_branch_visit_cabang_id_foreign');
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
		Schema::drop('users_branch_visit');
	}

}
