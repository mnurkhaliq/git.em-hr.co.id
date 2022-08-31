<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpiredAndLastPostDateToRecruitmentRequestDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruitment_request_detail', function (Blueprint $table) {
            $table->date('last_posted_date')->nullable()->after('posting_date');
            $table->date('expired_date')->nullable()->after('posting_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruitment_request_detail', function (Blueprint $table) {
            //
        });
    }
}
