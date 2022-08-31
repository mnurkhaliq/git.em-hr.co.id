<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToAssetTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_tracking', function (Blueprint $table) {
            $table->integer('is_return')->nullable()->after('remark');
            $table->string('note_return')->nullable()->after('is_return');
            $table->string('asset_condition_return')->nullable()->after('note_return');
            $table->dateTime('date_return')->nullable()->after('asset_condition_return');
            $table->integer('status_return')->nullable()->after('date_return');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_tracking', function (Blueprint $table) {
            //
        });
    }
}
