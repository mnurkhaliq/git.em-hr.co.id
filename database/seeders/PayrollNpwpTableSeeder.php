<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PayrollNpwpTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payroll_npwp')->delete();
        
        \DB::table('payroll_npwp')->insert(array (
            0 => 
            array (
                'id' => '1',
                'id_payroll_npwp' => NULL,
                'label' => 'Nama Perusahaan',
                'value' => '',
                'project_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => '2',
                'id_payroll_npwp' => NULL,
                'label' => 'Nomor NPWP',
                'value' => '',
                'project_id' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}