<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCatatanUserToExitInterviewAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exit_interview_assets', function (Blueprint $table) {
            //
            $table->text('catatan_user')->after('catatan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exit_interview_assets', function (Blueprint $table) {
            //
            $table->dropColumn('catatan_user');
        });
    }
}
