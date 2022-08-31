<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDataNumberToTraining extends Migration
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

        $results = DB::table('training')->join('users', 'users.id', '=', 'training.user_id')->select('training.id', 'training.user_id', 'users.nik', 'training.created_at')->where('number', NULL)->get();
        foreach ($results as $result){
            DB::table('training')
                ->where('id',$result->id)
                ->update([
                    "number" => 'BT-'. date('dmY', strtotime($result->created_at)) .'/'.$result->nik .'-'.checkCountByIdBT($result->user_id, $result->id)
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
