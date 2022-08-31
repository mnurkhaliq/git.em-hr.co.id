<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PayrollPtkpTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payroll_ptkp')->delete();
        
        \DB::table('payroll_ptkp')->insert(array (
            0 => 
            array (
                'id' => '1',
                'bujangan_wanita' => '54000000',
                'menikah' => '58500000',
                'menikah_anak_1' => '63000000',
                'menikah_anak_2' => '67500000',
                'menikah_anak_3' => '72000000',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}