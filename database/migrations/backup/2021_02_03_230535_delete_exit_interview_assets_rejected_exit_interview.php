<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteExitInterviewAssetsRejectedExitInterview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::delete("DELETE exit_interview_assets FROM exit_interview_assets LEFT JOIN exit_interview ON exit_interview_assets.exit_interview_id = exit_interview.id WHERE exit_interview.status = 3 AND exit_interview_assets.approval_check IS NULL");
        
        DB::table('exit_interview')->where('status', 3)->update(['status_clearance' => 1]);
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
