<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecruitmentRequestDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_request_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('recruitment_type_id')->nullable();
            $table->unsignedInteger('recruitment_request_id')->nullable();
            $table->timestamp('posting_date')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));


            $table->unique(['recruitment_type_id','recruitment_request_id'],'rr_detail_unique_ref');
            $table->foreign('recruitment_type_id')->references('id')->on('recruitment_type')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('recruitment_request_id')->references('id')->on('recruitment_request')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruitment_request_detail');
    }
}
