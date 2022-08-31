<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldDisbursementToCashAdvance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_advance', function (Blueprint $table) {
            $table->string('disbursement')->nullable()->after('is_transfer_by');
            $table->string('disbursement_claim')->nullable()->after('is_transfer_claim_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_advance', function (Blueprint $table) {
            //
        });
    }
}
