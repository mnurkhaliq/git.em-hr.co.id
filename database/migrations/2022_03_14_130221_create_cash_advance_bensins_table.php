<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashAdvanceBensinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_advance_bensin', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
			$table->integer('cash_advance_id')->nullable();
			$table->date('tanggal')->nullable();
			$table->integer('odo_start')->nullable();
			$table->integer('odo_end')->nullable();
			$table->integer('liter')->nullable();
			$table->integer('cost')->nullable();
			$table->integer('cash_advance_form_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_advance_bensin');
    }
}
