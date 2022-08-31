<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStatusInRecruitmentRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruitment_request', function (Blueprint $table) {
            DB::table('recruitment_request')->where('approval_hr', 1)->whereNull('approval_user')->update(['status' => 1]);
            DB::table('recruitment_request')->where('approval_hr', 1)->where('approval_user', 1)->update(['status' => 2]);
            DB::table('recruitment_request')->where('approval_hr', 0)->orWhere('approval_user', 0)->update(['status' => 3]);
            DB::table('recruitment_request')->whereNull('approval_hr')->whereNull('approval_user')->update(['status' => 4]);
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
