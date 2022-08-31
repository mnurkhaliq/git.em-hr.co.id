<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCabangpicmaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cabangpicmaster', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cabang_id')->nullable();
            $table->string('picname')->nullable();
            $table->boolean('isactive');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
//          $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));;
            $table->unsignedInteger('user_created')->nullable();
            $table->foreign('cabang_id')->references('id')->on('cabang')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cabangpicmaster');
    }
}
