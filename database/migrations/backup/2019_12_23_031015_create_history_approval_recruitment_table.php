<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryApprovalRecruitmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_approval_recruitment', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('recruitment_request_id');
            $table->integer('structure_organization_custom_id');
            $table->integer('setting_approval_level_id');
            $table->integer('approval_id')->nullable();
            $table->tinyInteger('is_approved')->nullable();
            $table->dateTime('date_approved')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_approval_recruitment');
    }
}
