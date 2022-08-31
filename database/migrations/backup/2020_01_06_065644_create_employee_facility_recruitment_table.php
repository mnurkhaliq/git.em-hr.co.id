<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeFacilityRecruitmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_facility_recruitment', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('external_application_id');
            $table->integer('asset_type_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->foreign('external_application_id')->references('id')->on('external_applications')->onDelete('cascade')->onUpdate('cascade');
//            $table->foreign('asset_type_id')->references('id')->on('asset_type')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_facility_recruitment');
    }
}
