<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitPict extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_pict', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('visit_list_id')->nullable();
            $table->string('photo')->nullable();
            $table->string('photocaption')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('visit_list_id')->references('id')->on('visit_list')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visit_pict');
    }
}
