<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RecruitmentPhasesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('recruitment_phases')->delete();
        
        \DB::table('recruitment_phases')->insert(array (
            0 => 
            array (
                'id' => '1',
                'recruitment_type_id' => '1',
                'name' => 'Screening',
                'order' => '1',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            1 => 
            array (
                'id' => '2',
                'recruitment_type_id' => '1',
                'name' => 'Technical Exam',
                'order' => '2',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            2 => 
            array (
                'id' => '3',
                'recruitment_type_id' => '1',
                'name' => 'Interview HR & User',
                'order' => '3',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            3 => 
            array (
                'id' => '4',
                'recruitment_type_id' => '1',
                'name' => 'Transfer/Promotion',
                'order' => '4',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            4 => 
            array (
                'id' => '5',
                'recruitment_type_id' => '2',
                'name' => 'Screening',
                'order' => '1',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            5 => 
            array (
                'id' => '6',
                'recruitment_type_id' => '2',
                'name' => 'Psychotest',
                'order' => '2',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            6 => 
            array (
                'id' => '7',
                'recruitment_type_id' => '2',
                'name' => 'Technical Exam',
                'order' => '3',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            7 => 
            array (
                'id' => '8',
                'recruitment_type_id' => '2',
                'name' => 'Interview HR & User',
                'order' => '4',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            8 => 
            array (
                'id' => '9',
                'recruitment_type_id' => '2',
                'name' => 'Reference Check',
                'order' => '5',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            9 => 
            array (
                'id' => '10',
                'recruitment_type_id' => '2',
                'name' => 'Medical Check up',
                'order' => '6',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            10 => 
            array (
                'id' => '11',
                'recruitment_type_id' => '2',
                'name' => 'Job Offer',
                'order' => '7',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            11 => 
            array (
                'id' => '12',
                'recruitment_type_id' => '2',
                'name' => 'Hiring',
                'order' => '8',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            12 => 
            array (
                'id' => '13',
                'recruitment_type_id' => '2',
                'name' => 'Onboarding',
                'order' => '9',
                'created_at' => '2020-01-06 16:01:36',
                'updated_at' => '0000-00-00 00:00:00',
            ),
        ));
        
        
    }
}