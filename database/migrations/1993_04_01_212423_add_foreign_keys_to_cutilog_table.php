<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCutilogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cutilog', function(Blueprint $table)
		{
			$table->foreign('usercuti_id')->references('id')->on('user_cuti')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('cutilog', function(Blueprint $table)
		{
			$table->dropForeign('cutilog_usercuti_id_foreign');
		});
	}

}
