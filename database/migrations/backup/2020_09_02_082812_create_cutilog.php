<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCutilog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cutilog', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('usercuti_id');
            $table->string('update_status')->nullable();
            $table->timestamp('date_update')->useCurrent();
            $table->foreign('usercuti_id')->references('id')->on('user_cuti')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cutilog');
    }
}
