<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MasterVisitTypeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('master_visit_type')->delete();
        
        \DB::table('master_visit_type')->insert(array (
            0 => 
            array (
                'id' => '1',
                'master_visit_type_name' => 'Lock',
                'description' => 'lock = features used for visits that have been determined based on the point of location,but can be used for free visits by using feature "out of branch / point"',
                'created_at' => '2020-11-10 10:45:15',
                'updated_at' => '0000-00-00 00:00:00',
            ),
            1 => 
            array (
                'id' => '2',
                'master_visit_type_name' => 'Unlock',
                'description' => 'features used for free visits or the point is not determined',
                'created_at' => '2020-11-10 10:45:15',
                'updated_at' => '0000-00-00 00:00:00',
            ),
        ));
        
        
    }
}