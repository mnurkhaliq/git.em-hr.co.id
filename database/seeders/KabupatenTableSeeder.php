<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KabupatenTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('kabupaten')->delete();
        
        \DB::table('kabupaten')->insert(array (
            0 => 
            array (
                'id_kab' => '1101',
                'id_prov' => '11',
                'nama' => 'ACEH SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id_kab' => '1102',
                'id_prov' => '11',
                'nama' => 'ACEH TENGGARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id_kab' => '1103',
                'id_prov' => '11',
                'nama' => 'ACEH TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id_kab' => '1104',
                'id_prov' => '11',
                'nama' => 'ACEH TENGAH',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id_kab' => '1105',
                'id_prov' => '11',
                'nama' => 'ACEH BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id_kab' => '1106',
                'id_prov' => '11',
                'nama' => 'ACEH BESAR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id_kab' => '1107',
                'id_prov' => '11',
                'nama' => 'PIDIE',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id_kab' => '1108',
                'id_prov' => '11',
                'nama' => 'ACEH UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id_kab' => '1109',
                'id_prov' => '11',
                'nama' => 'SIMEULUE',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id_kab' => '1110',
                'id_prov' => '11',
                'nama' => 'ACEH SINGKIL',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id_kab' => '1111',
                'id_prov' => '11',
                'nama' => 'BIREUEN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id_kab' => '1112',
                'id_prov' => '11',
                'nama' => 'ACEH BARAT DAYA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id_kab' => '1113',
                'id_prov' => '11',
                'nama' => 'GAYO LUES',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id_kab' => '1114',
                'id_prov' => '11',
                'nama' => 'ACEH JAYA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id_kab' => '1115',
                'id_prov' => '11',
                'nama' => 'NAGAN RAYA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id_kab' => '1116',
                'id_prov' => '11',
                'nama' => 'ACEH TAMIANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id_kab' => '1117',
                'id_prov' => '11',
                'nama' => 'BENER MERIAH',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id_kab' => '1118',
                'id_prov' => '11',
                'nama' => 'PIDIE JAYA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id_kab' => '1171',
                'id_prov' => '11',
                'nama' => 'BANDA ACEH',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id_kab' => '1172',
                'id_prov' => '11',
                'nama' => 'SABANG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id_kab' => '1173',
                'id_prov' => '11',
                'nama' => 'LHOKSEUMAWE',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id_kab' => '1174',
                'id_prov' => '11',
                'nama' => 'LANGSA',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id_kab' => '1175',
                'id_prov' => '11',
                'nama' => 'SUBULUSSALAM',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id_kab' => '1201',
                'id_prov' => '12',
                'nama' => 'TAPANULI TENGAH',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id_kab' => '1202',
                'id_prov' => '12',
                'nama' => 'TAPANULI UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id_kab' => '1203',
                'id_prov' => '12',
                'nama' => 'TAPANULI SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id_kab' => '1204',
                'id_prov' => '12',
                'nama' => 'NIAS',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id_kab' => '1205',
                'id_prov' => '12',
                'nama' => 'LANGKAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            28 => 
            array (
                'id_kab' => '1206',
                'id_prov' => '12',
                'nama' => 'KARO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id_kab' => '1207',
                'id_prov' => '12',
                'nama' => 'DELI SERDANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            30 => 
            array (
                'id_kab' => '1208',
                'id_prov' => '12',
                'nama' => 'SIMALUNGUN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            31 => 
            array (
                'id_kab' => '1209',
                'id_prov' => '12',
                'nama' => 'ASAHAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            32 => 
            array (
                'id_kab' => '1210',
                'id_prov' => '12',
                'nama' => 'LABUHANBATU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            33 => 
            array (
                'id_kab' => '1211',
                'id_prov' => '12',
                'nama' => 'DAIRI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            34 => 
            array (
                'id_kab' => '1212',
                'id_prov' => '12',
                'nama' => 'TOBA SAMOSIR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            35 => 
            array (
                'id_kab' => '1213',
                'id_prov' => '12',
                'nama' => 'MANDAILING NATAL',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            36 => 
            array (
                'id_kab' => '1214',
                'id_prov' => '12',
                'nama' => 'NIAS SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            37 => 
            array (
                'id_kab' => '1215',
                'id_prov' => '12',
                'nama' => 'PAKPAK BHARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            38 => 
            array (
                'id_kab' => '1216',
                'id_prov' => '12',
                'nama' => 'HUMBANG HASUNDUTAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            39 => 
            array (
                'id_kab' => '1217',
                'id_prov' => '12',
                'nama' => 'SAMOSIR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            40 => 
            array (
                'id_kab' => '1218',
                'id_prov' => '12',
                'nama' => 'SERDANG BEDAGAI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            41 => 
            array (
                'id_kab' => '1219',
                'id_prov' => '12',
                'nama' => 'BATU BARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            42 => 
            array (
                'id_kab' => '1220',
                'id_prov' => '12',
                'nama' => 'PADANG LAWAS UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            43 => 
            array (
                'id_kab' => '1221',
                'id_prov' => '12',
                'nama' => 'PADANG LAWAS',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            44 => 
            array (
                'id_kab' => '1222',
                'id_prov' => '12',
                'nama' => 'LABUHANBATU SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            45 => 
            array (
                'id_kab' => '1223',
                'id_prov' => '12',
                'nama' => 'LABUHANBATU UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            46 => 
            array (
                'id_kab' => '1224',
                'id_prov' => '12',
                'nama' => 'NIAS UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            47 => 
            array (
                'id_kab' => '1225',
                'id_prov' => '12',
                'nama' => 'NIAS BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            48 => 
            array (
                'id_kab' => '1271',
                'id_prov' => '12',
                'nama' => 'MEDAN',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            49 => 
            array (
                'id_kab' => '1272',
                'id_prov' => '12',
                'nama' => 'PEMATANG SIANTAR',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            50 => 
            array (
                'id_kab' => '1273',
                'id_prov' => '12',
                'nama' => 'SIBOLGA',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            51 => 
            array (
                'id_kab' => '1274',
                'id_prov' => '12',
                'nama' => 'TANJUNG BALAI',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            52 => 
            array (
                'id_kab' => '1275',
                'id_prov' => '12',
                'nama' => 'BINJAI',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            53 => 
            array (
                'id_kab' => '1276',
                'id_prov' => '12',
                'nama' => 'TEBING TINGGI',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            54 => 
            array (
                'id_kab' => '1277',
                'id_prov' => '12',
                'nama' => 'PADANGSIDIMPUAN',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            55 => 
            array (
                'id_kab' => '1278',
                'id_prov' => '12',
                'nama' => 'GUNUNGSITOLI',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            56 => 
            array (
                'id_kab' => '1301',
                'id_prov' => '13',
                'nama' => 'PESISIR SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            57 => 
            array (
                'id_kab' => '1302',
                'id_prov' => '13',
                'nama' => 'SOLOK',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            58 => 
            array (
                'id_kab' => '1303',
                'id_prov' => '13',
                'nama' => 'SIJUNJUNG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            59 => 
            array (
                'id_kab' => '1304',
                'id_prov' => '13',
                'nama' => 'TANAH DATAR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            60 => 
            array (
                'id_kab' => '1305',
                'id_prov' => '13',
                'nama' => 'PADANG PARIAMAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            61 => 
            array (
                'id_kab' => '1306',
                'id_prov' => '13',
                'nama' => 'AGAM',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            62 => 
            array (
                'id_kab' => '1307',
                'id_prov' => '13',
                'nama' => 'LIMA PULUH KOTA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            63 => 
            array (
                'id_kab' => '1308',
                'id_prov' => '13',
                'nama' => 'PASAMAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            64 => 
            array (
                'id_kab' => '1309',
                'id_prov' => '13',
                'nama' => 'KEPULAUAN MENTAWAI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            65 => 
            array (
                'id_kab' => '1310',
                'id_prov' => '13',
                'nama' => 'DHARMASRAYA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            66 => 
            array (
                'id_kab' => '1311',
                'id_prov' => '13',
                'nama' => 'SOLOK SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            67 => 
            array (
                'id_kab' => '1312',
                'id_prov' => '13',
                'nama' => 'PASAMAN BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            68 => 
            array (
                'id_kab' => '1371',
                'id_prov' => '13',
                'nama' => 'PADANG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            69 => 
            array (
                'id_kab' => '1372',
                'id_prov' => '13',
                'nama' => 'SOLOK',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            70 => 
            array (
                'id_kab' => '1373',
                'id_prov' => '13',
                'nama' => 'SAWAHLUNTO',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            71 => 
            array (
                'id_kab' => '1374',
                'id_prov' => '13',
                'nama' => 'PADANG PANJANG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            72 => 
            array (
                'id_kab' => '1375',
                'id_prov' => '13',
                'nama' => 'BUKITTINGGI',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            73 => 
            array (
                'id_kab' => '1376',
                'id_prov' => '13',
                'nama' => 'PAYAKUMBUH',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            74 => 
            array (
                'id_kab' => '1377',
                'id_prov' => '13',
                'nama' => 'PARIAMAN',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            75 => 
            array (
                'id_kab' => '1401',
                'id_prov' => '14',
                'nama' => 'KAMPAR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            76 => 
            array (
                'id_kab' => '1402',
                'id_prov' => '14',
                'nama' => 'INDRAGIRI HULU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            77 => 
            array (
                'id_kab' => '1403',
                'id_prov' => '14',
                'nama' => 'BENGKALIS',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            78 => 
            array (
                'id_kab' => '1404',
                'id_prov' => '14',
                'nama' => 'INDRAGIRI HILIR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            79 => 
            array (
                'id_kab' => '1405',
                'id_prov' => '14',
                'nama' => 'PELALAWAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            80 => 
            array (
                'id_kab' => '1406',
                'id_prov' => '14',
                'nama' => 'ROKAN HULU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            81 => 
            array (
                'id_kab' => '1407',
                'id_prov' => '14',
                'nama' => 'ROKAN HILIR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            82 => 
            array (
                'id_kab' => '1408',
                'id_prov' => '14',
                'nama' => 'SIAK',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            83 => 
            array (
                'id_kab' => '1409',
                'id_prov' => '14',
                'nama' => 'KUANTAN SINGINGI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            84 => 
            array (
                'id_kab' => '1410',
                'id_prov' => '14',
                'nama' => 'KEPULAUAN MERANTI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            85 => 
            array (
                'id_kab' => '1471',
                'id_prov' => '14',
                'nama' => 'PEKANBARU',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            86 => 
            array (
                'id_kab' => '1472',
                'id_prov' => '14',
                'nama' => 'DUMAI',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            87 => 
            array (
                'id_kab' => '1501',
                'id_prov' => '15',
                'nama' => 'KERINCI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            88 => 
            array (
                'id_kab' => '1502',
                'id_prov' => '15',
                'nama' => 'MERANGIN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            89 => 
            array (
                'id_kab' => '1503',
                'id_prov' => '15',
                'nama' => 'SAROLANGUN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            90 => 
            array (
                'id_kab' => '1504',
                'id_prov' => '15',
                'nama' => 'BATANGHARI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            91 => 
            array (
                'id_kab' => '1505',
                'id_prov' => '15',
                'nama' => 'MUARO JAMBI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            92 => 
            array (
                'id_kab' => '1506',
                'id_prov' => '15',
                'nama' => 'TANJUNG JABUNG BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            93 => 
            array (
                'id_kab' => '1507',
                'id_prov' => '15',
                'nama' => 'TANJUNG JABUNG TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            94 => 
            array (
                'id_kab' => '1508',
                'id_prov' => '15',
                'nama' => 'BUNGO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            95 => 
            array (
                'id_kab' => '1509',
                'id_prov' => '15',
                'nama' => 'TEBO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            96 => 
            array (
                'id_kab' => '1571',
                'id_prov' => '15',
                'nama' => 'JAMBI',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            97 => 
            array (
                'id_kab' => '1572',
                'id_prov' => '15',
                'nama' => 'SUNGAI PENUH',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            98 => 
            array (
                'id_kab' => '1601',
                'id_prov' => '16',
                'nama' => 'OGAN KOMERING ULU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            99 => 
            array (
                'id_kab' => '1602',
                'id_prov' => '16',
                'nama' => 'OGAN KOMERING ILIR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            100 => 
            array (
                'id_kab' => '1603',
                'id_prov' => '16',
                'nama' => 'MUARA ENIM',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            101 => 
            array (
                'id_kab' => '1604',
                'id_prov' => '16',
                'nama' => 'LAHAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            102 => 
            array (
                'id_kab' => '1605',
                'id_prov' => '16',
                'nama' => 'MUSI RAWAS',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            103 => 
            array (
                'id_kab' => '1606',
                'id_prov' => '16',
                'nama' => 'MUSI BANYUASIN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            104 => 
            array (
                'id_kab' => '1607',
                'id_prov' => '16',
                'nama' => 'BANYUASIN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            105 => 
            array (
                'id_kab' => '1608',
                'id_prov' => '16',
                'nama' => 'OGAN KOMERING ULU TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            106 => 
            array (
                'id_kab' => '1609',
                'id_prov' => '16',
                'nama' => 'OGAN KOMERING ULU SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            107 => 
            array (
                'id_kab' => '1610',
                'id_prov' => '16',
                'nama' => 'OGAN ILIR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            108 => 
            array (
                'id_kab' => '1611',
                'id_prov' => '16',
                'nama' => 'EMPAT LAWANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            109 => 
            array (
                'id_kab' => '1612',
                'id_prov' => '16',
                'nama' => 'PENUKAL ABAB LEMATANG ILIR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            110 => 
            array (
                'id_kab' => '1613',
                'id_prov' => '16',
                'nama' => 'MUSI RAWAS UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            111 => 
            array (
                'id_kab' => '1671',
                'id_prov' => '16',
                'nama' => 'PALEMBANG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            112 => 
            array (
                'id_kab' => '1672',
                'id_prov' => '16',
                'nama' => 'PAGAR ALAM',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            113 => 
            array (
                'id_kab' => '1673',
                'id_prov' => '16',
                'nama' => 'LUBUK LINGGAU',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            114 => 
            array (
                'id_kab' => '1674',
                'id_prov' => '16',
                'nama' => 'PRABUMULIH',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            115 => 
            array (
                'id_kab' => '1701',
                'id_prov' => '17',
                'nama' => 'BENGKULU SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            116 => 
            array (
                'id_kab' => '1702',
                'id_prov' => '17',
                'nama' => 'REJANG LEBONG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            117 => 
            array (
                'id_kab' => '1703',
                'id_prov' => '17',
                'nama' => 'BENGKULU UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            118 => 
            array (
                'id_kab' => '1704',
                'id_prov' => '17',
                'nama' => 'KAUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            119 => 
            array (
                'id_kab' => '1705',
                'id_prov' => '17',
                'nama' => 'SELUMA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            120 => 
            array (
                'id_kab' => '1706',
                'id_prov' => '17',
                'nama' => 'MUKO MUKO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            121 => 
            array (
                'id_kab' => '1707',
                'id_prov' => '17',
                'nama' => 'LEBONG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            122 => 
            array (
                'id_kab' => '1708',
                'id_prov' => '17',
                'nama' => 'KEPAHIANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            123 => 
            array (
                'id_kab' => '1709',
                'id_prov' => '17',
                'nama' => 'BENGKULU TENGAH',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            124 => 
            array (
                'id_kab' => '1771',
                'id_prov' => '17',
                'nama' => 'BENGKULU',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            125 => 
            array (
                'id_kab' => '1801',
                'id_prov' => '18',
                'nama' => 'LAMPUNG SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            126 => 
            array (
                'id_kab' => '1802',
                'id_prov' => '18',
                'nama' => 'LAMPUNG TENGAH',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            127 => 
            array (
                'id_kab' => '1803',
                'id_prov' => '18',
                'nama' => 'LAMPUNG UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            128 => 
            array (
                'id_kab' => '1804',
                'id_prov' => '18',
                'nama' => 'LAMPUNG BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            129 => 
            array (
                'id_kab' => '1805',
                'id_prov' => '18',
                'nama' => 'TULANG BAWANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            130 => 
            array (
                'id_kab' => '1806',
                'id_prov' => '18',
                'nama' => 'TANGGAMUS',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            131 => 
            array (
                'id_kab' => '1807',
                'id_prov' => '18',
                'nama' => 'LAMPUNG TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            132 => 
            array (
                'id_kab' => '1808',
                'id_prov' => '18',
                'nama' => 'WAY KANAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            133 => 
            array (
                'id_kab' => '1809',
                'id_prov' => '18',
                'nama' => 'PESAWARAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            134 => 
            array (
                'id_kab' => '1810',
                'id_prov' => '18',
                'nama' => 'PRINGSEWU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            135 => 
            array (
                'id_kab' => '1811',
                'id_prov' => '18',
                'nama' => 'MESUJI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            136 => 
            array (
                'id_kab' => '1812',
                'id_prov' => '18',
                'nama' => 'TULANG BAWANG BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            137 => 
            array (
                'id_kab' => '1813',
                'id_prov' => '18',
                'nama' => 'PESISIR BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            138 => 
            array (
                'id_kab' => '1871',
                'id_prov' => '18',
                'nama' => 'BANDAR LAMPUNG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            139 => 
            array (
                'id_kab' => '1872',
                'id_prov' => '18',
                'nama' => 'METRO',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            140 => 
            array (
                'id_kab' => '1901',
                'id_prov' => '19',
                'nama' => 'BANGKA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            141 => 
            array (
                'id_kab' => '1902',
                'id_prov' => '19',
                'nama' => 'BELITUNG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            142 => 
            array (
                'id_kab' => '1903',
                'id_prov' => '19',
                'nama' => 'BANGKA SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            143 => 
            array (
                'id_kab' => '1904',
                'id_prov' => '19',
                'nama' => 'BANGKA TENGAH',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            144 => 
            array (
                'id_kab' => '1905',
                'id_prov' => '19',
                'nama' => 'BANGKA BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            145 => 
            array (
                'id_kab' => '1906',
                'id_prov' => '19',
                'nama' => 'BELITUNG TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            146 => 
            array (
                'id_kab' => '1971',
                'id_prov' => '19',
                'nama' => 'PANGKAL PINANG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            147 => 
            array (
                'id_kab' => '2101',
                'id_prov' => '21',
                'nama' => 'BINTAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            148 => 
            array (
                'id_kab' => '2102',
                'id_prov' => '21',
                'nama' => 'KARIMUN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            149 => 
            array (
                'id_kab' => '2103',
                'id_prov' => '21',
                'nama' => 'NATUNA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            150 => 
            array (
                'id_kab' => '2104',
                'id_prov' => '21',
                'nama' => 'LINGGA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            151 => 
            array (
                'id_kab' => '2105',
                'id_prov' => '21',
                'nama' => 'KEPULAUAN ANAMBAS',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            152 => 
            array (
                'id_kab' => '2171',
                'id_prov' => '21',
                'nama' => 'BATAM',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            153 => 
            array (
                'id_kab' => '2172',
                'id_prov' => '21',
                'nama' => 'TANJUNG PINANG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            154 => 
            array (
                'id_kab' => '3101',
                'id_prov' => '31',
                'nama' => 'KEPULAUAN SERIBU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            155 => 
            array (
                'id_kab' => '3171',
                'id_prov' => '31',
                'nama' => 'JAKARTA PUSAT',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            156 => 
            array (
                'id_kab' => '3172',
                'id_prov' => '31',
                'nama' => 'JAKARTA UTARA',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            157 => 
            array (
                'id_kab' => '3173',
                'id_prov' => '31',
                'nama' => 'JAKARTA BARAT',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            158 => 
            array (
                'id_kab' => '3174',
                'id_prov' => '31',
                'nama' => 'JAKARTA SELATAN',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            159 => 
            array (
                'id_kab' => '3175',
                'id_prov' => '31',
                'nama' => 'JAKARTA TIMUR',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            160 => 
            array (
                'id_kab' => '3201',
                'id_prov' => '32',
                'nama' => 'BOGOR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            161 => 
            array (
                'id_kab' => '3202',
                'id_prov' => '32',
                'nama' => 'SUKABUMI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            162 => 
            array (
                'id_kab' => '3203',
                'id_prov' => '32',
                'nama' => 'CIANJUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            163 => 
            array (
                'id_kab' => '3204',
                'id_prov' => '32',
                'nama' => 'BANDUNG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            164 => 
            array (
                'id_kab' => '3205',
                'id_prov' => '32',
                'nama' => 'GARUT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            165 => 
            array (
                'id_kab' => '3206',
                'id_prov' => '32',
                'nama' => 'TASIKMALAYA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            166 => 
            array (
                'id_kab' => '3207',
                'id_prov' => '32',
                'nama' => 'CIAMIS',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            167 => 
            array (
                'id_kab' => '3208',
                'id_prov' => '32',
                'nama' => 'KUNINGAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            168 => 
            array (
                'id_kab' => '3209',
                'id_prov' => '32',
                'nama' => 'CIREBON',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            169 => 
            array (
                'id_kab' => '3210',
                'id_prov' => '32',
                'nama' => 'MAJALENGKA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            170 => 
            array (
                'id_kab' => '3211',
                'id_prov' => '32',
                'nama' => 'SUMEDANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            171 => 
            array (
                'id_kab' => '3212',
                'id_prov' => '32',
                'nama' => 'INDRAMAYU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            172 => 
            array (
                'id_kab' => '3213',
                'id_prov' => '32',
                'nama' => 'SUBANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            173 => 
            array (
                'id_kab' => '3214',
                'id_prov' => '32',
                'nama' => 'PURWAKARTA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            174 => 
            array (
                'id_kab' => '3215',
                'id_prov' => '32',
                'nama' => 'KARAWANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            175 => 
            array (
                'id_kab' => '3216',
                'id_prov' => '32',
                'nama' => 'BEKASI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            176 => 
            array (
                'id_kab' => '3217',
                'id_prov' => '32',
                'nama' => 'BANDUNG BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            177 => 
            array (
                'id_kab' => '3218',
                'id_prov' => '32',
                'nama' => 'PANGANDARAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            178 => 
            array (
                'id_kab' => '3271',
                'id_prov' => '32',
                'nama' => 'BOGOR',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            179 => 
            array (
                'id_kab' => '3272',
                'id_prov' => '32',
                'nama' => 'SUKABUMI',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            180 => 
            array (
                'id_kab' => '3273',
                'id_prov' => '32',
                'nama' => 'BANDUNG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            181 => 
            array (
                'id_kab' => '3274',
                'id_prov' => '32',
                'nama' => 'CIREBON',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            182 => 
            array (
                'id_kab' => '3275',
                'id_prov' => '32',
                'nama' => 'BEKASI',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            183 => 
            array (
                'id_kab' => '3276',
                'id_prov' => '32',
                'nama' => 'DEPOK',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            184 => 
            array (
                'id_kab' => '3277',
                'id_prov' => '32',
                'nama' => 'CIMAHI',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            185 => 
            array (
                'id_kab' => '3278',
                'id_prov' => '32',
                'nama' => 'TASIKMALAYA',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            186 => 
            array (
                'id_kab' => '3279',
                'id_prov' => '32',
                'nama' => 'BANJAR',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            187 => 
            array (
                'id_kab' => '3301',
                'id_prov' => '33',
                'nama' => 'CILACAP',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            188 => 
            array (
                'id_kab' => '3302',
                'id_prov' => '33',
                'nama' => 'BANYUMAS',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            189 => 
            array (
                'id_kab' => '3303',
                'id_prov' => '33',
                'nama' => 'PURBALINGGA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            190 => 
            array (
                'id_kab' => '3304',
                'id_prov' => '33',
                'nama' => 'BANJARNEGARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            191 => 
            array (
                'id_kab' => '3305',
                'id_prov' => '33',
                'nama' => 'KEBUMEN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            192 => 
            array (
                'id_kab' => '3306',
                'id_prov' => '33',
                'nama' => 'PURWOREJO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            193 => 
            array (
                'id_kab' => '3307',
                'id_prov' => '33',
                'nama' => 'WONOSOBO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            194 => 
            array (
                'id_kab' => '3308',
                'id_prov' => '33',
                'nama' => 'MAGELANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            195 => 
            array (
                'id_kab' => '3309',
                'id_prov' => '33',
                'nama' => 'BOYOLALI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            196 => 
            array (
                'id_kab' => '3310',
                'id_prov' => '33',
                'nama' => 'KLATEN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            197 => 
            array (
                'id_kab' => '3311',
                'id_prov' => '33',
                'nama' => 'SUKOHARJO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            198 => 
            array (
                'id_kab' => '3312',
                'id_prov' => '33',
                'nama' => 'WONOGIRI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            199 => 
            array (
                'id_kab' => '3313',
                'id_prov' => '33',
                'nama' => 'KARANGANYAR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            200 => 
            array (
                'id_kab' => '3314',
                'id_prov' => '33',
                'nama' => 'SRAGEN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            201 => 
            array (
                'id_kab' => '3315',
                'id_prov' => '33',
                'nama' => 'GROBOGAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            202 => 
            array (
                'id_kab' => '3316',
                'id_prov' => '33',
                'nama' => 'BLORA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            203 => 
            array (
                'id_kab' => '3317',
                'id_prov' => '33',
                'nama' => 'REMBANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            204 => 
            array (
                'id_kab' => '3318',
                'id_prov' => '33',
                'nama' => 'PATI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            205 => 
            array (
                'id_kab' => '3319',
                'id_prov' => '33',
                'nama' => 'KUDUS',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            206 => 
            array (
                'id_kab' => '3320',
                'id_prov' => '33',
                'nama' => 'JEPARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            207 => 
            array (
                'id_kab' => '3321',
                'id_prov' => '33',
                'nama' => 'DEMAK',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            208 => 
            array (
                'id_kab' => '3322',
                'id_prov' => '33',
                'nama' => 'SEMARANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            209 => 
            array (
                'id_kab' => '3323',
                'id_prov' => '33',
                'nama' => 'TEMANGGUNG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            210 => 
            array (
                'id_kab' => '3324',
                'id_prov' => '33',
                'nama' => 'KENDAL',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            211 => 
            array (
                'id_kab' => '3325',
                'id_prov' => '33',
                'nama' => 'BATANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            212 => 
            array (
                'id_kab' => '3326',
                'id_prov' => '33',
                'nama' => 'PEKALONGAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            213 => 
            array (
                'id_kab' => '3327',
                'id_prov' => '33',
                'nama' => 'PEMALANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            214 => 
            array (
                'id_kab' => '3328',
                'id_prov' => '33',
                'nama' => 'TEGAL',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            215 => 
            array (
                'id_kab' => '3329',
                'id_prov' => '33',
                'nama' => 'BREBES',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            216 => 
            array (
                'id_kab' => '3371',
                'id_prov' => '33',
                'nama' => 'MAGELANG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            217 => 
            array (
                'id_kab' => '3372',
                'id_prov' => '33',
                'nama' => 'SURAKARTA',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            218 => 
            array (
                'id_kab' => '3373',
                'id_prov' => '33',
                'nama' => 'SALATIGA',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            219 => 
            array (
                'id_kab' => '3374',
                'id_prov' => '33',
                'nama' => 'SEMARANG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            220 => 
            array (
                'id_kab' => '3375',
                'id_prov' => '33',
                'nama' => 'PEKALONGAN',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            221 => 
            array (
                'id_kab' => '3376',
                'id_prov' => '33',
                'nama' => 'TEGAL',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            222 => 
            array (
                'id_kab' => '3401',
                'id_prov' => '34',
                'nama' => 'KULON PROGO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            223 => 
            array (
                'id_kab' => '3402',
                'id_prov' => '34',
                'nama' => 'BANTUL',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            224 => 
            array (
                'id_kab' => '3403',
                'id_prov' => '34',
                'nama' => 'GUNUNG KIDUL',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            225 => 
            array (
                'id_kab' => '3404',
                'id_prov' => '34',
                'nama' => 'SLEMAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            226 => 
            array (
                'id_kab' => '3471',
                'id_prov' => '34',
                'nama' => 'YOGYAKARTA',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            227 => 
            array (
                'id_kab' => '3501',
                'id_prov' => '35',
                'nama' => 'PACITAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            228 => 
            array (
                'id_kab' => '3502',
                'id_prov' => '35',
                'nama' => 'PONOROGO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            229 => 
            array (
                'id_kab' => '3503',
                'id_prov' => '35',
                'nama' => 'TRENGGALEK',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            230 => 
            array (
                'id_kab' => '3504',
                'id_prov' => '35',
                'nama' => 'TULUNGAGUNG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            231 => 
            array (
                'id_kab' => '3505',
                'id_prov' => '35',
                'nama' => 'BLITAR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            232 => 
            array (
                'id_kab' => '3506',
                'id_prov' => '35',
                'nama' => 'KEDIRI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            233 => 
            array (
                'id_kab' => '3507',
                'id_prov' => '35',
                'nama' => 'MALANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            234 => 
            array (
                'id_kab' => '3508',
                'id_prov' => '35',
                'nama' => 'LUMAJANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            235 => 
            array (
                'id_kab' => '3509',
                'id_prov' => '35',
                'nama' => 'JEMBER',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            236 => 
            array (
                'id_kab' => '3510',
                'id_prov' => '35',
                'nama' => 'BANYUWANGI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            237 => 
            array (
                'id_kab' => '3511',
                'id_prov' => '35',
                'nama' => 'BONDOWOSO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            238 => 
            array (
                'id_kab' => '3512',
                'id_prov' => '35',
                'nama' => 'SITUBONDO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            239 => 
            array (
                'id_kab' => '3513',
                'id_prov' => '35',
                'nama' => 'PROBOLINGGO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            240 => 
            array (
                'id_kab' => '3514',
                'id_prov' => '35',
                'nama' => 'PASURUAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            241 => 
            array (
                'id_kab' => '3515',
                'id_prov' => '35',
                'nama' => 'SIDOARJO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            242 => 
            array (
                'id_kab' => '3516',
                'id_prov' => '35',
                'nama' => 'MOJOKERTO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            243 => 
            array (
                'id_kab' => '3517',
                'id_prov' => '35',
                'nama' => 'JOMBANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            244 => 
            array (
                'id_kab' => '3518',
                'id_prov' => '35',
                'nama' => 'NGANJUK',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            245 => 
            array (
                'id_kab' => '3519',
                'id_prov' => '35',
                'nama' => 'MADIUN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            246 => 
            array (
                'id_kab' => '3520',
                'id_prov' => '35',
                'nama' => 'MAGETAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            247 => 
            array (
                'id_kab' => '3521',
                'id_prov' => '35',
                'nama' => 'NGAWI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            248 => 
            array (
                'id_kab' => '3522',
                'id_prov' => '35',
                'nama' => 'BOJONEGORO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            249 => 
            array (
                'id_kab' => '3523',
                'id_prov' => '35',
                'nama' => 'TUBAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            250 => 
            array (
                'id_kab' => '3524',
                'id_prov' => '35',
                'nama' => 'LAMONGAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            251 => 
            array (
                'id_kab' => '3525',
                'id_prov' => '35',
                'nama' => 'GRESIK',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            252 => 
            array (
                'id_kab' => '3526',
                'id_prov' => '35',
                'nama' => 'BANGKALAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            253 => 
            array (
                'id_kab' => '3527',
                'id_prov' => '35',
                'nama' => 'SAMPANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            254 => 
            array (
                'id_kab' => '3528',
                'id_prov' => '35',
                'nama' => 'PAMEKASAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            255 => 
            array (
                'id_kab' => '3529',
                'id_prov' => '35',
                'nama' => 'SUMENEP',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            256 => 
            array (
                'id_kab' => '3571',
                'id_prov' => '35',
                'nama' => 'KEDIRI',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            257 => 
            array (
                'id_kab' => '3572',
                'id_prov' => '35',
                'nama' => 'BLITAR',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            258 => 
            array (
                'id_kab' => '3573',
                'id_prov' => '35',
                'nama' => 'MALANG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            259 => 
            array (
                'id_kab' => '3574',
                'id_prov' => '35',
                'nama' => 'PROBOLINGGO',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            260 => 
            array (
                'id_kab' => '3575',
                'id_prov' => '35',
                'nama' => 'PASURUAN',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            261 => 
            array (
                'id_kab' => '3576',
                'id_prov' => '35',
                'nama' => 'MOJOKERTO',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            262 => 
            array (
                'id_kab' => '3577',
                'id_prov' => '35',
                'nama' => 'MADIUN',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            263 => 
            array (
                'id_kab' => '3578',
                'id_prov' => '35',
                'nama' => 'SURABAYA',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            264 => 
            array (
                'id_kab' => '3579',
                'id_prov' => '35',
                'nama' => 'BATU',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            265 => 
            array (
                'id_kab' => '3601',
                'id_prov' => '36',
                'nama' => 'PANDEGLANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            266 => 
            array (
                'id_kab' => '3602',
                'id_prov' => '36',
                'nama' => 'LEBAK',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            267 => 
            array (
                'id_kab' => '3603',
                'id_prov' => '36',
                'nama' => 'TANGERANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            268 => 
            array (
                'id_kab' => '3604',
                'id_prov' => '36',
                'nama' => 'SERANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            269 => 
            array (
                'id_kab' => '3671',
                'id_prov' => '36',
                'nama' => 'TANGERANG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            270 => 
            array (
                'id_kab' => '3672',
                'id_prov' => '36',
                'nama' => 'CILEGON',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            271 => 
            array (
                'id_kab' => '3673',
                'id_prov' => '36',
                'nama' => 'SERANG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            272 => 
            array (
                'id_kab' => '3674',
                'id_prov' => '36',
                'nama' => 'TANGERANG SELATAN',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            273 => 
            array (
                'id_kab' => '5101',
                'id_prov' => '51',
                'nama' => 'JEMBRANA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            274 => 
            array (
                'id_kab' => '5102',
                'id_prov' => '51',
                'nama' => 'TABANAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            275 => 
            array (
                'id_kab' => '5103',
                'id_prov' => '51',
                'nama' => 'BADUNG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            276 => 
            array (
                'id_kab' => '5104',
                'id_prov' => '51',
                'nama' => 'GIANYAR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            277 => 
            array (
                'id_kab' => '5105',
                'id_prov' => '51',
                'nama' => 'KLUNGKUNG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            278 => 
            array (
                'id_kab' => '5106',
                'id_prov' => '51',
                'nama' => 'BANGLI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            279 => 
            array (
                'id_kab' => '5107',
                'id_prov' => '51',
                'nama' => 'KARANGASEM',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            280 => 
            array (
                'id_kab' => '5108',
                'id_prov' => '51',
                'nama' => 'BULELENG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            281 => 
            array (
                'id_kab' => '5171',
                'id_prov' => '51',
                'nama' => 'DENPASAR',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            282 => 
            array (
                'id_kab' => '5201',
                'id_prov' => '52',
                'nama' => 'LOMBOK BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            283 => 
            array (
                'id_kab' => '5202',
                'id_prov' => '52',
                'nama' => 'LOMBOK TENGAH',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            284 => 
            array (
                'id_kab' => '5203',
                'id_prov' => '52',
                'nama' => 'LOMBOK TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            285 => 
            array (
                'id_kab' => '5204',
                'id_prov' => '52',
                'nama' => 'SUMBAWA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            286 => 
            array (
                'id_kab' => '5205',
                'id_prov' => '52',
                'nama' => 'DOMPU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            287 => 
            array (
                'id_kab' => '5206',
                'id_prov' => '52',
                'nama' => 'BIMA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            288 => 
            array (
                'id_kab' => '5207',
                'id_prov' => '52',
                'nama' => 'SUMBAWA BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            289 => 
            array (
                'id_kab' => '5208',
                'id_prov' => '52',
                'nama' => 'LOMBOK UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            290 => 
            array (
                'id_kab' => '5271',
                'id_prov' => '52',
                'nama' => 'MATARAM',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            291 => 
            array (
                'id_kab' => '5272',
                'id_prov' => '52',
                'nama' => 'BIMA',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            292 => 
            array (
                'id_kab' => '5301',
                'id_prov' => '53',
                'nama' => 'KUPANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            293 => 
            array (
                'id_kab' => '5302',
                'id_prov' => '53',
                'nama' => 'IMOR TENGAH SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            294 => 
            array (
                'id_kab' => '5303',
                'id_prov' => '53',
                'nama' => 'TIMOR TENGAH UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            295 => 
            array (
                'id_kab' => '5304',
                'id_prov' => '53',
                'nama' => 'BELU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            296 => 
            array (
                'id_kab' => '5305',
                'id_prov' => '53',
                'nama' => 'ALOR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            297 => 
            array (
                'id_kab' => '5306',
                'id_prov' => '53',
                'nama' => 'FLORES TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            298 => 
            array (
                'id_kab' => '5307',
                'id_prov' => '53',
                'nama' => 'SIKKA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            299 => 
            array (
                'id_kab' => '5308',
                'id_prov' => '53',
                'nama' => 'ENDE',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            300 => 
            array (
                'id_kab' => '5309',
                'id_prov' => '53',
                'nama' => 'NGADA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            301 => 
            array (
                'id_kab' => '5310',
                'id_prov' => '53',
                'nama' => 'MANGGARAI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            302 => 
            array (
                'id_kab' => '5311',
                'id_prov' => '53',
                'nama' => 'SUMBA TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            303 => 
            array (
                'id_kab' => '5312',
                'id_prov' => '53',
                'nama' => 'SUMBA BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            304 => 
            array (
                'id_kab' => '5313',
                'id_prov' => '53',
                'nama' => 'LEMBATA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            305 => 
            array (
                'id_kab' => '5314',
                'id_prov' => '53',
                'nama' => 'ROTE NDAO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            306 => 
            array (
                'id_kab' => '5315',
                'id_prov' => '53',
                'nama' => 'MANGGARAI BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            307 => 
            array (
                'id_kab' => '5316',
                'id_prov' => '53',
                'nama' => 'NAGEKEO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            308 => 
            array (
                'id_kab' => '5317',
                'id_prov' => '53',
                'nama' => 'SUMBA TENGAH',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            309 => 
            array (
                'id_kab' => '5318',
                'id_prov' => '53',
                'nama' => 'SUMBA BARAT DAYA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            310 => 
            array (
                'id_kab' => '5319',
                'id_prov' => '53',
                'nama' => 'MANGGARAI TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            311 => 
            array (
                'id_kab' => '5320',
                'id_prov' => '53',
                'nama' => 'SABU RAIJUA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            312 => 
            array (
                'id_kab' => '5321',
                'id_prov' => '53',
                'nama' => 'MALAKA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            313 => 
            array (
                'id_kab' => '5371',
                'id_prov' => '53',
                'nama' => 'KUPANG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            314 => 
            array (
                'id_kab' => '6101',
                'id_prov' => '61',
                'nama' => 'SAMBAS',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            315 => 
            array (
                'id_kab' => '6102',
                'id_prov' => '61',
                'nama' => 'MEMPAWAH',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            316 => 
            array (
                'id_kab' => '6103',
                'id_prov' => '61',
                'nama' => 'SANGGAU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            317 => 
            array (
                'id_kab' => '6104',
                'id_prov' => '61',
                'nama' => 'KETAPANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            318 => 
            array (
                'id_kab' => '6105',
                'id_prov' => '61',
                'nama' => 'SINTANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            319 => 
            array (
                'id_kab' => '6106',
                'id_prov' => '61',
                'nama' => 'KAPUAS HULU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            320 => 
            array (
                'id_kab' => '6107',
                'id_prov' => '61',
                'nama' => 'BENGKAYANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            321 => 
            array (
                'id_kab' => '6108',
                'id_prov' => '61',
                'nama' => 'LANDAK',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            322 => 
            array (
                'id_kab' => '6109',
                'id_prov' => '61',
                'nama' => 'SEKADAU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            323 => 
            array (
                'id_kab' => '6110',
                'id_prov' => '61',
                'nama' => 'MELAWI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            324 => 
            array (
                'id_kab' => '6111',
                'id_prov' => '61',
                'nama' => 'KAYONG UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            325 => 
            array (
                'id_kab' => '6112',
                'id_prov' => '61',
                'nama' => 'KUBU RAYA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            326 => 
            array (
                'id_kab' => '6171',
                'id_prov' => '61',
                'nama' => 'PONTIANAK',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            327 => 
            array (
                'id_kab' => '6172',
                'id_prov' => '61',
                'nama' => 'SINGKAWANG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            328 => 
            array (
                'id_kab' => '6201',
                'id_prov' => '62',
                'nama' => 'KOTAWARINGIN BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            329 => 
            array (
                'id_kab' => '6202',
                'id_prov' => '62',
                'nama' => 'KOTAWARINGIN TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            330 => 
            array (
                'id_kab' => '6203',
                'id_prov' => '62',
                'nama' => 'KAPUAS',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            331 => 
            array (
                'id_kab' => '6204',
                'id_prov' => '62',
                'nama' => 'BARITO SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            332 => 
            array (
                'id_kab' => '6205',
                'id_prov' => '62',
                'nama' => 'BARITO UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            333 => 
            array (
                'id_kab' => '6206',
                'id_prov' => '62',
                'nama' => 'KATINGAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            334 => 
            array (
                'id_kab' => '6207',
                'id_prov' => '62',
                'nama' => 'SERUYAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            335 => 
            array (
                'id_kab' => '6208',
                'id_prov' => '62',
                'nama' => 'SUKAMARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            336 => 
            array (
                'id_kab' => '6209',
                'id_prov' => '62',
                'nama' => 'LAMANDAU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            337 => 
            array (
                'id_kab' => '6210',
                'id_prov' => '62',
                'nama' => 'GUNUNG MAS',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            338 => 
            array (
                'id_kab' => '6211',
                'id_prov' => '62',
                'nama' => 'PULANG PISAU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            339 => 
            array (
                'id_kab' => '6212',
                'id_prov' => '62',
                'nama' => 'MURUNG RAYA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            340 => 
            array (
                'id_kab' => '6213',
                'id_prov' => '62',
                'nama' => 'BARITO TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            341 => 
            array (
                'id_kab' => '6271',
                'id_prov' => '62',
                'nama' => 'PALANGKARAYA',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            342 => 
            array (
                'id_kab' => '6301',
                'id_prov' => '63',
                'nama' => 'TANAH LAUT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            343 => 
            array (
                'id_kab' => '6302',
                'id_prov' => '63',
                'nama' => 'KOTABARU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            344 => 
            array (
                'id_kab' => '6303',
                'id_prov' => '63',
                'nama' => 'BANJAR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            345 => 
            array (
                'id_kab' => '6304',
                'id_prov' => '63',
                'nama' => 'BARITO KUALA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            346 => 
            array (
                'id_kab' => '6305',
                'id_prov' => '63',
                'nama' => 'TAPIN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            347 => 
            array (
                'id_kab' => '6306',
                'id_prov' => '63',
                'nama' => 'HULU SUNGAI SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            348 => 
            array (
                'id_kab' => '6307',
                'id_prov' => '63',
                'nama' => 'HULU SUNGAI TENGAH',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            349 => 
            array (
                'id_kab' => '6308',
                'id_prov' => '63',
                'nama' => 'HULU SUNGAI UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            350 => 
            array (
                'id_kab' => '6309',
                'id_prov' => '63',
                'nama' => 'TABALONG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            351 => 
            array (
                'id_kab' => '6310',
                'id_prov' => '63',
                'nama' => 'TANAH BUMBU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            352 => 
            array (
                'id_kab' => '6311',
                'id_prov' => '63',
                'nama' => 'BALANGAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            353 => 
            array (
                'id_kab' => '6371',
                'id_prov' => '63',
                'nama' => 'BANJARMASIN',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            354 => 
            array (
                'id_kab' => '6372',
                'id_prov' => '63',
                'nama' => 'BANJARBARU',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            355 => 
            array (
                'id_kab' => '6401',
                'id_prov' => '64',
                'nama' => 'PASER',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            356 => 
            array (
                'id_kab' => '6402',
                'id_prov' => '64',
                'nama' => 'KUTAI KARTANEGARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            357 => 
            array (
                'id_kab' => '6403',
                'id_prov' => '64',
                'nama' => 'BERAU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            358 => 
            array (
                'id_kab' => '6407',
                'id_prov' => '64',
                'nama' => 'KUTAI BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            359 => 
            array (
                'id_kab' => '6408',
                'id_prov' => '64',
                'nama' => 'KUTAI TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            360 => 
            array (
                'id_kab' => '6409',
                'id_prov' => '64',
                'nama' => 'PENAJAM PASER UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            361 => 
            array (
                'id_kab' => '6411',
                'id_prov' => '64',
                'nama' => 'MAHAKAM ULU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            362 => 
            array (
                'id_kab' => '6471',
                'id_prov' => '64',
                'nama' => 'BALIKPAPAN',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            363 => 
            array (
                'id_kab' => '6472',
                'id_prov' => '64',
                'nama' => 'SAMARINDA',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            364 => 
            array (
                'id_kab' => '6474',
                'id_prov' => '64',
                'nama' => 'BONTANG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            365 => 
            array (
                'id_kab' => '6501',
                'id_prov' => '65',
                'nama' => 'BULUNGAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            366 => 
            array (
                'id_kab' => '6502',
                'id_prov' => '65',
                'nama' => 'MALINAU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            367 => 
            array (
                'id_kab' => '6503',
                'id_prov' => '65',
                'nama' => 'NUNUKAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            368 => 
            array (
                'id_kab' => '6504',
                'id_prov' => '65',
                'nama' => 'TANA TIDUNG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            369 => 
            array (
                'id_kab' => '6571',
                'id_prov' => '65',
                'nama' => 'TARAKAN',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            370 => 
            array (
                'id_kab' => '7101',
                'id_prov' => '71',
                'nama' => 'BOLAANG MONGONDOW',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            371 => 
            array (
                'id_kab' => '7102',
                'id_prov' => '71',
                'nama' => 'MINAHASA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            372 => 
            array (
                'id_kab' => '7103',
                'id_prov' => '71',
                'nama' => 'KEPULAUAN SANGIHE',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            373 => 
            array (
                'id_kab' => '7104',
                'id_prov' => '71',
                'nama' => 'KEPULAUAN TALAUD',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            374 => 
            array (
                'id_kab' => '7105',
                'id_prov' => '71',
                'nama' => 'MINAHASA SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            375 => 
            array (
                'id_kab' => '7106',
                'id_prov' => '71',
                'nama' => 'MINAHASA UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            376 => 
            array (
                'id_kab' => '7107',
                'id_prov' => '71',
                'nama' => 'MINAHASA TENGGARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            377 => 
            array (
                'id_kab' => '7108',
                'id_prov' => '71',
                'nama' => 'BOLAANG MONGONDOW UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            378 => 
            array (
                'id_kab' => '7109',
                'id_prov' => '71',
                'nama' => 'KEP. SIAU TAGULANDANG BIARO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            379 => 
            array (
                'id_kab' => '7110',
                'id_prov' => '71',
                'nama' => 'BOLAANG MONGONDOW TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            380 => 
            array (
                'id_kab' => '7111',
                'id_prov' => '71',
                'nama' => 'BOLAANG MONGONDOW SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            381 => 
            array (
                'id_kab' => '7171',
                'id_prov' => '71',
                'nama' => 'MANADO',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            382 => 
            array (
                'id_kab' => '7172',
                'id_prov' => '71',
                'nama' => 'BITUNG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            383 => 
            array (
                'id_kab' => '7173',
                'id_prov' => '71',
                'nama' => 'TOMOHON',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            384 => 
            array (
                'id_kab' => '7174',
                'id_prov' => '71',
                'nama' => 'KOTAMOBAGU',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            385 => 
            array (
                'id_kab' => '7201',
                'id_prov' => '72',
                'nama' => 'BANGGAI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            386 => 
            array (
                'id_kab' => '7202',
                'id_prov' => '72',
                'nama' => 'POSO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            387 => 
            array (
                'id_kab' => '7203',
                'id_prov' => '72',
                'nama' => 'DONGGALA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            388 => 
            array (
                'id_kab' => '7204',
                'id_prov' => '72',
                'nama' => 'TOLI TOLI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            389 => 
            array (
                'id_kab' => '7205',
                'id_prov' => '72',
                'nama' => 'BUOL',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            390 => 
            array (
                'id_kab' => '7206',
                'id_prov' => '72',
                'nama' => 'MOROWALI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            391 => 
            array (
                'id_kab' => '7207',
                'id_prov' => '72',
                'nama' => 'BANGGAI KEPULAUAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            392 => 
            array (
                'id_kab' => '7208',
                'id_prov' => '72',
                'nama' => 'PARIGI MOUTONG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            393 => 
            array (
                'id_kab' => '7209',
                'id_prov' => '72',
                'nama' => 'TOJO UNA UNA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            394 => 
            array (
                'id_kab' => '7210',
                'id_prov' => '72',
                'nama' => 'SIGI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            395 => 
            array (
                'id_kab' => '7211',
                'id_prov' => '72',
                'nama' => 'BANGGAI LAUT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            396 => 
            array (
                'id_kab' => '7212',
                'id_prov' => '72',
                'nama' => 'MOROWALI UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            397 => 
            array (
                'id_kab' => '7271',
                'id_prov' => '72',
                'nama' => 'PALU',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            398 => 
            array (
                'id_kab' => '7301',
                'id_prov' => '73',
                'nama' => 'KEPULAUAN SELAYAR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            399 => 
            array (
                'id_kab' => '7302',
                'id_prov' => '73',
                'nama' => 'BULUKUMBA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            400 => 
            array (
                'id_kab' => '7303',
                'id_prov' => '73',
                'nama' => 'BANTAENG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            401 => 
            array (
                'id_kab' => '7304',
                'id_prov' => '73',
                'nama' => 'JENEPONTO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            402 => 
            array (
                'id_kab' => '7305',
                'id_prov' => '73',
                'nama' => 'TAKALAR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            403 => 
            array (
                'id_kab' => '7306',
                'id_prov' => '73',
                'nama' => 'GOWA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            404 => 
            array (
                'id_kab' => '7307',
                'id_prov' => '73',
                'nama' => 'SINJAI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            405 => 
            array (
                'id_kab' => '7308',
                'id_prov' => '73',
                'nama' => 'BONE',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            406 => 
            array (
                'id_kab' => '7309',
                'id_prov' => '73',
                'nama' => 'MAROS',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            407 => 
            array (
                'id_kab' => '7310',
                'id_prov' => '73',
                'nama' => 'PANGKAJENE KEPULAUAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            408 => 
            array (
                'id_kab' => '7311',
                'id_prov' => '73',
                'nama' => 'BARRU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            409 => 
            array (
                'id_kab' => '7312',
                'id_prov' => '73',
                'nama' => 'SOPPENG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            410 => 
            array (
                'id_kab' => '7313',
                'id_prov' => '73',
                'nama' => 'WAJO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            411 => 
            array (
                'id_kab' => '7314',
                'id_prov' => '73',
                'nama' => 'SIDENRENG RAPPANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            412 => 
            array (
                'id_kab' => '7315',
                'id_prov' => '73',
                'nama' => 'PINRANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            413 => 
            array (
                'id_kab' => '7316',
                'id_prov' => '73',
                'nama' => 'ENREKANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            414 => 
            array (
                'id_kab' => '7317',
                'id_prov' => '73',
                'nama' => 'LUWU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            415 => 
            array (
                'id_kab' => '7318',
                'id_prov' => '73',
                'nama' => 'TANA TORAJA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            416 => 
            array (
                'id_kab' => '7322',
                'id_prov' => '73',
                'nama' => 'LUWU UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            417 => 
            array (
                'id_kab' => '7324',
                'id_prov' => '73',
                'nama' => 'LUWU TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            418 => 
            array (
                'id_kab' => '7326',
                'id_prov' => '73',
                'nama' => 'TORAJA UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            419 => 
            array (
                'id_kab' => '7371',
                'id_prov' => '73',
                'nama' => 'MAKASSAR',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            420 => 
            array (
                'id_kab' => '7372',
                'id_prov' => '73',
                'nama' => 'PARE PARE',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            421 => 
            array (
                'id_kab' => '7373',
                'id_prov' => '73',
                'nama' => 'PALOPO',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            422 => 
            array (
                'id_kab' => '7401',
                'id_prov' => '74',
                'nama' => 'KOLAKA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            423 => 
            array (
                'id_kab' => '7402',
                'id_prov' => '74',
                'nama' => 'KONAWE',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            424 => 
            array (
                'id_kab' => '7403',
                'id_prov' => '74',
                'nama' => 'MUNA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            425 => 
            array (
                'id_kab' => '7404',
                'id_prov' => '74',
                'nama' => 'BUTON',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            426 => 
            array (
                'id_kab' => '7405',
                'id_prov' => '74',
                'nama' => 'KONAWE SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            427 => 
            array (
                'id_kab' => '7406',
                'id_prov' => '74',
                'nama' => 'BOMBANA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            428 => 
            array (
                'id_kab' => '7407',
                'id_prov' => '74',
                'nama' => 'WAKATOBI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            429 => 
            array (
                'id_kab' => '7408',
                'id_prov' => '74',
                'nama' => 'KOLAKA UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            430 => 
            array (
                'id_kab' => '7409',
                'id_prov' => '74',
                'nama' => 'KONAWE UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            431 => 
            array (
                'id_kab' => '7410',
                'id_prov' => '74',
                'nama' => 'BUTON UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            432 => 
            array (
                'id_kab' => '7411',
                'id_prov' => '74',
                'nama' => 'KOLAKA TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            433 => 
            array (
                'id_kab' => '7412',
                'id_prov' => '74',
                'nama' => 'KONAWE KEPULAUAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            434 => 
            array (
                'id_kab' => '7413',
                'id_prov' => '74',
                'nama' => 'MUNA BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            435 => 
            array (
                'id_kab' => '7414',
                'id_prov' => '74',
                'nama' => 'BUTON TENGAH',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            436 => 
            array (
                'id_kab' => '7415',
                'id_prov' => '74',
                'nama' => 'BUTON SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            437 => 
            array (
                'id_kab' => '7471',
                'id_prov' => '74',
                'nama' => 'KENDARI',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            438 => 
            array (
                'id_kab' => '7472',
                'id_prov' => '74',
                'nama' => 'BAU BAU',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            439 => 
            array (
                'id_kab' => '7501',
                'id_prov' => '75',
                'nama' => 'GORONTALO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            440 => 
            array (
                'id_kab' => '7502',
                'id_prov' => '75',
                'nama' => 'BOALEMO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            441 => 
            array (
                'id_kab' => '7503',
                'id_prov' => '75',
                'nama' => 'BONE BOLANGO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            442 => 
            array (
                'id_kab' => '7504',
                'id_prov' => '75',
                'nama' => 'PAHUWATO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            443 => 
            array (
                'id_kab' => '7505',
                'id_prov' => '75',
                'nama' => 'GORONTALO UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            444 => 
            array (
                'id_kab' => '7571',
                'id_prov' => '75',
                'nama' => 'GORONTALO',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            445 => 
            array (
                'id_kab' => '7601',
                'id_prov' => '76',
                'nama' => 'MAMUJU UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            446 => 
            array (
                'id_kab' => '7602',
                'id_prov' => '76',
                'nama' => 'MAMUJU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            447 => 
            array (
                'id_kab' => '7603',
                'id_prov' => '76',
                'nama' => 'MAMASA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            448 => 
            array (
                'id_kab' => '7604',
                'id_prov' => '76',
                'nama' => 'POLEWALI MANDAR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            449 => 
            array (
                'id_kab' => '7605',
                'id_prov' => '76',
                'nama' => 'MAJENE',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            450 => 
            array (
                'id_kab' => '7606',
                'id_prov' => '76',
                'nama' => 'MAMUJU TENGAH',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            451 => 
            array (
                'id_kab' => '8101',
                'id_prov' => '81',
                'nama' => 'MALUKU TENGAH',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            452 => 
            array (
                'id_kab' => '8102',
                'id_prov' => '81',
                'nama' => 'MALUKU TENGGARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            453 => 
            array (
                'id_kab' => '8103',
                'id_prov' => '81',
                'nama' => 'ALUKU TENGGARA BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            454 => 
            array (
                'id_kab' => '8104',
                'id_prov' => '81',
                'nama' => 'BURU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            455 => 
            array (
                'id_kab' => '8105',
                'id_prov' => '81',
                'nama' => 'SERAM BAGIAN TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            456 => 
            array (
                'id_kab' => '8106',
                'id_prov' => '81',
                'nama' => 'SERAM BAGIAN BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            457 => 
            array (
                'id_kab' => '8107',
                'id_prov' => '81',
                'nama' => 'KEPULAUAN ARU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            458 => 
            array (
                'id_kab' => '8108',
                'id_prov' => '81',
                'nama' => 'MALUKU BARAT DAYA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            459 => 
            array (
                'id_kab' => '8109',
                'id_prov' => '81',
                'nama' => 'BURU SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            460 => 
            array (
                'id_kab' => '8171',
                'id_prov' => '81',
                'nama' => 'AMBON',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            461 => 
            array (
                'id_kab' => '8172',
                'id_prov' => '81',
                'nama' => 'TUAL',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            462 => 
            array (
                'id_kab' => '8201',
                'id_prov' => '82',
                'nama' => 'HALMAHERA BARAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            463 => 
            array (
                'id_kab' => '8202',
                'id_prov' => '82',
                'nama' => 'HALMAHERA TENGAH',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            464 => 
            array (
                'id_kab' => '8203',
                'id_prov' => '82',
                'nama' => 'HALMAHERA UTARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            465 => 
            array (
                'id_kab' => '8204',
                'id_prov' => '82',
                'nama' => 'HALMAHERA SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            466 => 
            array (
                'id_kab' => '8205',
                'id_prov' => '82',
                'nama' => 'KEPULAUAN SULA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            467 => 
            array (
                'id_kab' => '8206',
                'id_prov' => '82',
                'nama' => 'HALMAHERA TIMUR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            468 => 
            array (
                'id_kab' => '8207',
                'id_prov' => '82',
                'nama' => 'PULAU MOROTAI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            469 => 
            array (
                'id_kab' => '8208',
                'id_prov' => '82',
                'nama' => 'PULAU TALIABU',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            470 => 
            array (
                'id_kab' => '8271',
                'id_prov' => '82',
                'nama' => 'TERNATE',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            471 => 
            array (
                'id_kab' => '8272',
                'id_prov' => '82',
                'nama' => 'TIDORE KEPULAUAN',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            472 => 
            array (
                'id_kab' => '9101',
                'id_prov' => '91',
                'nama' => 'MERAUKE',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            473 => 
            array (
                'id_kab' => '9102',
                'id_prov' => '91',
                'nama' => 'JAYAWIJAYA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            474 => 
            array (
                'id_kab' => '9103',
                'id_prov' => '91',
                'nama' => 'JAYAPURA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            475 => 
            array (
                'id_kab' => '9104',
                'id_prov' => '91',
                'nama' => 'NABIRE',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            476 => 
            array (
                'id_kab' => '9105',
                'id_prov' => '91',
                'nama' => 'KEPULAUAN YAPEN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            477 => 
            array (
                'id_kab' => '9106',
                'id_prov' => '91',
                'nama' => 'BIAK NUMFOR',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            478 => 
            array (
                'id_kab' => '9107',
                'id_prov' => '91',
                'nama' => 'PUNCAK JAYA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            479 => 
            array (
                'id_kab' => '9108',
                'id_prov' => '91',
                'nama' => 'PANIAI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            480 => 
            array (
                'id_kab' => '9109',
                'id_prov' => '91',
                'nama' => 'MIMIKA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            481 => 
            array (
                'id_kab' => '9110',
                'id_prov' => '91',
                'nama' => 'SARMI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            482 => 
            array (
                'id_kab' => '9111',
                'id_prov' => '91',
                'nama' => 'KEEROM',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            483 => 
            array (
                'id_kab' => '9112',
                'id_prov' => '91',
                'nama' => 'EGUNUNGAN BINTANG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            484 => 
            array (
                'id_kab' => '9113',
                'id_prov' => '91',
                'nama' => 'YAHUKIMO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            485 => 
            array (
                'id_kab' => '9114',
                'id_prov' => '91',
                'nama' => 'TOLIKARA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            486 => 
            array (
                'id_kab' => '9115',
                'id_prov' => '91',
                'nama' => 'WAROPEN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            487 => 
            array (
                'id_kab' => '9116',
                'id_prov' => '91',
                'nama' => 'BOVEN DIGOEL',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            488 => 
            array (
                'id_kab' => '9117',
                'id_prov' => '91',
                'nama' => 'MAPPI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            489 => 
            array (
                'id_kab' => '9118',
                'id_prov' => '91',
                'nama' => 'ASMAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            490 => 
            array (
                'id_kab' => '9119',
                'id_prov' => '91',
                'nama' => 'SUPIORI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            491 => 
            array (
                'id_kab' => '9120',
                'id_prov' => '91',
                'nama' => 'MAMBERAMO RAYA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            492 => 
            array (
                'id_kab' => '9121',
                'id_prov' => '91',
                'nama' => 'MAMBERAMO TENGAH',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            493 => 
            array (
                'id_kab' => '9122',
                'id_prov' => '91',
                'nama' => 'YALIMO',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            494 => 
            array (
                'id_kab' => '9123',
                'id_prov' => '91',
                'nama' => 'LANNY JAYA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            495 => 
            array (
                'id_kab' => '9124',
                'id_prov' => '91',
                'nama' => 'NDUGA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            496 => 
            array (
                'id_kab' => '9125',
                'id_prov' => '91',
                'nama' => 'PUNCAK',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            497 => 
            array (
                'id_kab' => '9126',
                'id_prov' => '91',
                'nama' => 'DOGIYAI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            498 => 
            array (
                'id_kab' => '9127',
                'id_prov' => '91',
                'nama' => 'INTAN JAYA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            499 => 
            array (
                'id_kab' => '9128',
                'id_prov' => '91',
                'nama' => 'DEIYAI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        \DB::table('kabupaten')->insert(array (
            0 => 
            array (
                'id_kab' => '9171',
                'id_prov' => '91',
                'nama' => 'JAYAPURA',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id_kab' => '9201',
                'id_prov' => '92',
                'nama' => 'SORONG',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id_kab' => '9202',
                'id_prov' => '92',
                'nama' => 'MANOKWARI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id_kab' => '9203',
                'id_prov' => '92',
                'nama' => 'FAK FAK',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id_kab' => '9204',
                'id_prov' => '92',
                'nama' => 'SORONG SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id_kab' => '9205',
                'id_prov' => '92',
                'nama' => 'RAJA AMPAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id_kab' => '9206',
                'id_prov' => '92',
                'nama' => 'TELUK BINTUNI',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id_kab' => '9207',
                'id_prov' => '92',
                'nama' => 'TELUK WONDAMA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id_kab' => '9208',
                'id_prov' => '92',
                'nama' => 'KAIMANA',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id_kab' => '9209',
                'id_prov' => '92',
                'nama' => 'TAMBRAUW',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id_kab' => '9210',
                'id_prov' => '92',
                'nama' => 'MAYBRAT',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id_kab' => '9211',
                'id_prov' => '92',
                'nama' => 'MANOKWARI SELATAN',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id_kab' => '9212',
                'id_prov' => '92',
                'nama' => 'PEGUNUNGAN ARFAK',
                'id_jenis' => '1',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id_kab' => '9271',
                'id_prov' => '92',
                'nama' => 'SORONG',
                'id_jenis' => '2',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id_kab' => '9999',
                'id_prov' => '99',
                'nama' => 'JAKARTA',
                'id_jenis' => '3',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id_kab' => 'id_k',
                'id_prov' => 'id',
                'nama' => 'nama',
                'id_jenis' => '0',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}