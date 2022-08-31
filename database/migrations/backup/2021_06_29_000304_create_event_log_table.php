<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('database')->nullable();
            $table->string('type')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('transaction_id')->nullable();
            $table->string('description')->nullable();
            $table->timestamp('date')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_log');
    }
}
