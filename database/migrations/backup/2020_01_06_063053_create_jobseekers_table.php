<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobseekersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobseekers', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name")->default();
            $table->string("email")->default();
            $table->string("password")->nullable();
            $table->string("confirmation_code")->nullable();
            $table->tinyInteger("status_active")->default('0');
            $table->string("cv")->nullable();
            $table->string("portfolio")->nullable();
            $table->string("address")->nullable();
            $table->string("phone_number")->nullable();
            $table->string("auth_key")->nullable();
            $table->string("photos")->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobseekers');
    }
}
