<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetTrackingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('asset_tracking', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('asset_number', 10)->nullable();
			$table->string('asset_name')->nullable();
			$table->integer('asset_type_id')->nullable();
			$table->string('asset_sn', 100)->nullable();
			$table->date('purchase_date')->nullable();
			$table->string('asset_condition', 100)->nullable();
			$table->string('assign_to', 25)->nullable();
			$table->integer('user_id')->nullable();
			$table->dateTime('handover_date')->nullable();
			$table->timestamps();
			$table->integer('asset_id')->nullable();
			$table->string('remark')->nullable();
			$table->date('rental_date')->nullable();
			$table->string('tipe_mobil', 200)->nullable();
			$table->string('tahun', 5)->nullable();
			$table->string('no_polisi', 25)->nullable();
			$table->string('status_mobil', 25)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('asset_tracking');
	}

}
