<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryApprovalLeaveTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('history_approval_leave', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('cuti_karyawan_id')->nullable();
			$table->integer('structure_organization_custom_id')->nullable();
			$table->integer('setting_approval_level_id')->nullable();
			$table->integer('approval_id')->nullable();
			$table->boolean('is_approved')->nullable();
			$table->date('date_approved')->nullable();
			$table->text('note', 65535)->nullable();
			$table->boolean('is_withdrawal')->default(0);
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
		Schema::drop('history_approval_leave');
	}

}
