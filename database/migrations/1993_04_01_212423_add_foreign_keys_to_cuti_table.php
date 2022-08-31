<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCutiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cuti', function(Blueprint $table)
		{
			$table->foreign('master_cuti_type_id')->references('id')->on('master_cuti_type')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('cuti', function(Blueprint $table)
		{
			$table->dropForeign('cuti_master_cuti_type_id_foreign');
		});
	}

}
