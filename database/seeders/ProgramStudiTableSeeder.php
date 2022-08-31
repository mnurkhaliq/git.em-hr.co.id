<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProgramStudiTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('program_studi')->delete();
        
        \DB::table('program_studi')->insert(array (
            0 => 
            array (
                'id' => '1',
                'name' => 'Ilmu Prolitik',
                'description' => NULL,
                'created_at' => '2018-03-30 19:39:25',
                'updated_at' => '2018-03-30 19:39:25',
            ),
            1 => 
            array (
                'id' => '2',
                'name' => 'Hubungan Internasional',
                'description' => NULL,
                'created_at' => '2018-03-30 20:21:25',
                'updated_at' => '2018-03-30 20:21:25',
            ),
            2 => 
            array (
                'id' => '3',
                'name' => 'Ilmu Administrasi Negara',
                'description' => NULL,
                'created_at' => '2018-03-30 20:21:53',
                'updated_at' => '2018-03-30 20:21:53',
            ),
            3 => 
            array (
                'id' => '4',
                'name' => 'Studi Sosiologi',
                'description' => NULL,
                'created_at' => '2018-03-30 20:22:09',
                'updated_at' => '2018-03-30 20:22:09',
            ),
            4 => 
            array (
                'id' => '5',
                'name' => 'Ilmu Komunikasi',
                'description' => NULL,
                'created_at' => '2018-03-30 20:22:21',
                'updated_at' => '2018-03-30 20:22:21',
            ),
            5 => 
            array (
                'id' => '6',
                'name' => 'Ilmu Hukum',
                'description' => NULL,
                'created_at' => '2018-03-30 20:22:36',
                'updated_at' => '2019-05-30 00:12:37',
            ),
            6 => 
            array (
                'id' => '7',
                'name' => 'Teknik Telekomunikasi',
                'description' => NULL,
                'created_at' => '2019-07-25 17:55:06',
                'updated_at' => '2019-07-25 17:55:06',
            ),
            7 => 
            array (
                'id' => '8',
                'name' => 'Teknik Elektro',
                'description' => NULL,
                'created_at' => '2019-07-25 17:55:21',
                'updated_at' => '2019-07-25 17:55:21',
            ),
            8 => 
            array (
                'id' => '9',
                'name' => 'Teknik Fisika',
                'description' => NULL,
                'created_at' => '2019-07-25 17:55:46',
                'updated_at' => '2019-07-25 17:55:46',
            ),
            9 => 
            array (
                'id' => '10',
                'name' => 'Teknik Mesin',
                'description' => NULL,
                'created_at' => '2019-07-25 17:55:57',
                'updated_at' => '2019-07-25 17:55:57',
            ),
            10 => 
            array (
                'id' => '11',
                'name' => 'Teknik Sipil',
                'description' => NULL,
                'created_at' => '2019-07-25 17:56:09',
                'updated_at' => '2019-07-25 17:56:09',
            ),
        ));
        
        
    }
}