<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class JenisTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('jenis')->delete();
        
        \DB::table('jenis')->insert(array (
            0 => 
            array (
                'id_jenis' => '1',
                'nama' => 'kabupaten',
            ),
            1 => 
            array (
                'id_jenis' => '2',
                'nama' => 'kota',
            ),
            2 => 
            array (
                'id_jenis' => '3',
                'nama' => 'kelurahan',
            ),
            3 => 
            array (
                'id_jenis' => '4',
                'nama' => 'desa',
            ),
        ));
        
        
    }
}