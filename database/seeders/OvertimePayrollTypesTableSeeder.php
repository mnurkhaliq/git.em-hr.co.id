<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OvertimePayrollTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('overtime_payroll_types')->delete();
        
        \DB::table('overtime_payroll_types')->insert(array (
            0 => 
            array (
                'id' => '1',
                'name' => 'Regular',
                'description' => 'Calculated/173*total earnings',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => '2',
                'name' => 'Hourly',
                'description' => 'Approved claim overtime total hours*fix rate',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => '3',
                'name' => 'Daily',
                'description' => 'Approved claim OT day*fix rate',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}