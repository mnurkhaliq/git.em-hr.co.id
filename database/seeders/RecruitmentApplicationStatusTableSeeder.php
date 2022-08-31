<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RecruitmentApplicationStatusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('recruitment_application_status')->delete();
        
        \DB::table('recruitment_application_status')->insert(array (
            0 => 
            array (
                'id' => '0',
                'status' => 'Waiting',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            1 => 
            array (
                'id' => '1',
                'status' => 'Approved',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            2 => 
            array (
                'id' => '2',
                'status' => 'Shortlisted',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            3 => 
            array (
                'id' => '3',
                'status' => 'Rejected',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            4 => 
            array (
                'id' => '4',
                'status' => 'Archived',
                'created_at' => '2020-11-10 10:45:14',
                'updated_at' => '0000-00-00 00:00:00',
            ),
        ));
        
        
    }
}