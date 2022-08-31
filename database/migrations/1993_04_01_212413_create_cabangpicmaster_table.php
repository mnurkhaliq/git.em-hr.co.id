<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCabangpicmasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cabangpicmaster', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('cabang_id')->unsigned()->nullable()->index('cabangpicmaster_cabang_id_foreign');
			$table->string('picname', 191)->nullable();
			$table->boolean('isactive');
			$table->timestamps();
			$table->integer('user_created')->unsigned()->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cabangpicmaster');
	}

}
