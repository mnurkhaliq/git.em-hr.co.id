<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCutiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_cuti', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('jenis_cuti')->nullable();
			$table->integer('kuota')->nullable();
			$table->timestamps();
			$table->integer('user_id')->nullable();
			$table->string('status', 100)->nullable();
			$table->date('join_date')->nullable();
			$table->string('length_of_service', 50)->nullable();
			$table->integer('cuti_id')->nullable();
			$table->integer('cuti_terpakai')->nullable();
			$table->integer('sisa_cuti')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_cuti');
	}

}
