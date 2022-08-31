<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCutiKaryawanDatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cuti_karyawan_dates', function(Blueprint $table)
		{
			$table->foreign('cuti_karyawan_id')->references('id')->on('cuti_karyawan')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('cuti_karyawan_dates', function(Blueprint $table)
		{
			$table->dropForeign('cuti_karyawan_dates_cuti_karyawan_id_foreign');
		});
	}

}
