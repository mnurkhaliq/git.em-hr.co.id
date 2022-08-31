<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToPaymentRequestType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_request_type', function (Blueprint $table) {
            $table->text('plafond')->nullable();
            $table->integer('is_lock')->nullable();
        });

        \DB::table('payment_request_type')->insert([
            ['id'=> 1, 'type' => 'Parking', 'is_lock' => 1],
            ['id'=> 2, 'type' => 'Gasoline', 'is_lock' => 1],
            ['id'=> 3, 'type' => 'Toll', 'is_lock' => 1],
            ['id'=> 4, 'type' => 'Transportation', 'is_lock' => 1],
            ['id'=> 5, 'type' => 'Transport(Overtime)', 'is_lock' => 1],
            ['id'=> 6, 'type' => 'Others', 'is_lock' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_request_type', function (Blueprint $table) {
            //
        });
    }
}
