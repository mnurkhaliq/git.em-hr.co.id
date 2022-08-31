<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('visit_list', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->index('visit_list_user_id_foreign');
			$table->integer('master_visit_type_id')->unsigned()->nullable()->index('visit_list_master_visit_type_id_foreign');
			$table->integer('master_category_visit_id')->unsigned()->nullable()->index('visit_list_master_category_visit_id_foreign');
			$table->integer('setting_visit_activity_id')->unsigned()->nullable()->index('visit_list_setting_visit_activity_id_foreign');
			$table->float('point', 10, 0)->nullable()->default(1);
			$table->integer('cabang_id')->unsigned()->nullable()->index('visit_list_cabang_id_foreign');
			$table->timestamp('visit_time')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('timezone', 191)->nullable();
			$table->string('timetable', 191)->nullable();
			$table->boolean('isoutbranch')->nullable();
			$table->string('justification', 191)->nullable();
			$table->float('longitude', 10, 0)->nullable();
			$table->float('latitude', 10, 0)->nullable();
			$table->string('locationname', 191)->nullable();
			$table->string('placename', 191)->nullable();
			$table->boolean('isotheractivityname')->nullable();
			$table->string('activityname', 191)->nullable();
			$table->string('description', 191)->nullable();
			$table->float('radius_visit', 10, 0)->nullable();
			$table->float('branchlongitude', 10, 0)->nullable();
			$table->float('branchlatitude', 10, 0)->nullable();
			$table->boolean('isotherpic')->nullable();
			$table->string('picname', 191)->nullable();
			$table->string('signature', 191)->nullable();
			$table->dateTime('created_at')->default('0000-00-00 00:00:00');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('visit_list');
	}

}
