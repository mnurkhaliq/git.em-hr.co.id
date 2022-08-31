<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToVisitListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('visit_list', function(Blueprint $table)
		{
			$table->foreign('cabang_id')->references('id')->on('cabang')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('master_category_visit_id')->references('id')->on('master_category_visit')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('master_visit_type_id')->references('id')->on('master_visit_type')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('setting_visit_activity_id')->references('id')->on('setting_visit_activity')->onUpdate('CASCADE')->onDelete('SET NULL');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('visit_list', function(Blueprint $table)
		{
			$table->dropForeign('visit_list_cabang_id_foreign');
			$table->dropForeign('visit_list_master_category_visit_id_foreign');
			$table->dropForeign('visit_list_master_visit_type_id_foreign');
			$table->dropForeign('visit_list_setting_visit_activity_id_foreign');
			$table->dropForeign('visit_list_user_id_foreign');
		});
	}

}
