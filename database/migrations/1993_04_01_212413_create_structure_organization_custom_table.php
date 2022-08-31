<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStructureOrganizationCustomTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('structure_organization_custom', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('parent_id')->nullable();
			$table->string('name')->nullable();
			$table->text('description', 65535)->nullable();
			$table->timestamps();
			$table->integer('organisasi_division_id')->nullable();
			$table->integer('organisasi_position_id')->nullable();
			$table->integer('user_created')->nullable();
			$table->integer('grade_id')->nullable();
			$table->text('requirement', 65535)->nullable();
			$table->boolean('remote_attendance')->default(0);
			$table->integer('project_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('structure_organization_custom');
	}

}
