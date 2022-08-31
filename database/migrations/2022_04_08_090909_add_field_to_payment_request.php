<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToPaymentRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_request', function (Blueprint $table) {
            $table->integer('is_transfer')->default(1)->nullable()->after('status');
            $table->string('transfer_proof')->nullable()->after('is_transfer');
            $table->integer('is_transfer_by')->nullable()->after('transfer_proof');
        });
        // \DB::statement('UPDATE payment_request SET is_transfer =0 WHERE status is NULL OR status=1 AND payment_method="Bank Transfer"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_request', function (Blueprint $table) {
            //
        });
    }
}
