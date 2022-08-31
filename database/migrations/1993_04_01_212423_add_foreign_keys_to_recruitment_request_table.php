<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRecruitmentRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recruitment_request', function(Blueprint $table)
		{
			$table->foreign('job_category_id')->references('id')->on('job_categories')->onUpdate('SET NULL')->onDelete('SET NULL');
			$table->foreign('structure_organization_custom_id', 'structure_rec_req_ref')->references('id')->on('structure_organization_custom')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('recruitment_request', function(Blueprint $table)
		{
			$table->dropForeign('recruitment_request_job_category_id_foreign');
			$table->dropForeign('structure_rec_req_ref');
		});
	}

}
