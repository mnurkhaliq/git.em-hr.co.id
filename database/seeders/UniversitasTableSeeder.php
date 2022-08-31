<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UniversitasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('universitas')->delete();
        
        \DB::table('universitas')->insert(array (
            0 => 
            array (
                'id' => '1',
                'name' => 'Universitas Gadjah Mada',
                'description' => NULL,
                'created_at' => '2018-03-30 07:39:54',
                'updated_at' => '2018-03-30 07:39:54',
            ),
            1 => 
            array (
                'id' => '2',
                'name' => 'Institut Tekhnologi Bandung',
                'description' => NULL,
                'created_at' => '2018-03-30 07:40:38',
                'updated_at' => '2018-03-30 07:40:38',
            ),
            2 => 
            array (
                'id' => '3',
                'name' => 'Institut Pertanian Bogor',
                'description' => NULL,
                'created_at' => '2018-03-30 07:40:55',
                'updated_at' => '2018-03-30 07:40:55',
            ),
            3 => 
            array (
                'id' => '4',
                'name' => 'Universitas Indonesia',
                'description' => NULL,
                'created_at' => '2018-03-30 07:41:11',
                'updated_at' => '2018-03-30 07:41:11',
            ),
            4 => 
            array (
                'id' => '5',
                'name' => 'Institut Teknologi Sepuluh Nopember',
                'description' => NULL,
                'created_at' => '2018-03-30 07:41:28',
                'updated_at' => '2018-03-30 07:41:28',
            ),
            5 => 
            array (
                'id' => '6',
                'name' => 'Universitas Diponegoro',
                'description' => NULL,
                'created_at' => '2018-03-30 07:41:41',
                'updated_at' => '2018-03-30 07:41:41',
            ),
            6 => 
            array (
                'id' => '7',
                'name' => 'Universitas Airlangga',
                'description' => NULL,
                'created_at' => '2018-03-30 07:41:59',
                'updated_at' => '2018-03-30 07:41:59',
            ),
            7 => 
            array (
                'id' => '8',
                'name' => 'Universitas Brawijaya',
                'description' => NULL,
                'created_at' => '2018-03-30 07:42:15',
                'updated_at' => '2019-05-30 00:05:16',
            ),
            8 => 
            array (
                'id' => '10',
                'name' => 'Universitas Andalas',
                'description' => NULL,
                'created_at' => '2019-07-25 17:52:42',
                'updated_at' => '2019-07-25 17:52:42',
            ),
            9 => 
            array (
                'id' => '11',
                'name' => 'Politeknik Negeri Padang',
                'description' => NULL,
                'created_at' => '2019-07-25 17:53:12',
                'updated_at' => '2019-07-25 17:53:12',
            ),
            10 => 
            array (
                'id' => '12',
                'name' => 'Politeknik Negeri Jakarta',
                'description' => NULL,
                'created_at' => '2019-07-25 17:53:28',
                'updated_at' => '2019-07-25 17:53:28',
            ),
            11 => 
            array (
                'id' => '13',
                'name' => 'Universitas Gunadarma',
                'description' => NULL,
                'created_at' => '2019-07-25 17:53:50',
                'updated_at' => '2019-07-25 17:53:50',
            ),
        ));
        
        
    }
}