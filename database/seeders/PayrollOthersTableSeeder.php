<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PayrollOthersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payroll_others')->delete();
        
        \DB::table('payroll_others')->insert(array (
            0 => 
            array (
                'id' => '1',
                'label' => 'Biaya Jabatan Maks per Thn',
                'value' => '6000000',
                'created_at' => '2018-05-30 11:28:35',
                'updated_at' => '2018-05-30 11:28:35',
            ),
            1 => 
            array (
                'id' => '2',
                'label' => 'Upah Minimum Propinsi',
                'value' => '3940973',
                'created_at' => '2018-05-30 11:31:06',
                'updated_at' => '2019-03-01 00:56:47',
            ),
            2 => 
            array (
                'id' => '3',
                'label' => 'BPJS Jaminan Pensiun',
                'value' => '8512400',
                'created_at' => '2018-11-26 10:03:12',
                'updated_at' => '2019-07-19 04:26:41',
            ),
            3 => 
            array (
                'id' => '4',
                'label' => 'BPJS Kesehatan',
                'value' => '8000000',
                'created_at' => '2018-11-26 10:05:05',
                'updated_at' => '2019-06-26 19:02:22',
            ),
        ));
        
        
    }
}