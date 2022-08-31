<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDefaultValueQuotaInUserCutiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_cuti', function (Blueprint $table) {
            $table->integer('kuota')->nullable(false)->default(0)->change();
            $table->integer('cuti_terpakai')->nullable(false)->default(0)->change();
            $table->integer('sisa_cuti')->nullable(false)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_cuti', function (Blueprint $table) {
            //
        });
    }
}
