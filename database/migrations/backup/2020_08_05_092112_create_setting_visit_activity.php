<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingVisitActivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_visit_activity', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('master_category_visit_id');
            $table->string('activityname')->nullable();
            $table->boolean('isactive');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));;
            $table->unsignedInteger('user_created')->nullable();
            $table->foreign('master_category_visit_id')->references('id')->on('master_category_visit')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_visit_activity');
    }
}
