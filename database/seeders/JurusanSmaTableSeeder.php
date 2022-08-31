<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class JurusanSmaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('jurusan_sma')->delete();
        
        \DB::table('jurusan_sma')->insert(array (
            0 => 
            array (
                'id' => '1',
                'name' => 'IPA',
                'description' => 'Ilmu Pengetahuan Alam',
                'created_at' => '2018-03-30 20:33:00',
                'updated_at' => '2018-03-30 20:33:00',
            ),
            1 => 
            array (
                'id' => '2',
                'name' => 'IPS',
                'description' => 'Ilmu Pengetahuan Sosial',
                'created_at' => '2018-03-30 20:33:15',
                'updated_at' => '2018-03-30 20:33:15',
            ),
        ));
        
        
    }
}