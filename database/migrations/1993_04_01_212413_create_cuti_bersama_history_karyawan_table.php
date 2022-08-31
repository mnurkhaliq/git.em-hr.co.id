<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCutiBersamaHistoryKaryawanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cuti_bersama_history_karyawan', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('cuti_bersama_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('cuti_bersama_old')->nullable();
			$table->integer('cuti_bersama_new')->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cuti_bersama_history_karyawan');
	}

}
