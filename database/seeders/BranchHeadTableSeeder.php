<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BranchHeadTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('branch_head')->delete();
        
        \DB::table('branch_head')->insert(array (
            0 => 
            array (
                'id' => '1',
                'name' => 'Head Collection',
                'created_at' => '2018-05-02 07:57:13',
                'updated_at' => '2018-05-02 07:57:13',
            ),
            1 => 
            array (
                'id' => '2',
                'name' => 'Head Credit',
                'created_at' => '2018-05-02 07:59:01',
                'updated_at' => '2018-05-02 07:59:01',
            ),
            2 => 
            array (
                'id' => '3',
                'name' => 'Outlet',
                'created_at' => '2018-05-02 07:59:16',
                'updated_at' => '2018-05-02 07:59:16',
            ),
            3 => 
            array (
                'id' => '4',
                'name' => 'Head Operation',
                'created_at' => '2018-05-02 07:59:29',
                'updated_at' => '2018-05-02 07:59:29',
            ),
        ));
        
        
    }
}