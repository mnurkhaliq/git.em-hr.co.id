<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanAssetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_asset', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('loan_id')->nullable();
            $table->string('asset_name')->nullable();
            $table->text('photo')->nullable();
            $table->timestamps();

            $table->foreign('loan_id')->references('id')->on('loan')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_asset');
    }
}
