<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExitInterviewReasonTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exit_interview_reason')->delete();
        
        \DB::table('exit_interview_reason')->insert(array (
            0 => 
            array (
                'id' => '1',
                'is_parent' => NULL,
                'parent_label' => NULL,
                'parent_id' => NULL,
            'label' => 'Better job dan benefit (Mendapatkan Pekerjaan dan Benefit lebih baik)',
                'type' => NULL,
                'created_at' => '2018-05-20 00:50:50',
                'updated_at' => '2018-05-20 00:50:50',
            ),
            1 => 
            array (
                'id' => '2',
                'is_parent' => NULL,
                'parent_label' => NULL,
                'parent_id' => NULL,
            'label' => 'Familiy reason (Alasan keluarga)',
                'type' => NULL,
                'created_at' => '2018-05-20 01:54:00',
                'updated_at' => '2018-05-20 01:54:00',
            ),
            2 => 
            array (
                'id' => '3',
                'is_parent' => NULL,
                'parent_label' => NULL,
                'parent_id' => NULL,
            'label' => 'Study (Alasan Pendidikan)',
                'type' => NULL,
                'created_at' => '2018-05-20 01:54:24',
                'updated_at' => '2018-05-20 01:54:24',
            ),
            3 => 
            array (
                'id' => '4',
                'is_parent' => NULL,
                'parent_label' => NULL,
                'parent_id' => NULL,
            'label' => 'Start business (Memulai bisnis/Usaha)',
                'type' => NULL,
                'created_at' => '2018-05-20 01:54:45',
                'updated_at' => '2018-05-20 01:54:45',
            ),
            4 => 
            array (
                'id' => '5',
                'is_parent' => NULL,
                'parent_label' => NULL,
                'parent_id' => NULL,
            'label' => 'Sickness (Sakit berkelanjutan)',
                'type' => NULL,
                'created_at' => '2018-05-20 01:55:52',
                'updated_at' => '2018-05-20 01:55:52',
            ),
            5 => 
            array (
                'id' => '6',
                'is_parent' => NULL,
                'parent_label' => NULL,
                'parent_id' => NULL,
            'label' => 'Decease (Meninggal Dunia)',
                'type' => NULL,
                'created_at' => '2018-05-20 01:56:13',
                'updated_at' => '2018-05-20 01:56:13',
            ),
            6 => 
            array (
                'id' => '7',
                'is_parent' => NULL,
                'parent_label' => NULL,
                'parent_id' => NULL,
                'label' => 'Rasionalisasi',
                'type' => NULL,
                'created_at' => '2018-05-20 01:56:27',
                'updated_at' => '2018-05-20 01:56:27',
            ),
            7 => 
            array (
                'id' => '8',
                'is_parent' => NULL,
                'parent_label' => NULL,
                'parent_id' => NULL,
            'label' => 'End of contract (Habis Kontrak)',
                'type' => NULL,
                'created_at' => '2018-05-20 01:56:43',
                'updated_at' => '2018-05-20 01:56:43',
            ),
            8 => 
            array (
                'id' => '9',
                'is_parent' => NULL,
                'parent_label' => NULL,
                'parent_id' => NULL,
            'label' => 'Didn\'t pass probation (Tidak lulus probation)',
                'type' => NULL,
                'created_at' => '2018-05-20 01:57:09',
                'updated_at' => '2018-05-20 01:57:09',
            ),
            9 => 
            array (
                'id' => '10',
                'is_parent' => NULL,
                'parent_label' => NULL,
                'parent_id' => NULL,
            'label' => 'Pension (Pensiun)',
                'type' => NULL,
                'created_at' => '2018-05-20 01:57:28',
                'updated_at' => '2018-05-20 01:57:28',
            ),
            10 => 
            array (
                'id' => '11',
                'is_parent' => NULL,
                'parent_label' => NULL,
                'parent_id' => NULL,
            'label' => 'Discplinary dismissal (Tindakan Indisipliner)',
                'type' => NULL,
                'created_at' => '2018-05-20 02:00:07',
                'updated_at' => '2018-05-20 02:00:07',
            ),
            11 => 
            array (
                'id' => '12',
                'is_parent' => NULL,
                'parent_label' => NULL,
                'parent_id' => NULL,
            'label' => 'Others(Other Reason): ',
                'type' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}