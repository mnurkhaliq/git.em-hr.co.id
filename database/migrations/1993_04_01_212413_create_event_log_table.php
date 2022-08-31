<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_log', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('database', 191)->nullable();
			$table->string('type', 191)->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('transaction_id')->nullable();
			$table->string('description', 191)->nullable();
			$table->timestamp('date')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('event_log');
	}

}
