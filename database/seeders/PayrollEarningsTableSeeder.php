<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PayrollEarningsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payroll_earnings')->delete();
        
        \DB::table('payroll_earnings')->insert(array (
            0 => 
            array (
                'id' => '1',
                'title' => 'Call Allowance',
                'taxable' => '1',
                'created_at' => '2019-05-10 05:08:44',
                'updated_at' => '2019-05-10 05:08:44',
                'user_created' => NULL,
                'project_id' => NULL,
            ),
            1 => 
            array (
                'id' => '2',
                'title' => 'Transportation Allowance',
                'taxable' => '1',
                'created_at' => '2019-05-10 05:09:07',
                'updated_at' => '2019-05-10 05:09:07',
                'user_created' => NULL,
                'project_id' => NULL,
            ),
            2 => 
            array (
                'id' => '3',
                'title' => 'Meal Allowance',
                'taxable' => '1',
                'created_at' => '2019-05-10 05:09:22',
                'updated_at' => '2019-05-10 05:09:22',
                'user_created' => NULL,
                'project_id' => NULL,
            ),
            3 => 
            array (
                'id' => '4',
                'title' => 'Overtime',
                'taxable' => '1',
                'created_at' => '2019-05-10 05:09:52',
                'updated_at' => '2019-05-10 05:09:52',
                'user_created' => NULL,
                'project_id' => NULL,
            ),
            4 => 
            array (
                'id' => '5',
                'title' => 'Housing Allowance',
                'taxable' => '1',
                'created_at' => '2019-05-10 05:10:11',
                'updated_at' => '2019-05-10 05:10:11',
                'user_created' => NULL,
                'project_id' => NULL,
            ),
            5 => 
            array (
                'id' => '6',
            'title' => 'BPJS Jaminan Kecelakaan Kerja (JKK) (Company)',
                'taxable' => '1',
                'created_at' => '2019-05-10 05:10:49',
                'updated_at' => '2019-05-10 05:10:49',
                'user_created' => NULL,
                'project_id' => NULL,
            ),
            6 => 
            array (
                'id' => '7',
            'title' => 'BPJS Jaminan Kematian (JKM) (Company)',
                'taxable' => '1',
                'created_at' => '2019-05-10 05:11:26',
                'updated_at' => '2019-05-10 05:11:26',
                'user_created' => NULL,
                'project_id' => NULL,
            ),
            7 => 
            array (
                'id' => '8',
            'title' => 'BPJS Jaminan Hari Tua (JHT) (Company)',
                'taxable' => '1',
                'created_at' => '2019-05-10 05:12:03',
                'updated_at' => '2019-05-10 05:12:03',
                'user_created' => NULL,
                'project_id' => NULL,
            ),
            8 => 
            array (
                'id' => '10',
            'title' => 'BPJS Pensiun (Company)',
                'taxable' => '1',
                'created_at' => '2019-05-10 05:14:12',
                'updated_at' => '2019-05-10 05:14:12',
                'user_created' => NULL,
                'project_id' => NULL,
            ),
            9 => 
            array (
                'id' => '11',
            'title' => 'BPJS Kesehatan (Company)',
                'taxable' => '1',
                'created_at' => '2019-05-10 05:14:39',
                'updated_at' => '2019-05-10 05:14:39',
                'user_created' => NULL,
                'project_id' => NULL,
            ),
            10 => 
            array (
                'id' => '12',
                'title' => 'Meal allowance',
                'taxable' => '1',
                'created_at' => '2019-07-19 20:34:08',
                'updated_at' => '2019-07-19 20:34:08',
                'user_created' => '15747',
                'project_id' => NULL,
            ),
            11 => 
            array (
                'id' => '13',
                'title' => 'Transport allowance',
                'taxable' => '1',
                'created_at' => '2019-07-19 20:34:34',
                'updated_at' => '2019-07-19 20:34:34',
                'user_created' => '15747',
                'project_id' => NULL,
            ),
            12 => 
            array (
                'id' => '14',
                'title' => 'Transportation Allowance',
                'taxable' => '1',
                'created_at' => '2019-07-19 04:27:54',
                'updated_at' => '2019-07-19 04:27:54',
                'user_created' => '15766',
                'project_id' => NULL,
            ),
            13 => 
            array (
                'id' => '15',
                'title' => 'Meal Allowance',
                'taxable' => '1',
                'created_at' => '2019-07-19 04:28:04',
                'updated_at' => '2019-07-19 04:28:04',
                'user_created' => '15766',
                'project_id' => NULL,
            ),
            14 => 
            array (
                'id' => '24',
                'title' => 'Housing Allowance',
                'taxable' => '1',
                'created_at' => '2019-08-28 17:12:19',
                'updated_at' => '2019-08-28 17:12:19',
                'user_created' => '15766',
                'project_id' => NULL,
            ),
        ));
        
        
    }
}