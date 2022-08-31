<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelurahanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kelurahan', function(Blueprint $table)
		{
			$table->char('id_kel', 10)->primary();
			$table->char('id_kec', 6)->nullable();
			$table->text('nama')->nullable();
			$table->integer('id_jenis');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('kelurahan');
	}

}
