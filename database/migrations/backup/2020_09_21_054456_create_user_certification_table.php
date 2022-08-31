<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCertificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_certification', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('index');
            $table->string('name', 200)->nullable();
            $table->date('date')->nullable();
            $table->string('organizer', 200)->nullable();
            $table->string('certificate_number', 200)->nullable();
            $table->string('score', 200)->nullable();
            $table->text('description', 65535)->nullable();
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
        Schema::dropIfExists('user_certification');
    }
}
