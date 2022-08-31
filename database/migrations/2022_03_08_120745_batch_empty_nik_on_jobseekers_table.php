<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BatchEmptyNikOnJobseekersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::STATEMENT("UPDATE jobseekers SET input_date = NOW() WHERE input_date IS NULL");
        \DB::STATEMENT("UPDATE jobseekers SET nik = CONCAT(CONCAT(DATE_FORMAT(input_date, '%Y%m%d'), '-'), id) WHERE nik IS NULL");
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
