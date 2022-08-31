<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToTraining extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training', function (Blueprint $table) {
            $table->integer('is_transfer')->default(1)->nullable()->after('training_type_id');
            $table->string('transfer_proof')->nullable()->after('is_transfer');
            $table->integer('is_transfer_by')->nullable()->after('transfer_proof');
            $table->string('disbursement')->nullable()->after('is_transfer_by');
            $table->integer('is_transfer_claim')->default(1)->nullable()->after('disbursement');
            $table->string('transfer_proof_claim')->nullable()->after('is_transfer_claim');
            $table->integer('is_transfer_claim_by')->nullable()->after('transfer_proof_claim');
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
        Schema::table('training', function (Blueprint $table) {
            //
        });
    }
}
