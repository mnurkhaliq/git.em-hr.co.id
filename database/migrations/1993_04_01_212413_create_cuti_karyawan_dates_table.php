<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCutiKaryawanDatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cuti_karyawan_dates', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('cuti_karyawan_id')->unsigned()->index('cuti_karyawan_dates_cuti_karyawan_id_foreign');
			$table->date('tanggal_cuti');
			$table->boolean('type')->nullable();
			$table->string('description', 191)->nullable();
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
		Schema::drop('cuti_karyawan_dates');
	}

}
