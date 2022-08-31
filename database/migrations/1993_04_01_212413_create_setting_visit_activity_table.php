<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingVisitActivityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('setting_visit_activity', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('master_category_visit_id')->unsigned()->index('setting_visit_activity_master_category_visit_id_foreign');
			$table->string('activityname', 191)->nullable();
			$table->boolean('isactive');
			$table->timestamps();
			$table->integer('user_created')->unsigned()->nullable();
			$table->float('point', 10, 0)->nullable()->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('setting_visit_activity');
	}

}
