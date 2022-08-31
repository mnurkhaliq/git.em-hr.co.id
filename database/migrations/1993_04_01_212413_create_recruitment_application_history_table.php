<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentApplicationHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recruitment_application_history', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('recruitment_application_id')->unsigned()->index('rec_app_ref');
			$table->integer('recruitment_phase_id')->unsigned()->index('recruitment_application_history_recruitment_phase_id_foreign');
			$table->integer('application_status')->unsigned()->index('recruitment_application_history_application_status_foreign');
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
		Schema::drop('recruitment_application_history');
	}

}
