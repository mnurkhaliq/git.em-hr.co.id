<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BatchIsExitOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (\App\User::whereIn('access_id', ['1','2'])->get() as $user) {
            if ($exit = \App\Models\ExitInterview::where('user_id', $user->id)->where('status', 2)->orderBy('id', 'DESC')->first()) {
                $user->inactive_date = $exit->last_work_date;
                $user->non_active_date = $exit->resign_date;
                $user->is_exit = 1;
                if ($user->organisasi_status && $user->organisasi_status != 'Permanent') {
                    $user->end_date_contract = $exit->resign_date;
                    if (\App\Models\CrmModule::where('project_id', $user->project_id)->where('crm_product_id', 26)->count()) {
                        $career = \App\Models\CareerHistory::where('user_id', $user->id)
                            ->whereDate('effective_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'))
                            ->orderBy('effective_date', 'DESC')
                            ->orderBy('id', 'DESC')
                            ->first();
                        if (!$career) {
                            $career = new \App\Models\CareerHistory();
                            $career->user_id = $user->id;
                            $career->effective_date = $user->join_date;
                        }
                        $career->end_date = $user->end_date_contract;
                        $career->save();
                    }
                } else {
                    $user->status = 2;
                    $user->resign_date = $exit->resign_date;
                }
                $user->save();
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
        //
    }
}
