<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJobCategoryIdToRecruitmentRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruitment_request', function (Blueprint $table) {
            //
            $table->unsignedInteger("job_category_id")->nullable()->after('subgrade_id');
            $table->foreign('job_category_id')->references('id')->on('job_categories')->onDelete('SET NULL')->onUpdate('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruitment_request', function (Blueprint $table) {
            //
        });
    }
}
