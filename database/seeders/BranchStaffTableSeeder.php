<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BranchStaffTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('branch_staff')->delete();
        
        \DB::table('branch_staff')->insert(array (
            0 => 
            array (
                'id' => '1',
                'branch_head_id' => '1',
                'name' => 'Field Collection',
                'created_at' => '2018-05-02 08:38:02',
                'updated_at' => '2018-05-02 08:42:05',
            ),
            1 => 
            array (
                'id' => '2',
                'branch_head_id' => '2',
                'name' => 'CMO',
                'created_at' => '2018-05-02 08:43:46',
                'updated_at' => '2018-05-02 08:43:46',
            ),
            2 => 
            array (
                'id' => '3',
                'branch_head_id' => '4',
                'name' => 'Admin Collection',
                'created_at' => '2018-05-02 08:44:07',
                'updated_at' => '2018-05-02 08:44:07',
            ),
            3 => 
            array (
                'id' => '4',
                'branch_head_id' => '4',
                'name' => 'Customer Service',
                'created_at' => '2018-05-02 08:44:19',
                'updated_at' => '2018-05-02 08:44:19',
            ),
            4 => 
            array (
                'id' => '5',
                'branch_head_id' => '4',
                'name' => 'Staff Umum & Messenger',
                'created_at' => '2018-05-02 08:44:37',
                'updated_at' => '2018-05-02 08:44:37',
            ),
        ));
        
        
    }
}