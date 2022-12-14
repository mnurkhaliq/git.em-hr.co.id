<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SeaportsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('seaports')->delete();
        
        \DB::table('seaports')->insert(array (
            0 => 
            array (
                'id' => '1',
                'code' => 'TJP',
                'name' => 'Tanjung Priok',
                'cityCode' => 'JKT',
                'cityName' => 'Jakarta',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => '2',
                'code' => 'SMRG',
                'name' => 'Semarang',
                'cityCode' => 'JATENG',
                'cityName' => 'Semarang',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => '3',
                'code' => 'TJGITN',
                'name' => 'Pelabuhan Tanjung Intan',
                'cityCode' => 'JATENG',
                'cityName' => 'Cilacap',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => '4',
                'code' => 'BTGL',
                'name' => 'Pelabuhan Batu Guluk',
                'cityCode' => 'JATENG',
                'cityName' => 'Madura',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => '5',
                'code' => 'KLAG',
                'name' => 'Pelabuhan Kalianget',
                'cityCode' => 'JATIM',
                'cityName' => 'Madura',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => '6',
                'code' => 'KLMS',
                'name' => 'Pelabuhan Kalimas',
                'cityCode' => 'JATIM',
                'cityName' => 'Surabaya',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => '7',
                'code' => 'KML',
                'name' => 'Pelabuhan Kamal',
                'cityCode' => 'JATIM',
                'cityName' => 'Madura',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => '8',
                'code' => 'KTPG',
                'name' => 'Pelabuhan Ketapang',
                'cityCode' => 'JATIM',
                'cityName' => 'Banyuwangi',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => '9',
                'code' => 'TJGPRK',
                'name' => 'Pelabuhan Tanjung Perak',
                'cityCode' => 'JATIM',
                'cityName' => 'Surabaya',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => '10',
                'code' => 'UJG',
                'name' => 'Pelabuhan Ujung',
                'cityCode' => 'JATIM',
                'cityName' => 'Surabaya',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => '11',
                'code' => 'TJGWGI',
                'name' => 'Pelabuhan Tanjung Wangi',
                'cityCode' => 'JATIM',
                'cityName' => 'Banyuwangi',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => '12',
                'code' => 'CRBN',
                'name' => 'Pelabuhan Cirebon',
                'cityCode' => 'JATIM',
                'cityName' => 'Cirebon',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => '13',
                'code' => 'PRTW',
                'name' => 'Pelabuhan Pertiwi',
                'cityCode' => 'JABAR',
                'cityName' => 'Subang',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => '14',
                'code' => 'PRMK',
                'name' => 'Pelabuhan Pramuka',
                'cityCode' => 'JABAR',
                'cityName' => 'Garut',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => '15',
                'code' => 'MRK',
                'name' => 'Pelabuhan Merak',
                'cityCode' => 'JABAR',
                'cityName' => 'Banten',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => '16',
                'code' => 'SDKLP',
                'name' => 'Pelabuhan Sunda Kelapa',
                'cityCode' => 'BTN',
                'cityName' => 'Jakarta',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => '17',
                'code' => 'JAGOH',
                'name' => 'Pelabuhan ASDP Jagoh',
                'cityCode' => 'JKT',
                'cityName' => 'Jakarta',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => '18',
                'code' => 'DMPK',
                'name' => 'Pelabuhan ASDP Dompak',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Lingga',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => '19',
                'code' => 'PRTRMPK',
                'name' => 'Pelabuhan ASDP Parit Rempak',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Tanjungpinang',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => '20',
                'code' => 'TJNGUBN',
                'name' => 'Pelabuhan ASDP Tanjung Uban',
                'cityCode' => 'JATENG',
                'cityName' => 'Karimun',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => '21',
                'code' => 'TLGPGR',
                'name' => 'Pelabuhan ASDP Telaga Punggur',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Bintan',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => '22',
                'code' => 'BKG',
                'name' => 'Pelabuhan Bakong',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Batam',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => '23',
                'code' => 'BTMCT',
                'name' => 'Pelabuhan Batam Centre',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Lingga',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => '24',
                'code' => 'BTAPR',
                'name' => 'Pelabuhan Batu Ampar',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Batam',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => '25',
                'code' => 'BLGLG',
                'name' => 'Pelabuhan Bulang Linggi',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Batam',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => '26',
                'code' => 'DBSGP',
                'name' => 'Pelabuhan Dabo Singkep',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Bintan',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => '27',
                'code' => 'HRBB',
                'name' => 'Pelabuhan Harbour Bay',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Lingga',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => '28',
                'code' => 'KJGSB',
                'name' => 'Pelabuhan Kijang Sri Bayintan',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Batam',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            28 => 
            array (
                'id' => '29',
                'code' => 'KOTE',
                'name' => 'Pelabuhan Kote',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Bintan',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id' => '30',
                'code' => 'LTGJMJ',
                'name' => 'Pelabuhan Letung Jemaja',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Lingga',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            30 => 
            array (
                'id' => '31',
                'code' => 'MRKTA',
                'name' => 'Pelabuhan Marok Tua',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Kepulauan Anambas',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            31 => 
            array (
                'id' => '32',
                'code' => 'TLPGR',
                'name' => 'Pelabuhan Telaga Punggur',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Lingga',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            32 => 
            array (
                'id' => '33',
                'code' => 'TRMP',
                'name' => 'Pelabuhan Tarempa',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Batam',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            33 => 
            array (
                'id' => '34',
                'code' => 'TJSS',
                'name' => 'Pelabuhan Tanjung Setelung Serasan',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Kepulauan Anambas',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            34 => 
            array (
                'id' => '35',
                'code' => 'TJBTN',
                'name' => 'Pelabuhan Tanjung Buton',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Natuna',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            35 => 
            array (
                'id' => '36',
                'code' => 'TJNBK',
                'name' => 'Pelabuhan Tanjung Balai Karimun',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Lingga',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            36 => 
            array (
                'id' => '37',
                'code' => 'SGG',
                'name' => 'Pelabuhan Sunggak',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Karimun',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            37 => 
            array (
                'id' => '38',
                'code' => 'SGB',
                'name' => 'Pelabuhan Sungai Buluh',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Kepulauan Anambas',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            38 => 
            array (
                'id' => '39',
                'code' => 'SRP',
                'name' => 'Pelabuhan Sri Payung',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Lingga',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            39 => 
            array (
                'id' => '40',
                'code' => 'SRBP',
                'name' => 'Pelabuhan Sri Bintan Pura',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Tanjungpinang',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            40 => 
            array (
                'id' => '41',
                'code' => 'SJTNG',
                'name' => 'Pelabuhan Sijantung',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Tanjungpinang',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            41 => 
            array (
                'id' => '42',
                'code' => 'SKPNG',
                'name' => 'Pelabuhan Sekupang',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Batam',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            42 => 
            array (
                'id' => '43',
                'code' => 'SNYG',
                'name' => 'Pelabuhan Senayang',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Batam',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            43 => 
            array (
                'id' => '44',
                'code' => 'SETM',
                'name' => 'Pelabuhan Sei Tenam',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Lingga',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            44 => 
            array (
                'id' => '45',
                'code' => 'TLKBYR',
                'name' => 'Pelabuhan Teluk Bayur',
                'cityCode' => 'KPLNRIAU',
                'cityName' => 'Lingga',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            45 => 
            array (
                'id' => '46',
                'code' => 'MRA',
                'name' => 'Pelabuhan Muara',
                'cityCode' => 'SUMBAR',
                'cityName' => 'Padang',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            46 => 
            array (
                'id' => '47',
                'code' => 'TJGPDN',
                'name' => 'Pelabuhan Tanjung Pandan',
                'cityCode' => 'BGKABLTG',
                'cityName' => 'Bangka Belitung',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            47 => 
            array (
                'id' => '48',
                'code' => 'PKLBLM',
                'name' => 'Pelabuhan Pangkal Balam',
                'cityCode' => 'BGKABLTG',
                'cityName' => 'Bangka Belitung',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            48 => 
            array (
                'id' => '49',
                'code' => 'TJGBL',
                'name' => 'Pelabuhan Tanjung Balai',
                'cityCode' => 'SUMUT',
                'cityName' => 'Sumatera Utara',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            49 => 
            array (
                'id' => '50',
                'code' => 'BLWN',
                'name' => 'Pelabuhan Belawan',
                'cityCode' => 'SUMUT',
                'cityName' => 'Sumatera Utara',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            50 => 
            array (
                'id' => '51',
                'code' => 'YSHSK',
                'name' => 'Pelabuhan Yoseph Iskandar',
                'cityCode' => 'ACEH',
                'cityName' => 'Aceh Selatan',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            51 => 
            array (
                'id' => '52',
                'code' => 'KRGK',
                'name' => 'Pelabuhan Krueng Geukueh',
                'cityCode' => 'ACEH',
                'cityName' => 'Tapaktuan',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            52 => 
            array (
                'id' => '53',
                'code' => 'BJRMN',
                'name' => 'Banjarmasin',
                'cityCode' => 'KALSEL',
                'cityName' => 'Banjarmasin',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            53 => 
            array (
                'id' => '54',
                'code' => 'DWKR',
                'name' => 'Dwikora',
                'cityCode' => 'KALBAR',
                'cityName' => 'Batu Licin, Satui',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            54 => 
            array (
                'id' => '55',
                'code' => 'PLGK',
                'name' => 'Palangkaraya',
                'cityCode' => 'KALTENG',
                'cityName' => 'Palangkaraya',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            55 => 
            array (
                'id' => '56',
                'code' => 'SMYG',
                'name' => 'Pelabuhan Semayang',
                'cityCode' => 'KALTIM',
                'cityName' => 'Balikpapan',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            56 => 
            array (
                'id' => '57',
                'code' => 'MLDG',
                'name' => 'Pelabuhan Malundung',
                'cityCode' => 'KALUT',
                'cityName' => 'Tarakan',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            57 => 
            array (
                'id' => '58',
                'code' => 'TRSKT',
                'name' => 'Pelabuhan Trisakti',
                'cityCode' => 'KALUT',
                'cityName' => 'Tarakan',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            58 => 
            array (
                'id' => '59',
                'code' => 'SMDR',
                'name' => 'Pelabuhan Samudera',
                'cityCode' => 'KALSEL',
                'cityName' => 'Banjarmasin',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            59 => 
            array (
                'id' => '60',
                'code' => 'STNHSD',
                'name' => 'Pelabuhan Sulathan Hasanuddin',
                'cityCode' => 'SULSEL',
                'cityName' => 'Makassar',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            60 => 
            array (
                'id' => '61',
                'code' => 'GRTL',
                'name' => 'Pelabuhan Gorontalo',
                'cityCode' => 'SULUT',
                'cityName' => 'Gorontalo',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            61 => 
            array (
                'id' => '62',
                'code' => 'AGRK',
                'name' => 'Pelabuhan Anggrek',
                'cityCode' => 'SULUT',
                'cityName' => 'Gorontalo',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            62 => 
            array (
                'id' => '63',
                'code' => 'PTE',
                'name' => 'Pelabuhan Paotere',
                'cityCode' => 'SULUT',
                'cityName' => 'Gorontalo',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            63 => 
            array (
                'id' => '64',
                'code' => 'PMTT',
                'name' => 'Pelabuhan Pamatata',
                'cityCode' => 'SULSEL',
                'cityName' => 'Selayar',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            64 => 
            array (
                'id' => '65',
                'code' => 'TJGRGT',
                'name' => 'Pelabuhan Tanjung Ringgit',
                'cityCode' => 'SULSEL',
                'cityName' => 'Palopo',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            65 => 
            array (
                'id' => '66',
                'code' => 'BLP',
                'name' => 'Pelabuhan Belopa',
                'cityCode' => 'SULSEL',
                'cityName' => 'Belopa',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            66 => 
            array (
                'id' => '67',
                'code' => 'MLL',
                'name' => 'Pelabuhan Malili',
                'cityCode' => 'SULSEL',
                'cityName' => 'Malili',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            67 => 
            array (
                'id' => '68',
                'code' => 'PRE',
                'name' => 'Pelabuhan Pare Pare',
                'cityCode' => 'SULSEL',
                'cityName' => 'Pare-pare',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            68 => 
            array (
                'id' => '69',
                'code' => 'BRU',
                'name' => 'Pelabuhan Barru',
                'cityCode' => 'SULSEL',
                'cityName' => 'Barru',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            69 => 
            array (
                'id' => '70',
                'code' => 'PNTLN',
                'name' => 'Pelabuhan Pantoloan',
                'cityCode' => 'SULTENG',
                'cityName' => 'Palu',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            70 => 
            array (
                'id' => '71',
                'code' => 'KNDR',
                'name' => 'Pelabuhan Kendari',
                'cityCode' => 'SULTENG',
                'cityName' => 'Kendari',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            71 => 
            array (
                'id' => '72',
                'code' => 'BTON',
                'name' => 'Pelabuhan Buton',
                'cityCode' => 'SULTENG',
                'cityName' => 'Buton',
                'countryName' => 'Indonesia',
                'countryCode' => 'ID',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}