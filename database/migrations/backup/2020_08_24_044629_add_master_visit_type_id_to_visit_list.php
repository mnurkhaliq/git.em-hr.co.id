<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMasterVisitTypeIdToVisitList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visit_list', function (Blueprint $table) {
            $table->unsignedInteger('master_visit_type_id')->nullable()->after('user_id');
            $table->foreign('master_visit_type_id')->references('id')->on('master_visit_type')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visit_list', function (Blueprint $table) {
            //
        });
    }
}
