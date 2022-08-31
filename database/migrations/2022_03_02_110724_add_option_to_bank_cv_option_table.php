<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOptionToBankCvOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_cv_option', function (Blueprint $table) {
            $table->tinyInteger('is_filter')->nullable()->default(0)->after('name');
            $table->tinyInteger('is_list')->nullable()->default(0)->after('name');
            $table->string('date_name')->nullable()->after('name');
            $table->tinyInteger('is_date')->nullable()->default(0)->after('name');
            $table->tinyInteger('is_dropdown')->nullable()->default(0)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_cv_option', function (Blueprint $table) {
            //
        });
    }
}
