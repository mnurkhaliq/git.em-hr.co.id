<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToUsersBranchVisitTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users_branch_visit', function(Blueprint $table)
		{
			$table->foreign('cabang_id')->references('id')->on('cabang')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users_branch_visit', function(Blueprint $table)
		{
			$table->dropForeign('users_branch_visit_cabang_id_foreign');
			$table->dropForeign('users_branch_visit_user_id_foreign');
		});
	}

}
