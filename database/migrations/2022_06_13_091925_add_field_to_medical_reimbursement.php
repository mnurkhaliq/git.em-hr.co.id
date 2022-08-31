<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToMedicalReimbursement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_reimbursement', function (Blueprint $table) {
            $table->integer('is_transfer')->default(1)->nullable()->after('status');
            $table->string('transfer_proof')->nullable()->after('is_transfer');
            $table->integer('is_transfer_by')->nullable()->after('transfer_proof');
            $table->string('disbursement')->nullable()->after('is_transfer_by');
        });

        $results = DB::table('medical_reimbursement')->where('status', 1)->get();
        foreach ($results as $result){
            DB::table('medical_reimbursement')
                ->where('id',$result->id)
                ->update([
                    "is_transfer" => 0
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
