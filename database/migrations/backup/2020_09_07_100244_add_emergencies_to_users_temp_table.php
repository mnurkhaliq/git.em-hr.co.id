<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmergenciesToUsersTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_temp', function (Blueprint $table) {
            $table->string('emergency_name', 100)->nullable()->after('mobile_2');
            $table->string('emergency_relationship', 100)->nullable()->after('emergency_name');
            $table->string('emergency_contact', 100)->nullable()->after('emergency_relationship');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_temp', function (Blueprint $table) {
            $table->dropColumn('emergency_name');
            $table->dropColumn('emergency_relationship');
            $table->dropColumn('emergency_contact');
        });
    }
}
