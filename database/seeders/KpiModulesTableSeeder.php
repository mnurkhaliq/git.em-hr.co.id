<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KpiModulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('kpi_modules')->delete();
        
        \DB::table('kpi_modules')->insert(array (
            0 => 
            array (
                'id' => '1',
                'name' => 'Organization KPI',
                'role' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => '2',
                'name' => 'Manager KPI',
                'role' => '',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}