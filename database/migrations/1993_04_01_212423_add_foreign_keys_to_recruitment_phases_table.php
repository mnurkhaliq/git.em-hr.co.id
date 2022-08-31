<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRecruitmentPhasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recruitment_phases', function(Blueprint $table)
		{
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
		Schema::table('recruitment_phases', function(Blueprint $table)
		{
			$table->dropForeign('recruitment_phases_recruitment_type_id_foreign');
		});
	}

}
