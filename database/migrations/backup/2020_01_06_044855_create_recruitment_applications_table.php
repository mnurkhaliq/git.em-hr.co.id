<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecruitmentApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('recruitment_request_id');
            $table->unsignedInteger('current_phase_id');
            $table->unsignedInteger('application_status');
            $table->text('cover_letter');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));


            $table->foreign('recruitment_request_id')->references('id')->on('recruitment_request')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('current_phase_id')->references('id')->on('recruitment_phases')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('recruitment_applications');
    }
}
