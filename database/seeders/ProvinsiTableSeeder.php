<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProvinsiTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('provinsi')->delete();
        
        \DB::table('provinsi')->insert(array (
            0 => 
            array (
                'id_prov' => '11',
                'nama' => 'Aceh',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:31:48',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            1 => 
            array (
                'id_prov' => '12',
                'nama' => 'Sumatera Utara',
                'created_at' => NULL,
                'updated_at' => '2019-06-13 15:44:52',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            2 => 
            array (
                'id_prov' => '13',
                'nama' => 'Sumatera Barat',
                'created_at' => NULL,
                'updated_at' => '2019-07-16 10:49:59',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            3 => 
            array (
                'id_prov' => '14',
                'nama' => 'Riau',
                'created_at' => NULL,
                'updated_at' => '2019-07-16 10:54:58',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            4 => 
            array (
                'id_prov' => '15',
                'nama' => 'Jambi',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:33:17',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            5 => 
            array (
                'id_prov' => '16',
                'nama' => 'Sumatera Selatan',
                'created_at' => NULL,
                'updated_at' => '2019-07-16 10:49:48',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            6 => 
            array (
                'id_prov' => '17',
                'nama' => 'Bengkulu',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:32:28',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            7 => 
            array (
                'id_prov' => '18',
                'nama' => 'Lampung',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:34:57',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            8 => 
            array (
                'id_prov' => '19',
                'nama' => 'Kepulauan Bangka Belitung',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:34:30',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            9 => 
            array (
                'id_prov' => '21',
                'nama' => 'Kepulauan Riau',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:34:38',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            10 => 
            array (
                'id_prov' => '31',
                'nama' => 'DKI Jakarta',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:32:47',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            11 => 
            array (
                'id_prov' => '32',
                'nama' => 'Jawa Barat',
                'created_at' => NULL,
                'updated_at' => '2019-04-26 15:15:40',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            12 => 
            array (
                'id_prov' => '33',
                'nama' => 'Jawa Tengah',
                'created_at' => NULL,
                'updated_at' => '2019-06-10 16:17:21',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            13 => 
            array (
                'id_prov' => '34',
                'nama' => 'DI Yogyakarta',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:32:41',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            14 => 
            array (
                'id_prov' => '35',
                'nama' => 'Jawa Timur',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:29:17',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            15 => 
            array (
                'id_prov' => '36',
                'nama' => 'Banten',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:32:11',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            16 => 
            array (
                'id_prov' => '51',
                'nama' => 'Bali',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:32:03',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            17 => 
            array (
                'id_prov' => '52',
                'nama' => 'Nusa Tenggara Barat',
                'created_at' => NULL,
                'updated_at' => '2019-07-16 10:53:08',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            18 => 
            array (
                'id_prov' => '53',
                'nama' => 'Nusa Tenggara Timur',
                'created_at' => NULL,
                'updated_at' => '2019-07-16 10:54:15',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            19 => 
            array (
                'id_prov' => '61',
                'nama' => 'Kalimantan Barat',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:33:35',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            20 => 
            array (
                'id_prov' => '62',
                'nama' => 'Kalimantan Tengah',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:33:58',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            21 => 
            array (
                'id_prov' => '63',
                'nama' => 'Kalimantan Selatan',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:33:50',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            22 => 
            array (
                'id_prov' => '64',
                'nama' => 'Kalimantan Timur',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:34:13',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            23 => 
            array (
                'id_prov' => '65',
                'nama' => 'Kalimantan Utara',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:34:21',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            24 => 
            array (
                'id_prov' => '71',
                'nama' => 'Sulawesi Utara',
                'created_at' => NULL,
                'updated_at' => '2019-07-16 10:50:09',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            25 => 
            array (
                'id_prov' => '72',
                'nama' => 'Sulawesi Tengah',
                'created_at' => NULL,
                'updated_at' => '2019-07-16 10:50:34',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            26 => 
            array (
                'id_prov' => '73',
                'nama' => 'Sulawesi Selatan',
                'created_at' => NULL,
                'updated_at' => '2019-07-16 10:50:46',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            27 => 
            array (
                'id_prov' => '74',
                'nama' => 'Sulawesi Tenggara',
                'created_at' => NULL,
                'updated_at' => '2019-07-16 10:50:21',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            28 => 
            array (
                'id_prov' => '75',
                'nama' => 'Gorontalo',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:33:03',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            29 => 
            array (
                'id_prov' => '76',
                'nama' => 'Sulawesi Barat',
                'created_at' => NULL,
                'updated_at' => '2019-07-16 10:52:29',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            30 => 
            array (
                'id_prov' => '81',
                'nama' => 'Maluku',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:35:07',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            31 => 
            array (
                'id_prov' => '82',
                'nama' => 'Maluku Utara',
                'created_at' => NULL,
                'updated_at' => '2019-07-15 17:35:14',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            32 => 
            array (
                'id_prov' => '91',
                'nama' => 'Papua Barat',
                'created_at' => NULL,
                'updated_at' => '2019-07-16 10:54:45',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
            33 => 
            array (
                'id_prov' => '92',
                'nama' => 'Papua',
                'created_at' => NULL,
                'updated_at' => '2019-07-16 10:54:25',
                'type' => 'Standard',
                'project_id' => NULL,
            ),
        ));
        
        
    }
}