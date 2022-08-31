<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobseekersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jobseekers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191)->default('1');
			$table->string('email', 191)->default('1');
			$table->string('password', 191)->nullable();
			$table->string('confirmation_code', 191)->nullable();
			$table->boolean('status_active')->default(0);
			$table->string('cv', 191)->nullable();
			$table->string('portfolio', 191)->nullable();
			$table->string('address', 191)->nullable();
			$table->string('phone_number', 191)->nullable();
			$table->string('auth_key', 191)->nullable();
			$table->string('photos', 191)->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('jobseekers');
	}

}
