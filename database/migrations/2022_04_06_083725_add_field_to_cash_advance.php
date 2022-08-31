<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToCashAdvance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_advance', function (Blueprint $table) {
            $table->integer('is_transfer')->default(1)->nullable()->after('date_claim');
            $table->string('transfer_proof')->nullable()->after('is_transfer');
            $table->integer('is_transfer_by')->nullable()->after('transfer_proof');
            $table->integer('is_transfer_claim')->default(1)->nullable()->after('is_transfer_by');
            $table->string('transfer_proof_claim')->nullable()->after('is_transfer_claim');
            $table->integer('is_transfer_claim_by')->nullable()->after('transfer_proof_claim');
        });

        // \DB::statement('UPDATE cash_advance SET is_transfer =1 WHERE status_claim >= 1 AND payment_method="Bank Transfer"');
        // \DB::statement('UPDATE cash_advance SET is_transfer =0 WHERE status_claim is NULL AND payment_method="Bank Transfer"');
        // \DB::statement('UPDATE cash_advance SET is_transfer_claim =0 WHERE payment_method="Bank Transfer"');
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
