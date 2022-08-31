<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingApprovalLeaveItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('setting_approval_leave_item', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('setting_approval_id')->nullable();
			$table->integer('setting_approval_level_id')->nullable();
			$table->integer('structure_organization_custom_id')->unsigned()->nullable()->index('structure_leave_ref');
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
		Schema::drop('setting_approval_leave_item');
	}

}
