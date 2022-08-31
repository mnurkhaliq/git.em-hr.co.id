<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCabangpicTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cabangpic', function(Blueprint $table)
		{
			$table->foreign('cabangpicmaster_id')->references('id')->on('cabangpicmaster')->onUpdate('CASCADE')->onDelete('CASCADE');
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
		Schema::table('cabangpic', function(Blueprint $table)
		{
			$table->dropForeign('cabangpic_cabangpicmaster_id_foreign');
			$table->dropForeign('cabangpic_user_id_foreign');
		});
	}

}
