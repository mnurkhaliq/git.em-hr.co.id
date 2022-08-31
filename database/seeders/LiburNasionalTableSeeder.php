<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LiburNasionalTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('libur_nasional')->delete();
        
        \DB::table('libur_nasional')->insert(array (
            0 => 
            array (
                'id' => '43',
                'tanggal' => '2019-01-01',
                'keterangan' => 'Tahun Baru 2019 Masehi',
                'created_at' => '2018-05-07 03:49:11',
                'updated_at' => '2019-02-14 03:09:10',
            ),
            1 => 
            array (
                'id' => '44',
                'tanggal' => '2019-02-05',
                'keterangan' => 'Tahun Baru Imlek',
                'created_at' => '2018-05-07 03:49:11',
                'updated_at' => '2019-02-14 03:09:35',
            ),
            2 => 
            array (
                'id' => '45',
                'tanggal' => '2019-03-07',
                'keterangan' => 'Hari Raya Nyepi',
                'created_at' => '2018-05-07 03:49:11',
                'updated_at' => '2019-02-14 03:10:11',
            ),
            3 => 
            array (
                'id' => '46',
                'tanggal' => '2019-04-19',
                'keterangan' => 'Wafat Isa Al Masih',
                'created_at' => '2018-05-07 03:49:11',
                'updated_at' => '2019-02-20 18:37:34',
            ),
            4 => 
            array (
                'id' => '47',
                'tanggal' => '2019-04-03',
                'keterangan' => 'Isra Mikraj Nabi Muhammad SAW',
                'created_at' => '2018-05-07 03:49:11',
                'updated_at' => '2019-02-20 18:37:11',
            ),
            5 => 
            array (
                'id' => '48',
                'tanggal' => '2019-05-01',
                'keterangan' => 'Hari Buruh Internasional',
                'created_at' => '2018-05-07 03:49:11',
                'updated_at' => '2019-02-20 18:38:16',
            ),
            6 => 
            array (
                'id' => '49',
                'tanggal' => '2019-05-30',
                'keterangan' => 'Kenaikan Isa Al Masih',
                'created_at' => '2018-05-07 03:49:11',
                'updated_at' => '2019-02-20 18:39:20',
            ),
            7 => 
            array (
                'id' => '50',
                'tanggal' => '2019-05-19',
                'keterangan' => 'Hari Raya Waisak 2562',
                'created_at' => '2018-05-07 03:49:11',
                'updated_at' => '2019-02-20 18:38:56',
            ),
            8 => 
            array (
                'id' => '51',
                'tanggal' => '2019-06-01',
                'keterangan' => 'Hari Lahir Pancasila',
                'created_at' => '2018-05-07 03:49:11',
                'updated_at' => '2019-02-20 18:39:43',
            ),
            9 => 
            array (
                'id' => '52',
                'tanggal' => '2019-06-05',
                'keterangan' => 'Hari Raya Idul Fitri 1439 Hijriyah',
                'created_at' => '2018-05-07 03:49:11',
                'updated_at' => '2019-02-20 18:40:05',
            ),
            10 => 
            array (
                'id' => '53',
                'tanggal' => '2019-06-06',
                'keterangan' => 'Hari Raya Idul Fitri 1439 Hijriyah',
                'created_at' => '2018-05-07 03:49:12',
                'updated_at' => '2019-02-20 18:41:01',
            ),
            11 => 
            array (
                'id' => '54',
                'tanggal' => '2019-08-17',
                'keterangan' => 'Hari Kemerdekaan Republik Indonesia',
                'created_at' => '2018-05-07 03:49:12',
                'updated_at' => '2019-02-20 18:41:43',
            ),
            12 => 
            array (
                'id' => '55',
                'tanggal' => '2019-08-11',
                'keterangan' => 'Hari Raya Idul Adha 1439 Hijriyah',
                'created_at' => '2018-05-07 03:49:12',
                'updated_at' => '2019-02-20 18:43:46',
            ),
            13 => 
            array (
                'id' => '56',
                'tanggal' => '2019-09-01',
                'keterangan' => 'Tahun Baru Islam 1440 Hijriyah',
                'created_at' => '2018-05-07 03:49:12',
                'updated_at' => '2019-02-20 18:42:21',
            ),
            14 => 
            array (
                'id' => '57',
                'tanggal' => '2019-11-09',
                'keterangan' => 'Maulid Nabi Muhammad SAW',
                'created_at' => '2018-05-07 03:49:12',
                'updated_at' => '2019-02-20 18:42:50',
            ),
            15 => 
            array (
                'id' => '58',
                'tanggal' => '2019-12-25',
                'keterangan' => 'Hari Raya Natal',
                'created_at' => '2018-05-07 03:49:12',
                'updated_at' => '2019-02-14 03:08:38',
            ),
        ));
        
        
    }
}