<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRecruitmentRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("delete rr from recruitment_request rr left join structure_organization_custom so on rr.structure_organization_custom_id = so.id where so.id is null");
        Schema::table('recruitment_request', function (Blueprint $table) {
            $table->foreign('structure_organization_custom_id','structure_rec_req_ref')->references('id')->on('structure_organization_custom')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
