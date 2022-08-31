<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentApplicationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recruitment_applications', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('recruitment_request_id')->unsigned()->index('recruitment_applications_recruitment_request_id_foreign');
			$table->integer('current_phase_id')->unsigned()->index('recruitment_applications_current_phase_id_foreign');
			$table->integer('application_status')->unsigned()->index('recruitment_applications_application_status_foreign');
			$table->text('cover_letter', 65535)->nullable();
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
		Schema::drop('recruitment_applications');
	}

}
