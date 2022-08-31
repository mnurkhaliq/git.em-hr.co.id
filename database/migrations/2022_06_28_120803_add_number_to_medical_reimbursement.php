<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumberToMedicalReimbursement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_reimbursement', function (Blueprint $table) {
            $table->string('number')->nullable()->after('user_id');
        });

        $results = DB::table('medical_reimbursement')->join('users', 'users.id', '=', 'medical_reimbursement.user_id')->select('medical_reimbursement.id', 'medical_reimbursement.user_id', 'users.nik', 'medical_reimbursement.created_at')->where('number', NULL)->get();
        foreach ($results as $result){
            DB::table('medical_reimbursement')
                ->where('id',$result->id)
                ->update([
                    "number" => 'MR-'. date('dmY', strtotime($result->created_at)) .'/'.$result->nik .'-'.checkCountByIdMR($result->user_id, $result->id)
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
        Schema::table('medical_reimbursement', function (Blueprint $table) {
            //
        });
    }
}
