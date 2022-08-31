<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDataTransferToTraining extends Migration
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
        $results = DB::table('training')->where('status_actual_bill', 2)->get();
        foreach ($results as $result){
            $total_reimbursement_disetujui =  $result->sub_total_1_disetujui + $result->sub_total_2_disetujui + $result->sub_total_3_disetujui + $result->sub_total_4_disetujui - $result->pengambilan_uang_muka;
            if($total_reimbursement_disetujui==0){
                DB::table('training')
                    ->where('id',$result->id)
                    ->update([
                        "is_transfer_claim" => 1
                ]);
            }
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
