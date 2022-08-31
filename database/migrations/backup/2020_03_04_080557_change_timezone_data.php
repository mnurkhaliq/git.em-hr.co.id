<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTimezoneData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("UPDATE cabang SET timezone = 'WIB' WHERE timezone = 'WIB (GMT +7)'");
        DB::statement("UPDATE cabang SET timezone = 'WITA' WHERE timezone = 'WITA (GMT +8)'");
        DB::statement("UPDATE cabang SET timezone = 'WIT' WHERE timezone = 'WIT (GMT +9)'");
        DB::statement("UPDATE absensi_item SET timezone = 'WIB' WHERE timezone = 'WIB (GMT +7)'");
        DB::statement("UPDATE absensi_item SET timezone = 'WITA' WHERE timezone = 'WITA (GMT +8)'");
        DB::statement("UPDATE absensi_item SET timezone = 'WIT' WHERE timezone = 'WIT (GMT +9)'");
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
