<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusClearanceToExitInterviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exit_interview', function (Blueprint $table) {
            //
            $table->tinyInteger('status_clearance')->after('status')->default('0');
        });
        DB::statement("UPDATE exit_interview e SET status_clearance = 1 WHERE (select count(approval_check) from exit_interview_assets where exit_interview_id = e.id and approval_check = 1) = (select count(*) from exit_interview_assets where exit_interview_id = e.id)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exit_interview', function (Blueprint $table) {
            //
            $table->dropColumn('status_clearance');
        });
    }
}
