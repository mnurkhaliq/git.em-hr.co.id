<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MedicalTypeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('medical_type')->delete();
        
        \DB::table('medical_type')->insert(array (
            0 => 
            array (
                'id' => '1',
                'name' => 'Outpatient',
                'created_at' => '2019-05-10 06:05:57',
                'updated_at' => '2019-06-04 06:46:52',
                'user_created' => NULL,
                'project_id' => NULL,
            ),
            1 => 
            array (
                'id' => '2',
                'name' => 'Inpatient',
                'created_at' => '2019-05-10 06:06:47',
                'updated_at' => '2019-06-04 06:47:01',
                'user_created' => NULL,
                'project_id' => NULL,
            ),
            2 => 
            array (
                'id' => '3',
                'name' => 'glasses',
                'created_at' => '2019-07-19 06:06:29',
                'updated_at' => '2019-07-19 06:06:29',
                'user_created' => '15766',
                'project_id' => NULL,
            ),
            3 => 
            array (
                'id' => '4',
                'name' => 'MM',
                'created_at' => '2019-07-26 18:14:59',
                'updated_at' => '2019-07-26 18:14:59',
                'user_created' => '15766',
                'project_id' => NULL,
            ),
        ));
        
        
    }
}