<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMasterVisitCategoryIdToVisitList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visit_list', function (Blueprint $table) {
            $table->unsignedInteger('master_category_visit_id')->nullable()->after('user_id');
            $table->foreign('master_category_visit_id')->references('id')->on('master_category_visit')->onDelete('cascade')->onUpdate('cascade');
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
