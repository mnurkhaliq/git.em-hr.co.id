<?php

use App\Models\CareerHistory;
use App\Models\SubGrade;
use App\Models\Grade;
use App\User;
use Carbon\Carbon;

function synchronize_career($user_id)
{
    $user = User::find($user_id);
    $data = CareerHistory::where('user_id', $user_id)
        ->whereDate('effective_date', '<=', Carbon::now()->format('Y-m-d'))
        ->orderBy('effective_date','DESC')
        ->orderBy('id', 'DESC')
        ->first();
    if($user && $data){
        $user->cabang_id                        = $data->cabang_id;
        $user->structure_organization_custom_id = $data->structure_organization_custom_id;
        $user->organisasi_status                = $data->status;
        $user->start_date_contract              = $data->start_date;
        $user->end_date_contract                = $data->end_date;
        if (!$user->organisasi_status || $user->organisasi_status == 'Permanent') {
            $user->status_contract = null;
        } else {
            if ($user->end_date_contract) {
                $user->non_active_date = $user->end_date_contract;
            }
            $user->status       = null;
            $user->resign_date  = null;
        }
        if (!$user->resign_date && !$user->end_date_contract) {
            $user->non_active_date = null;
        }
        $user->save();
    }
    cleaning_future_career($user);
}

function call_sub_grade($grade_id){
    $data = SubGrade::where('grade_id', $grade_id)->get();

    return $data;
}


function get_grades()
{
	$auth = \Auth::user();
	if($auth)
	{
        return \App\Models\Grade::orderBy('id', 'ASC')->get();
	}
}

function cleaning_future_career($user) {
    if ($user && ($user->is_exit || ($user->non_active_date && $user->non_active_date <= Carbon::now()))) {
        CareerHistory::where('user_id', $user->id)
        ->whereDate('effective_date', '>', Carbon::now()->format('Y-m-d'))
        ->delete();
    }
}

function synchronize_all_career(){
    foreach (User::whereIn('access_id', ['1','2'])->get() as $user) {
        $data = CareerHistory::where('user_id', $user->id)
            ->whereDate('effective_date', '<=', Carbon::now()->format('Y-m-d'))
            ->orderBy('effective_date', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();
        if ($data) {
            $user->cabang_id                        = $data->cabang_id;
            $user->structure_organization_custom_id = $data->structure_organization_custom_id;
            $user->organisasi_status                = $data->status;
            $user->start_date_contract              = $data->start_date;
            $user->end_date_contract                = $data->end_date;
            if (!$user->organisasi_status || $user->organisasi_status == 'Permanent') {
                $user->status_contract = null;
            } else {
                if ($user->end_date_contract) {
                    $user->non_active_date = $user->end_date_contract;
                }
                $user->status       = null;
                $user->resign_date  = null;
            }
            if (!$user->resign_date && !$user->end_date_contract) {
                $user->non_active_date = null;
            }
            $user->save();
        }
        cleaning_future_career($user);
    }
}