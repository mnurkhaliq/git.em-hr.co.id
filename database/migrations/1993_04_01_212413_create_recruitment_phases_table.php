<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentPhasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recruitment_phases', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('recruitment_type_id')->unsigned()->index('recruitment_phases_recruitment_type_id_foreign');
			$table->string('name', 191);
			$table->boolean('order');
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
		Schema::drop('recruitment_phases');
	}

}
