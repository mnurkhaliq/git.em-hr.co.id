<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BatchNonActiveDateOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE career_history SET status = NULL WHERE status = ''");

        foreach (\App\User::whereIn('access_id', ['1', '2'])->get() as $user) {
            $data = \App\Models\CareerHistory::where('user_id', $user->id)
                ->whereDate('effective_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'))
                ->orderBy('effective_date', 'DESC')
                ->orderBy('id', 'DESC')
                ->first();
            if ($data) {
                $user->cabang_id = $data->cabang_id;
                $user->structure_organization_custom_id = $data->structure_organization_custom_id;
                $user->organisasi_status = $data->status;
                $user->start_date_contract = $data->start_date;
                $user->end_date_contract = $data->end_date;
                $user->save();
            }
        }

        DB::statement("UPDATE users SET organisasi_status = NULL WHERE organisasi_status = ''");
        DB::statement("UPDATE users SET end_date_contract = resign_date WHERE organisasi_status IS NOT NULL AND organisasi_status != 'Permanent' AND end_date_contract IS NULL");
        DB::statement("UPDATE users SET resign_date = NULL, status = NULL WHERE organisasi_status IS NOT NULL AND organisasi_status != 'Permanent'");
        DB::statement("UPDATE users SET non_active_date = IF(resign_date IS NOT NULL, resign_date, IF(end_date_contract IS NOT NULL, end_date_contract, NULL))");
        DB::statement("UPDATE users SET join_date = NULL WHERE non_active_date < join_date");

        foreach (\App\User::whereIn('access_id',['1','2'])->get() as $user) {
            $data = \App\Models\CareerHistory::where('user_id', $user->id)
                ->whereDate('effective_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'))
                ->orderBy('effective_date', 'DESC')
                ->orderBy('id', 'DESC')
                ->first();
            if ($data && !$data->end_date && $user->non_active_date && $user->organisasi_status && $user->organisasi_status != 'Permanent') {
                $data->end_date = $user->non_active_date;
                $data->save();
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
