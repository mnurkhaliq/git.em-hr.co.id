<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlafondDinasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('plafond_dinas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('organisasi_position_id')->nullable();
			$table->integer('hotel')->nullable();
			$table->integer('tunjangan_makanan')->nullable();
			$table->integer('tunjangan_harian')->nullable();
			$table->string('pesawat')->nullable();
			$table->text('keterangan', 65535)->nullable();
			$table->timestamps();
			$table->string('organisasi_position_text')->nullable();
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
		Schema::drop('plafond_dinas');
	}

}
