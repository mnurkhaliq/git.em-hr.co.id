<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAbsensiItemOffice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('absensi_item', function (Blueprint $table) {
            //
            $table->renameColumn('lat_office','lat_office_in');
            $table->renameColumn('long_office','long_office_in');
            $table->renameColumn('radius_office','radius_office_in');
            $table->double('lat_office_out')->nullable();
            $table->double('long_office_out')->nullable();
            $table->integer('radius_office_out')->nullable();
        });
        DB::statement("update absensi_item set lat_office_out = lat_office_in, long_office_out = long_office_in, radius_office_out = radius_office_in");
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
