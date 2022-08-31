<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankCvOptionValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_cv_option_value', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bank_cv_option_id')->nullable();
            $table->string('value')->nullable();
            $table->timestamps();

            $table->foreign('bank_cv_option_id')->references('id')->on('bank_cv_option')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_cv_option_value');
    }
}
