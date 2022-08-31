<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitPictTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('visit_pict', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('visit_list_id')->unsigned()->nullable()->index('visit_pict_visit_list_id_foreign');
			$table->string('photo', 191)->nullable();
			$table->string('photocaption', 191)->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('visit_pict');
	}

}
