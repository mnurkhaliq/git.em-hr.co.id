<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersBranchVisitTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_branch_visit_temp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id_temp')->nullable();
            $table->unsignedInteger('cabang_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
            $table->unsignedInteger('user_created')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_branch_visit_temp');
    }
}
