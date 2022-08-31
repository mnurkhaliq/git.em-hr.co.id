<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldPayrollToCashAdvance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_advance', function (Blueprint $table) {
            $table->string('number')->nullable()->after('user_id');
            $table->integer('status_payroll')->nullable()->after('disbursement_claim');
            $table->integer('payroll_approval_user_id')->nullable()->after('status_payroll');
			$table->integer('payroll_history_id')->nullable()->after('payroll_approval_user_id');
        });

        $results = DB::table('cash_advance')->join('users', 'users.id', '=', 'cash_advance.user_id')->select('cash_advance.id', 'cash_advance.user_id', 'users.nik', 'cash_advance.created_at')->where('number', NULL)->get();
        foreach ($results as $result){
            DB::table('cash_advance')
                ->where('id',$result->id)
                ->update([
                    "number" => 'CA-'. date('dmY', strtotime($result->created_at)) .'/'.$result->nik .'-'.checkCountByIdCA($result->user_id, $result->id)
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
        Schema::table('cash_advance', function (Blueprint $table) {
            //
        });
    }
}
