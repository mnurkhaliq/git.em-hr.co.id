<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlafondDinasLuarNegeriTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('plafond_dinas_luar_negeri', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('organisasi_position_id')->nullable();
			$table->string('hotel', 100)->nullable();
			$table->integer('tunjangan_makanan')->nullable();
			$table->integer('tunjangan_harian')->nullable();
			$table->string('pesawat', 150)->nullable();
			$table->text('keterangan', 65535)->nullable();
			$table->timestamps();
			$table->string('organisasi_position_text', 50);
			$table->string('plafond_type')->nullable();
			$table->integer('user_created')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('plafond_dinas_luar_negeri');
	}

}
