<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSettingVisitActivityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('setting_visit_activity', function(Blueprint $table)
		{
			$table->foreign('master_category_visit_id')->references('id')->on('master_category_visit')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('setting_visit_activity', function(Blueprint $table)
		{
			$table->dropForeign('setting_visit_activity_master_category_visit_id_foreign');
		});
	}

}
