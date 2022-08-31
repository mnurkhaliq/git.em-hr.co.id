<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingApprovalLevelTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('setting_approval_level')->delete();
        
        \DB::table('setting_approval_level')->insert(array (
            0 => 
            array (
                'id' => '1',
                'name' => 'Approval 1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => '2',
                'name' => 'Approval 2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => '3',
                'name' => 'Approval 3',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => '4',
                'name' => 'Approval 4',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => '5',
                'name' => 'Approval 5',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => '6',
                'name' => 'Approval 6',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => '7',
                'name' => 'Approval 7',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => '8',
                'name' => 'Approval 8',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => '9',
                'name' => 'Approval 9',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => '10',
                'name' => 'Approval 10',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}