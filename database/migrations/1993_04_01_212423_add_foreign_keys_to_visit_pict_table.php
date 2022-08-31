<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToVisitPictTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('visit_pict', function(Blueprint $table)
		{
			$table->foreign('visit_list_id')->references('id')->on('visit_list')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('visit_pict', function(Blueprint $table)
		{
			$table->dropForeign('visit_pict_visit_list_id_foreign');
		});
	}

}
