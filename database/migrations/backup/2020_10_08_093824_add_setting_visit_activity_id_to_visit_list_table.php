<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettingVisitActivityIdToVisitListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visit_list', function (Blueprint $table) {
            $table->Integer('setting_visit_activity_id')->nullable()->after('master_category_visit_id')->unsigned();
            $table->foreign('setting_visit_activity_id')->references('id')->on('setting_visit_activity')->onDelete('set null')->onUpdate('cascade');
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
