<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCabangpicmasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cabangpicmaster', function(Blueprint $table)
		{
			$table->foreign('cabang_id')->references('id')->on('cabang')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('cabangpicmaster', function(Blueprint $table)
		{
			$table->dropForeign('cabangpicmaster_cabang_id_foreign');
		});
	}

}
