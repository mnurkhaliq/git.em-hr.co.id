<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TrainingTransportationTypeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('training_transportation_type')->delete();
        
        \DB::table('training_transportation_type')->insert(array (
            0 => 
            array (
                'id' => '1',
                'name' => 'Hotel',
                'is_attachment' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => '2',
                'name' => 'Ticket (Train/Airlines/Ship,etc)',
                'is_attachment' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => '3',
                'name' => 'Taxi',
                'is_attachment' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => '4',
                'name' => 'Gasoline',
                'is_attachment' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => '5',
                'name' => 'Tol',
                'is_attachment' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => '6',
                'name' => 'Parking',
                'is_attachment' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => '7',
                'name' => 'Hotel In Lieu',
                'is_attachment' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}