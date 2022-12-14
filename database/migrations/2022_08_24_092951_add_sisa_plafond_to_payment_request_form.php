<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSisaPlafondToPaymentRequestForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_request_form', function (Blueprint $table) {
            $table->string('sisa_plafond')->nullable()->after('plafond');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_request_form', function (Blueprint $table) {
            //
        });
    }
}
