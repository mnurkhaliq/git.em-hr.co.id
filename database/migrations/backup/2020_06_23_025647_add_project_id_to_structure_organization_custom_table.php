<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectIdToStructureOrganizationCustomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('structure_organization_custom', function (Blueprint $table) {
            //
            $table->integer('project_id')->nullable();
        });
        DB::statement("UPDATE structure_organization_custom so SET project_id = (select project_id from users u where u.id = so.user_created)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('structure_organization_custom', function (Blueprint $table) {
            //
        });
    }
}
