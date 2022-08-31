<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExternalApplicationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('external_applications', function(Blueprint $table)
		{
			$table->foreign('jobseeker_id')->references('id')->on('jobseekers')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('recruitment_application_id')->references('id')->on('recruitment_applications')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('external_applications', function(Blueprint $table)
		{
			$table->dropForeign('external_applications_jobseeker_id_foreign');
			$table->dropForeign('external_applications_recruitment_application_id_foreign');
		});
	}

}
