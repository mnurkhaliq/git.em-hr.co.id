<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MasterCutiTypeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('master_cuti_type')->delete();
        
        \DB::table('master_cuti_type')->insert(array (
            0 => 
            array (
                'id' => '1',
                'master_cuti_name' => 'Annually',
            ),
            1 => 
            array (
                'id' => '2',
                'master_cuti_name' => 'Anniversary',
            ),
            2 => 
            array (
                'id' => '3',
                'master_cuti_name' => 'Anniversary Annually',
            ),
            3 => 
            array (
                'id' => '4',
                'master_cuti_name' => 'Monthly',
            ),
            4 => 
            array (
                'id' => '5',
                'master_cuti_name' => 'Custom',
            ),
        ));
        
        
    }
}