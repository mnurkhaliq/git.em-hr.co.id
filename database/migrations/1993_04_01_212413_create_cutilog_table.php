<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCutilogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cutilog', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('usercuti_id')->unsigned()->index('cutilog_usercuti_id_foreign');
			$table->string('update_status', 191)->nullable();
			$table->timestamp('date_update')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cutilog');
	}

}
