<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCutiBersamaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cuti_bersama', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('dari_tanggal')->nullable();
			$table->date('sampai_tanggal')->nullable();
			$table->boolean('impacttoleave')->nullable();
			$table->integer('total_cuti')->nullable();
			$table->string('description', 191)->nullable();
			$table->timestamps();
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
		Schema::drop('cuti_bersama');
	}

}
