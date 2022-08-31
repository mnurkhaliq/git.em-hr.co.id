<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectIdToMedicalTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_type', function (Blueprint $table) {
            //
            $table->integer('project_id')->nullable();
        });
        DB::statement("UPDATE medical_type m SET project_id = (select project_id from users u where u.id = m.user_created)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_type', function (Blueprint $table) {
            //
        });
    }
}
