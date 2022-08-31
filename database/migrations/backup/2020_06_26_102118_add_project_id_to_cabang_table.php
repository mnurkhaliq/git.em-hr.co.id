<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectIdToCabangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cabang', function (Blueprint $table) {
            //
            $table->integer('project_id')->nullable();
        });
        DB::statement("UPDATE cabang c SET project_id = (select project_id from users u where u.id = c.user_created)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cabang', function (Blueprint $table) {
            //
        });
    }
}
