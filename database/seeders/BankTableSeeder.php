<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BankTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('bank')->delete();
        
        \DB::table('bank')->insert(array (
            0 => 
            array (
                'id' => '1',
                'name' => 'BCA',
                'description' => 'Bank Central Asia',
                'created_at' => '2018-03-30 04:13:29',
                'updated_at' => '2018-03-30 04:13:29',
            ),
            1 => 
            array (
                'id' => '2',
                'name' => 'BNI',
                'description' => 'Bank Negara Indonesia',
                'created_at' => '2018-03-30 04:13:52',
                'updated_at' => '2018-03-30 04:13:52',
            ),
            2 => 
            array (
                'id' => '3',
                'name' => 'BRI',
                'description' => 'Bank Rakyat Indonesia',
                'created_at' => '2018-03-30 04:14:03',
                'updated_at' => '2018-07-01 08:33:15',
            ),
            3 => 
            array (
                'id' => '4',
                'name' => 'Bank Mandiri',
                'description' => 'Bank Mandiri',
                'created_at' => '2018-03-30 04:14:24',
                'updated_at' => '2019-06-19 22:25:59',
            ),
            4 => 
            array (
                'id' => '5',
                'name' => 'Bank Muamalat',
                'description' => 'Bank Muamalat',
                'created_at' => '2018-03-30 04:15:11',
                'updated_at' => '2019-07-24 21:10:39',
            ),
            5 => 
            array (
                'id' => '6',
                'name' => 'Bank Permata',
                'description' => 'Bank Permata',
                'created_at' => '2018-03-30 04:15:33',
                'updated_at' => '2019-06-19 22:25:38',
            ),
            6 => 
            array (
                'id' => '8',
                'name' => 'Bank Danamon',
                'description' => 'Bank Danamon',
                'created_at' => '2019-07-26 17:29:32',
                'updated_at' => '2019-07-26 17:29:44',
            ),
        ));
        
        
    }
}