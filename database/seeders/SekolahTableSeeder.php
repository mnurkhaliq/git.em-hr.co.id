<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SekolahTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sekolah')->delete();
        
        \DB::table('sekolah')->insert(array (
            0 => 
            array (
                'id' => '1',
                'name' => 'SMA Negeri 1',
                'alamat' => 'Jl. Budi Utomo No. 7 Sawah Besar',
                'telepon' => NULL,
                'created_at' => '2018-03-31 05:08:05',
                'updated_at' => '2018-03-31 05:08:05',
            ),
            1 => 
            array (
                'id' => '2',
                'name' => 'SMA Negeri 2',
                'alamat' => 'Jl. Gajah Mada 175 Taman Sari',
                'telepon' => NULL,
                'created_at' => '2018-03-31 05:09:29',
                'updated_at' => '2018-03-31 05:09:29',
            ),
            2 => 
            array (
                'id' => '3',
                'name' => 'SMA Negeri 3',
                'alamat' => 'Jl. Setiabudi II Setiabudi',
                'telepon' => NULL,
                'created_at' => '2018-03-31 05:10:18',
                'updated_at' => '2018-03-31 05:10:18',
            ),
        ));
        
        
    }
}