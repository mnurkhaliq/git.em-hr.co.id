<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToJobseekerEducationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jobseeker_educations', function(Blueprint $table)
		{
			$table->foreign('education_id')->references('id')->on('educations')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('jobseeker_id')->references('id')->on('jobseekers')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('jobseeker_educations', function(Blueprint $table)
		{
			$table->dropForeign('jobseeker_educations_education_id_foreign');
			$table->dropForeign('jobseeker_educations_jobseeker_id_foreign');
		});
	}

}
