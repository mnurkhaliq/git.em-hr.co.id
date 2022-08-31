<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PayrollDeductionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payroll_deductions')->delete();
        
        \DB::table('payroll_deductions')->insert(array (
            0 => 
            array (
                'id' => '1',
            'title' => 'BPJS Jaminan Hari Tua (JHT) (Employee)',
                'taxable' => '1',
                'created_at' => '2019-05-10 05:15:56',
                'updated_at' => '2019-05-10 05:15:56',
                'user_created' => NULL,
                'project_id' => NULL,
            ),
            1 => 
            array (
                'id' => '2',
            'title' => 'BPJS Jaminan Pensiun (Employee)',
                'taxable' => '1',
                'created_at' => '2019-05-10 05:16:39',
                'updated_at' => '2019-05-10 05:16:39',
                'user_created' => NULL,
                'project_id' => NULL,
            ),
            2 => 
            array (
                'id' => '3',
            'title' => 'BPJS Kesehatan (Employee)',
                'taxable' => '1',
                'created_at' => '2019-05-10 05:17:07',
                'updated_at' => '2019-05-10 05:17:07',
                'user_created' => NULL,
                'project_id' => NULL,
            ),
            3 => 
            array (
                'id' => '10',
                'title' => 'Cicilan',
                'taxable' => '1',
                'created_at' => '2019-08-23 17:15:52',
                'updated_at' => '2019-08-23 17:15:52',
                'user_created' => '15766',
                'project_id' => NULL,
            ),
            4 => 
            array (
                'id' => '11',
                'title' => 'Pinjaman',
                'taxable' => '1',
                'created_at' => '2019-08-23 17:17:23',
                'updated_at' => '2019-08-23 17:17:23',
                'user_created' => '15766',
                'project_id' => NULL,
            ),
            5 => 
            array (
                'id' => '13',
                'title' => 'Tabungan',
                'taxable' => '1',
                'created_at' => '2019-08-26 23:32:58',
                'updated_at' => '2019-08-26 23:32:58',
                'user_created' => '15766',
                'project_id' => NULL,
            ),
        ));
        
        
    }
}