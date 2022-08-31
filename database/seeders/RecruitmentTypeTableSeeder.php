<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RecruitmentTypeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('recruitment_type')->delete();
        
        \DB::table('recruitment_type')->insert(array (
            0 => 
            array (
                'id' => '1',
                'name' => 'Internal',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            1 => 
            array (
                'id' => '2',
                'name' => 'External',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
        ));
        
        
    }
}