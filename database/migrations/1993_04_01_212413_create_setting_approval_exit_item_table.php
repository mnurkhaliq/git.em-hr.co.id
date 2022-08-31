<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingApprovalExitItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('setting_approval_exit_item', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('setting_approval_id');
			$table->integer('setting_approval_level_id');
			$table->integer('structure_organization_custom_id')->unsigned()->index('structure_exit_ref');
			$table->text('description', 65535)->nullable();
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
		Schema::drop('setting_approval_exit_item');
	}

}
