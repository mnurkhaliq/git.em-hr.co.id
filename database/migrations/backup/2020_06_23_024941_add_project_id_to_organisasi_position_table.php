<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectIdToOrganisasiPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organisasi_position', function (Blueprint $table) {
            //
            $table->integer('project_id')->nullable();
        });
        DB::statement("UPDATE organisasi_position op SET project_id = (select project_id from users u where u.id = op.user_created)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organisasi_position', function (Blueprint $table) {
            //
        });
    }
}
