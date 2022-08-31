<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSettingApprovalOvertimeItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('setting_approval_overtime_item', function(Blueprint $table)
		{
			$table->foreign('structure_organization_custom_id', 'structure_overtime_ref')->references('id')->on('structure_organization_custom')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('setting_approval_overtime_item', function(Blueprint $table)
		{
			$table->dropForeign('structure_overtime_ref');
		});
	}

}
