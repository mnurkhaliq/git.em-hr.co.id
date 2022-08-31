<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('asset', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('asset_number', 10)->nullable();
			$table->string('asset_name')->nullable();
			$table->integer('asset_type_id')->nullable();
			$table->string('asset_sn', 100)->nullable();
			$table->date('purchase_date')->nullable();
			$table->string('asset_condition', 100)->nullable();
			$table->string('assign_to', 25)->nullable();
			$table->integer('user_id')->nullable();
			$table->timestamps();
			$table->dateTime('handover_date')->nullable();
			$table->string('remark')->nullable();
			$table->date('rental_date')->nullable();
			$table->string('tipe_mobil', 200)->nullable();
			$table->string('tahun', 5)->nullable();
			$table->string('no_polisi', 25)->nullable();
			$table->string('status_mobil', 25)->nullable();
			$table->smallInteger('status')->nullable();
			$table->string('encrypted_key');
			$table->text('admin_note', 65535)->nullable();
			$table->text('user_note', 65535)->nullable();
			$table->integer('user_note_by')->unsigned()->nullable()->index('asset_user_note_by_foreign');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('asset');
	}

}
