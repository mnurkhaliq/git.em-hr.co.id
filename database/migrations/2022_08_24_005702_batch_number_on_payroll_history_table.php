<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BatchNumberOnPayrollHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $results = DB::table('payroll_history')->join('users', 'users.id', '=', 'payroll_history.user_id')->select('payroll_history.id', 'payroll_history.user_id', 'users.nik', 'payroll_history.created_at')->where('number', null)->get();
        foreach ($results as $result) {
            DB::table('payroll_history')
                ->where('id', $result->id)
                ->update([
                    "number" => 'P-' . date('mY', strtotime($result->created_at)) . '/' . $result->nik . '-' . checkCountByIdP($result->user_id, $result->id),
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
        //
    }
}
