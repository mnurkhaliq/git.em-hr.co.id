<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRecruitmentRequestDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recruitment_request_detail', function(Blueprint $table)
		{
			$table->foreign('recruitment_request_id')->references('id')->on('recruitment_request')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('recruitment_type_id')->references('id')->on('recruitment_type')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('recruitment_request_detail', function(Blueprint $table)
		{
			$table->dropForeign('recruitment_request_detail_recruitment_request_id_foreign');
			$table->dropForeign('recruitment_request_detail_recruitment_type_id_foreign');
		});
	}

}
