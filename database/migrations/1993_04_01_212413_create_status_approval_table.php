<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusApprovalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('status_approval', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('approval_user_id')->nullable();
			$table->string('jenis_form')->nullable();
			$table->integer('foreign_id')->nullable();
			$table->integer('status')->nullable();
			$table->text('noted', 65535)->nullable();
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
		Schema::drop('status_approval');
	}

}
