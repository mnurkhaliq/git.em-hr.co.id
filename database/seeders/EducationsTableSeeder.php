<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EducationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('educations')->delete();
        
        \DB::table('educations')->insert(array (
            0 => 
            array (
                'id' => '1',
                'name' => 'SMA/SMK',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            1 => 
            array (
                'id' => '2',
                'name' => 'S1',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            2 => 
            array (
                'id' => '3',
                'name' => 'S2',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            3 => 
            array (
                'id' => '4',
                'name' => 'S3',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
        ));
        
        
    }
}