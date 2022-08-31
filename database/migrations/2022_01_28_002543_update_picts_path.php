<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePictsPath extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::STATEMENT("UPDATE visit_pict SET photo = REPLACE(photo, '/tunnel/upload/visit/pict/', 'tunnel/upload/visit/pict/')");
        DB::STATEMENT("UPDATE visit_list SET signature = REPLACE(signature, '/tunnel/upload/visit/signature/', 'tunnel/upload/visit/signature/')");
        DB::STATEMENT("UPDATE cuti_karyawan SET attachment = REPLACE(attachment, '/tunnel/upload/leave/attachment/', 'tunnel/upload/leave/attachment/')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
