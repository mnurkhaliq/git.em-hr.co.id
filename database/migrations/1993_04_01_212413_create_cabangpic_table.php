<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCabangpicTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cabangpic', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('cabangpicmaster_id')->unsigned()->nullable()->index('cabangpic_cabangpicmaster_id_foreign');
			$table->integer('user_id')->nullable()->index('cabangpic_user_id_foreign');
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
		Schema::drop('cabangpic');
	}

}
