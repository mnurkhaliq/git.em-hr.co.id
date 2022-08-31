<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRecruitmentInterviewersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recruitment_interviewers', function(Blueprint $table)
		{
			$table->foreign('recruitment_request_id')->references('id')->on('recruitment_request')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('recruitment_interviewers', function(Blueprint $table)
		{
			$table->dropForeign('recruitment_interviewers_recruitment_request_id_foreign');
		});
	}

}
