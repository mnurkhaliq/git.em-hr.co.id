<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFamilyTempTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_family_temp', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_temp_id')->nullable();
			$table->string('nama')->nullable();
			$table->string('hubungan')->nullable();
			$table->string('contact', 100)->nullable();
			$table->string('tempat_lahir')->nullable();
			$table->date('tanggal_lahir')->nullable();
			$table->date('tanggal_meninggal')->nullable();
			$table->string('jenjang_pendidikan')->nullable();
			$table->string('pekerjaan')->nullable();
			$table->timestamps();
			$table->string('gender', 20)->nullable();
			$table->text('note', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_family_temp');
	}

}
