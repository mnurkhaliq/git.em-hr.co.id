<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDataToTraining extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('training', function (Blueprint $table) {
        //     //
        // });

        $results = DB::table('training')->where('pengambilan_uang_muka', 0)->where('is_transfer', 0)->get();
        foreach ($results as $result){
            DB::table('training')
                ->where('id',$result->id)
                ->update([
                    "is_transfer" => 1
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training', function (Blueprint $table) {
            //
        });
    }
}
