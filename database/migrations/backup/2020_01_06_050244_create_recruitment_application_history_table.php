<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecruitmentApplicationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_application_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('recruitment_application_id');
            $table->unsignedInteger('recruitment_phase_id');
            $table->unsignedInteger('application_status');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));


            $table->foreign('recruitment_application_id','rec_app_ref')->references('id')->on('recruitment_applications')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('recruitment_phase_id')->references('id')->on('recruitment_phases')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('application_status')->references('id')->on('recruitment_application_status')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruitment_application_history');
    }
}
