<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayrollCountryIdToUsersTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_temp', function (Blueprint $table) {
            $table->unsignedInteger('payroll_country_id')->nullable();
            $table->tinyInteger('foreigners_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_temp', function (Blueprint $table) {
            //
        });
    }
}
