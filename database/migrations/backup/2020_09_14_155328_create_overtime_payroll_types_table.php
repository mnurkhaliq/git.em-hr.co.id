<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOvertimePayrollTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('overtime_payroll_types', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 100)->nullable();
			$table->text('description', 65535)->nullable();
			$table->timestamps();
        });
        
        \DB::table('overtime_payroll_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Regular',
                'description' => 'Calculated/173*total earnings',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Hourly',
                'description' => 'Approved claim overtime total hours*fix rate',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Daily',
                'description' => 'Approved claim OT day*fix rate',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('overtime_payroll_types');
	}

}
