<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTrainingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('training', function (Blueprint $table) {
            //
            $table->dropColumn('pesawat_tanggal');
            $table->renameColumn('pesawat_perjalanan','tipe_perjalanan');
            $table->renameColumn('pesawat_rute_dari','rute_dari_berangkat');
            $table->renameColumn('pesawat_rute_tujuan','rute_tujuan_berangkat');
            $table->dropColumn('pesawat_rute_dari_tanggal');
            $table->dropColumn('pesawat_rute_ke_tanggal');
            $table->dropColumn('pesawat_rute_dari_waktu');
            $table->dropColumn('pesawat_rute_ke_waktu');
            $table->renameColumn('pesawat_kelas','tipe_kelas_berangkat');
            $table->renameColumn('pesawat_maskapai','nama_transportasi_berangkat');
            $table->string('nama_transportasi_pulang')->after('pesawat_maskapai')->nullable();
            $table->string('tipe_kelas_pulang')->after('pesawat_kelas')->nullable();
            $table->string('transportasi_berangkat')->after('pesawat_perjalanan')->nullable();
            $table->string('transportasi_pulang')->after('transportasi_berangkat')->nullable();
            $table->string('rute_dari_pulang')->after('pesawat_rute_tujuan')->nullable();
            $table->string('rute_tujuan_pulang')->after('rute_dari_pulang')->nullable();
        });
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
