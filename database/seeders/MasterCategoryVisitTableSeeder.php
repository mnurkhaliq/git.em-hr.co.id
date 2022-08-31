<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MasterCategoryVisitTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('master_category_visit')->delete();
        
        \DB::table('master_category_visit')->insert(array (
            0 => 
            array (
                'id' => '1',
                'master_category_name' => 'Sales / Marketing',
            ),
            1 => 
            array (
                'id' => '2',
                'master_category_name' => 'Medical',
            ),
            2 => 
            array (
                'id' => '3',
                'master_category_name' => 'Telecomunication / Information Technology',
            ),
            3 => 
            array (
                'id' => '4',
                'master_category_name' => 'Engineering',
            ),
            4 => 
            array (
                'id' => '5',
                'master_category_name' => 'Finance / Banking / Insurance',
            ),
            5 => 
            array (
                'id' => '6',
                'master_category_name' => 'Logistic',
            ),
        ));
        
        
    }
}