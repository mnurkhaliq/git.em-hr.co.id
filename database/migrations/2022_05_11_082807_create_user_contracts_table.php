<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_contract', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
			$table->string('number')->nullable();
			$table->date('date')->nullable();
            $table->date('contract_sent')->nullable();
            $table->date('return_contract')->nullable();
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
        Schema::dropIfExists('user_contract');
    }
}
