<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCutiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cuti', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('jenis_cuti')->nullable();
			$table->integer('kuota')->nullable();
			$table->integer('master_cuti_type_id')->unsigned()->nullable()->index('cuti_master_cuti_type_id_foreign');
			$table->integer('carryforwardleave')->unsigned()->nullable();
			$table->boolean('iscarryforward')->nullable();
			$table->boolean('is_attachment')->nullable();
			$table->string('cutoffmonth', 191)->nullable();
			$table->timestamps();
			$table->string('description')->nullable();
			$table->integer('user_created')->nullable();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cuti');
	}

}
