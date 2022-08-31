<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterCutiType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_cuti_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('master_cuti_name')->nullable();
        });
        DB::table('master_cuti_type')->insert(
            [
                [
                    'id' => 1,
                    'master_cuti_name' => 'Annually'
                ],
                [
                    'id' => 2,
                    'master_cuti_name' => 'Anniversary'
                ],
                [
                    'id' => 3,
                    'master_cuti_name' => 'Anniversary Annually'
                ],
                [
                    'id' => 4,
                    'master_cuti_name' => 'Monthly'
                ],
                [
                    'id' => 5,
                    'master_cuti_name' => 'Custom'
                ]
            ]
          );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_cuti_type');
    }
}
