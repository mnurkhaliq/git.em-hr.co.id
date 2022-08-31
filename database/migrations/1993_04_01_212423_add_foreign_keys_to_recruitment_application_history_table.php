<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRecruitmentApplicationHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recruitment_application_history', function(Blueprint $table)
		{
			$table->foreign('recruitment_application_id', 'rec_app_ref')->references('id')->on('recruitment_applications')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('application_status')->references('id')->on('recruitment_application_status')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('recruitment_phase_id')->references('id')->on('recruitment_phases')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('recruitment_application_history', function(Blueprint $table)
		{
			$table->dropForeign('rec_app_ref');
			$table->dropForeign('recruitment_application_history_application_status_foreign');
			$table->dropForeign('recruitment_application_history_recruitment_phase_id_foreign');
		});
	}

}
