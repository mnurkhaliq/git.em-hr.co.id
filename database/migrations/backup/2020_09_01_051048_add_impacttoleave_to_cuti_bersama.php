<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImpacttoleaveToCutiBersama extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cuti_bersama', function (Blueprint $table) {
            $table->boolean('impacttoleave')->nullable()->after('sampai_tanggal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cuti_bersama', function (Blueprint $table) {
            //
        });
    }
}
