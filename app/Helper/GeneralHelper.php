<?php

/**
 * Get Setting
 * @return objects
 */
function get_shift_attendance($branch_id)
{
	if(\Auth::user()->project_id != Null){
		return \App\Models\Shift::where('branch_id', $branch_id)->get();
	}else{
		return \App\Models\Shift::all();
	}
}

/**
 * Format IDR
 * @param  snumber
 * @return string
 */
function format_idr($number, $delimeter='.')
{
	return number_format($number,0,0,$delimeter);
}


function get_plafond_type()
{
	$type = ['Standard', 'Middle', 'High'];
	
	return $type;
}

function overtime_absensi($date,$user_id)
{
	//tanggal dan user_id
	$data = \App\Models\AbsensiItem::select('clock_in', 'clock_out')->where('date',$date)->where('user_id',$user_id)->get();

    $data->clock_in = count($data) ? $data[0]->clock_in : null;
    $data->clock_out = count($data) ? $data[count($data) - 1]->clock_out : null;

	return $data;
	
}

function get_level_header()
{
	$data = \App\Models\HistoryApprovalLeave::orderBy('setting_approval_level_id', 'DESC')->first();

	if($data)
		return $data->setting_approval_level_id;
	else
		return 0;
}

function get_payment_header()
{
	$data = \App\Models\HistoryApprovalPaymentRequest::orderBy('setting_approval_level_id', 'DESC')->first();

	if($data)
		return $data->setting_approval_level_id;
	else
		return 0;
}

function get_timesheet_header()
{
	$data = \App\Models\HistoryApprovalTimesheet::orderBy('setting_approval_level_id', 'DESC')->first();

	if($data)
		return $data->setting_approval_level_id;
	else
		return 0;
}

function get_overtime_header()
{
	$data = \App\Models\HistoryApprovalOvertime::orderBy('setting_approval_level_id', 'DESC')->first();

	if($data)
		return $data->setting_approval_level_id;
	else
		return 0;
}

function get_training_header()
{
	$data = \App\Models\HistoryApprovalTraining::orderBy('setting_approval_level_id', 'DESC')->first();

	if($data)
		return $data->setting_approval_level_id;
	else
		return 0;
}
function get_medical_header()
{
	$data = \App\Models\HistoryApprovalMedical::orderBy('setting_approval_level_id', 'DESC')->first();

	if($data)
		return $data->setting_approval_level_id;
	else
		return 0;
}
function get_loan_header()
{
	$data = \App\Models\HistoryApprovalLoan::orderBy('setting_approval_level_id', 'DESC')->first();

	if($data)
		return $data->setting_approval_level_id;
	else
		return 0;
}
function get_exit_header()
{
	$data = \App\Models\HistoryApprovalExit::orderBy('exit_interview_id', 'DESC')->first();

	if($data)
		return $data->setting_approval_level_id;
	else
		return 0;
}

/**
 * cek level up
 */
function getStructureName()
{
	$user = \Auth::user();
    if($user->project_id != NULL)
    {
    	$all = \App\Models\StructureOrganizationCustom::where('structure_organization_custom.project_id', $user->project_id)->select('structure_organization_custom.*')
            ->leftJoin('organisasi_position as op', 'structure_organization_custom.organisasi_position_id','=','op.id')
            ->leftJoin('organisasi_division as od', 'structure_organization_custom.organisasi_division_id','=','od.id')
            ->leftJoin('organisasi_title as ot', 'structure_organization_custom.organisasi_title_id','=','ot.id')
            ->orderBy(\DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), ''))"),'ASC')
            ->get();
    }else {
    	$all = \App\Models\StructureOrganizationCustom::leftJoin('organisasi_position as op', 'structure_organization_custom.organisasi_position_id','=','op.id')
            ->leftJoin('organisasi_division as od', 'structure_organization_custom.organisasi_division_id','=','od.id')
            ->leftJoin('organisasi_title as ot', 'structure_organization_custom.organisasi_title_id','=','ot.id')
            ->orderBy("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), ''))",'ASC')
            ->get();
    }
    $data = [];
    foreach ($all as $key => $item) 
    {
        $data[$key]['id']       = $item->id;
        $data[$key]['name']     = isset($item->position) ? $item->position->name:'';
        $data[$key]['name']     = isset($item->division) ? $data[$key]['name'] .' - '. $item->division->name: $data[$key]['name'];
        $data[$key]['name']     = isset($item->title) ? $data[$key]['name'] .' - '. $item->title->name: $data[$key]['name'];
    }
    return $data;
}
function cek_level_leave_up($id, $display = false)
{
	$cuti = \App\Models\HistoryApprovalLeave::join('cuti_karyawan','cuti_karyawan.id','=','history_approval_leave.cuti_karyawan_id')->where('cuti_karyawan.id', $id);

	$cek1 = clone $cuti;
	$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
	
	if(!$cek1) return false;

	if($cek1->setting_approval_level_id >=2)
	{
		$cek2 = clone $cuti;
		$cek3 = clone $cuti;
		$cek2 = $cek2->where('history_approval_leave.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
        $cek3 = $display ? $cek3->where('history_approval_leave.setting_approval_level_id',  $cek1->setting_approval_level_id )->whereNotNull('is_approved')->first() : false;
		if($cek2 && !$cek3)
		{
			return false;
		} 
	}

	return true;
}

/**
 * Delete Item Asset
 * @return void
 */
function delete_asset_item($id)
{
	$asset = \App\Models\Asset::where('id', $id)->first();
	if($asset)
	{
		$asset->delete();
	}

	$history = \App\Models\AssetTracking::where('asset_id', $id)->first();
	if($history)
	{
		$history->delete();
	}

	return;
}


/**
 * Month name
 * @return array
 */
function month_name()
{
	$months = [];
	for ($i = 1; $i <= 12; $i++) {
	    $timestamp = mktime(0, 0, 0, $i, 1);
	    $months[date('n', $timestamp)] = date('F', $timestamp);
	}

	return $months;
}

/**
 * Replace IDR
 * @return string
 */
function cek_leave_id_approval($id)
{
    return cek_leave_approval()->where('id', $id)->first();
}

function cek_leave_approval()
{
	$cuti = \App\Models\HistoryApprovalLeave::select('history_approval_leave.*', 'cuti_karyawan.*')
											->join('cuti_karyawan','cuti_karyawan.id','=','history_approval_leave.cuti_karyawan_id')
											->orderBy('history_approval_leave.id', 'DESC');


	if(\Auth::user()->project_id != "")
	{
		$cuti = $cuti->join('users', 'users.id', '=', 'cuti_karyawan.user_id')->where('users.project_id', \Auth::user()->project_id);
	}
	
	$cek1 = clone $cuti;
	$cek1 = $cek1->where('history_approval_leave.structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
	
	if(!$cek1) return [];

	if($cek1->setting_approval_level_id >=2)
	{
		$cek2 = clone $cuti;
		$cek2 = $cek2->where('history_approval_leave.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
		if($cek2)
		{
			//return [];
		} 
	}
	return $cuti->where('history_approval_leave.structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->get()->unique('cuti_karyawan_id');
}

function count_leave_approval()
{
	$data = cek_leave_approval();
	$params['approved'] 	= 0;
	$params['waiting'] 		= 0;
	$params['reject'] 		= 0;
	$params['all']			= 0;	
	if(!$data) return $params;
	foreach($data as $item)	
	{
		if($item->is_approved == NULL)
		{
			if($item->status == 3 || $item->status == 5 || $item->status == 8) continue;
			
            if(cek_level_leave_up($item->cutiKaryawan->id))
            {
				$params['data'][$params['waiting']] = $item;
                $params['waiting'] = $params['waiting'] + 1; 
            }
        }
        if($item->is_approved == 0)
        {
			$params['reject'] = $params['reject'] + 1;
        }
        if($item->is_approved == 1)
        {
			$params['approved'] = $params['approved'] + 1;
        }
	}
	$params['all'] = $params['approved'] + $params['waiting'] + $params['reject'];
	return $params;
}

//payment request

function cek_payment_request_id_approval_or_no($id)
{
    return  \App\Models\HistoryApprovalPaymentRequest::join('payment_request','payment_request.id','=','history_approval_payment_request.payment_request_id')
		->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->orderBy('payment_request_id', 'DESC')->where('payment_request.id', $id)->first();
}

function cek_payment_request_id_approval($id)
{
    return cek_payment_request_approval()->where('id', $id)->first();
}

function cek_payment_request_approval()
{
	$paymentRequest = \App\Models\HistoryApprovalPaymentRequest::join('payment_request','payment_request.id','=','history_approval_payment_request.payment_request_id')->orderBy('payment_request_id', 'DESC');
	$cek1 = clone $paymentRequest;
	$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
	$cek_transfer_approve = \App\Models\TransferSetting::where('user_id', auth()->user()->id)->first();

	if(!$cek1) return [];
	if($cek1->setting_approval_level_id >=2)
	{
		$cek2 = clone $paymentRequest;
		$cek2 = $cek2->where('history_approval_payment_request.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
		if($cek2)
		{
			//return [];
		}
	}

	return $paymentRequest->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->get();
}


function count_payment_request_approval()
{
	$data = cek_payment_request_approval();
	$params['approved'] 	= 0;
	$params['waiting'] 		= 0;
	$params['reject'] 		= 0;
	$params['all']			= 0;

	if(!$data) return $params;
	foreach($data as $item)	
	{
		if($item->is_approved == NULL)
		{
			if($item->status == 3) continue;
			
            if(cek_level_payment_request_up($item->paymentRequest->id))
            {
				$params['data'][$params['waiting']] = $item;
                $params['waiting'] = $params['waiting'] + 1; 
            }
        }
        if($item->is_approved == 0)
        {
			$params['reject'] = $params['reject'] + 1;
        }
        if($item->is_approved == 1)
        {
			$params['approved'] = $params['approved'] + 1;
        }
	}

	if(cek_transfer_setting_user()){
		$params['waiting'] = $params['waiting'] + getPaymentRequestCount();
	}

	$params['all'] = $params['approved'] + $params['waiting'] + $params['reject'];
	return $params;
}

function cek_level_payment_request_up($id)
{
	$paymentRequest = \App\Models\HistoryApprovalPaymentRequest::join('payment_request','payment_request.id','=','history_approval_payment_request.payment_request_id')->where('payment_request.id', $id);

	$cek1 = clone $paymentRequest;
	$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
	if(!$cek1) return false;
	if($cek1->setting_approval_level_id >=2)
	{
		$cek2 = clone $paymentRequest;
		$cek2 = $cek2->where('history_approval_payment_request.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
		if($cek2)
		{
			return false;
		} 
	}

	return true;
}


function cek_recruitment_approval()
{
    $recrutimentRequest = \App\Models\HistoryApprovalRecruitment::join('recruitment_request','recruitment_request.id','=','history_approval_recruitment.recruitment_request_id')->orderBy('recruitment_request_id', 'DESC');
    $cek1 = clone $recrutimentRequest;
    $cek1 = $cek1->where('history_approval_recruitment.structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();

    if(!$cek1) return [];
    if($cek1->setting_approval_level_id >=2)
    {
        $cek2 = clone $recrutimentRequest;
        $cek2 = $cek2->where('history_approval_recruitment.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
        if($cek2)
        {
            //return [];
        }
    }
    return $recrutimentRequest->where('history_approval_recruitment.structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->get();
}
function count_recruitment_approval()
{
    $data = cek_recruitment_approval();
    $params['approved'] 	= 0;
    $params['waiting'] 		= 0;
    $params['reject'] 		= 0;
    $params['all']			= 0;

    if(!$data) return $params;
    foreach($data as $item)
    {
        if($item->is_approved == NULL)
        {
            if($item->approval_user == '0') continue;

            if(cek_level_recruitment_up($item->recruitmentRequest->id))
            {
				$params['data'][$params['waiting']] = $item;
                $params['waiting'] = $params['waiting'] + 1;
            }
        }
        if($item->is_approved == 0)
        {
            $params['reject'] = $params['reject'] + 1;
        }
        if($item->is_approved == 1)
        {
            $params['approved'] = $params['approved'] + 1;
        }
    }
    $params['all'] = $params['approved'] + $params['waiting'] + $params['reject'];
    return $params;
}

function cek_level_recruitment_up($id)
{
    $recruitmentRequest = \App\Models\HistoryApprovalRecruitment::join('recruitment_request','recruitment_request.id','=','history_approval_recruitment.recruitment_request_id')->where('recruitment_request.id', $id);

    $cek1 = clone $recruitmentRequest;
    $cek1 = $cek1->where('history_approval_recruitment.structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
    if(!$cek1) return false;
    if($cek1->setting_approval_level_id >=2)
    {
        $cek2 = clone $recruitmentRequest;
        $cek2 = $cek2->where('history_approval_recruitment.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
        if($cek2)
        {
            return false;
        }
    }
    return true;
}

function cek_timesheet_id_approval($id)
{
    return cek_timesheet_approval()->where('id', $id)->first();
}

function cek_timesheet_approval()
{	
	return \App\Models\TimesheetPeriod::whereHas('timesheetPeriodTransaction', function ($query) {
        $query->join('timesheet_categories as tc', function ($join) {
            $join->on('tc.id', '=', 'timesheet_period_transactions.timesheet_category_id');
        })->join('setting_approval_timesheet_transaction_item as satti', function ($join) {
            $join->on('tc.id', '=', 'satti.timesheet_category_id');
        })->where('status', '!=', 4)->where('satti.user_id', '=', \Auth::user()->id);
    })->whereDoesntHave('timesheetPeriodTransaction', function ($query) {
        $query->where('status', '=', 4);
    })
        ->orderBy('start_date', 'DESC')
        ->select('timesheet_periods.*')
        ->get();
}

function count_timesheet_approval()
{
	$data = cek_timesheet_approval();
	$params['approved'] 	= 0;

	$item =  \App\Models\TimesheetPeriod::whereHas('timesheetPeriodTransaction', function ($query) {
        $query->join('timesheet_categories as tc', function ($join) {
            $join->on('tc.id', '=', 'timesheet_period_transactions.timesheet_category_id');
        })->join('setting_approval_timesheet_transaction_item as satti', function ($join) {
            $join->on('tc.id', '=', 'satti.timesheet_category_id');
        })->where('status', '=', 1)->where('satti.user_id', '=', \Auth::user()->id);
    });
	$params['waiting'] 		= $item->count();

	$params['data'] = $item->get();
	$params['reject'] 		= 0;
	$params['all']			= $data->count();
		
	if(!$data) return $params;
	foreach($data as $item)	
	{
        if($item->status == 3) 
        {
			$params['reject'] = $params['reject'] + 1;
        }
        if($item->status == 2)
        {
			$params['approved'] = $params['approved'] + 1;
        }
	}
	return $params;
}

function cek_level_timesheet_up($id)
{
	$all = \App\Models\HistoryApprovalTimesheet::join('timesheet_periods','timesheet_periods.id','=','history_approval_timesheet.timesheet_period_id')->where('timesheet_periods.id', $id)->get();
	
	$timesheet = \App\Models\HistoryApprovalTimesheet::join('timesheet_periods','timesheet_periods.id','=','history_approval_timesheet.timesheet_period_id')->where('timesheet_periods.id', $id);

	foreach ($all as $key => $data) {
		# code...
		if($data->status == 1)
		{
			$cek1 = clone $timesheet;
			$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
			if(!$cek1) return false;
			if($cek1->setting_approval_level_id >=2)
			{
				$cek2 = clone $timesheet;
				$cek2 = $cek2->where('history_approval_timesheet.setting_approval_level_id', ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
				if($cek2)
				{
					return false;
				} 
			}
			return true;
		}elseif ($data->status_claim == 1) {
			# code...
			$cek1 = clone $timesheet;
			$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
			if(!$cek1) return false;
			if($cek1->setting_approval_level_id >=2)
			{
				$cek2 = clone $timesheet;
				$cek2 = $cek2->where('history_approval_timesheet.setting_approval_level_id', ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved_claim')->first();
				if($cek2)
				{
					return false;
				} 
			}
			return true;
		}
	}
}

//overtime
function cek_overtime_id_approval($id)
{
    return cek_overtime_approval()->where('id', $id)->first();
}

function cek_overtime_approval()
{
	//jika statusnya 1
	//jika status claimnya 1
	$all = \App\Models\HistoryApprovalOvertime::join('overtime_sheet','overtime_sheet.id','=','history_approval_overtime.overtime_sheet_id')->orderBy('overtime_sheet_id', 'DESC')->get();
	
	$overtime = \App\Models\HistoryApprovalOvertime::join('overtime_sheet','overtime_sheet.id','=','history_approval_overtime.overtime_sheet_id')->orderBy('overtime_sheet_id', 'DESC');

	foreach ($all as $key => $data) {
		# code...
		if($data->status == 1)
		{
			$cek1 = clone $overtime;
			$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();	
			if(!$cek1) return [];
			if($cek1->setting_approval_level_id >=2)
			{
				$cek2 = clone $overtime;
				$cek2 = $cek2->where('history_approval_overtime.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
				if($cek2)
				{
					//return [];
				}
			}
		}elseif($data->status_claim == 1)
		{
			$cek1 = clone $overtime;
			$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();	
			if(!$cek1) return [];
			if($cek1->setting_approval_level_id >=2)
			{
				$cek2 = clone $overtime;
				$cek2 = $cek2->where('history_approval_overtime.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved_claim')->first();
				if($cek2)
				{
					//return [];
				} 
			}
		}
	}
	
	return $overtime->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->get();
}

function count_overtime_approval()
{
	$data = cek_overtime_approval();
	$params['approved'] 	= 0;
	$params['waiting'] 		= 0;
	$params['reject'] 		= 0;
	$params['all']			= 0;
		
	if(!$data) return $params;
	foreach($data as $item)	
	{
		if($item->is_approved == NULL || $item->is_approved_claim == NULL)
		{
			if($item->status == 3 || $item->status_claim == 3) continue;
			
            if(cek_level_overtime_up($item->overtimeSheet->id))
            {
				$params['data'][$params['waiting']] = $item;
                $params['waiting'] = $params['waiting'] + 1; 
            }
        }
        if($item->is_approved == 0 || $item->is_approved_claim == 0) 
        {
			$params['reject'] = $params['reject'] + 1;
        }
        if($item->is_approved == 1 || $item->is_approved_claim == 1)
        {
			$params['approved'] = $params['approved'] + 1;
        }
	}
	$params['all'] = $params['approved'] + $params['waiting'] + $params['reject'];
	return $params;
}

function cek_level_overtime_up($id)
{
	$all = \App\Models\HistoryApprovalOvertime::join('overtime_sheet','overtime_sheet.id','=','history_approval_overtime.overtime_sheet_id')->where('overtime_sheet.id', $id)->get();
	
	$overtime = \App\Models\HistoryApprovalOvertime::join('overtime_sheet','overtime_sheet.id','=','history_approval_overtime.overtime_sheet_id')->where('overtime_sheet.id', $id);

	foreach ($all as $key => $data) {
		# code...
		if($data->status == 1)
		{
			$cek1 = clone $overtime;
			$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
			if(!$cek1) return false;
			if($cek1->setting_approval_level_id >=2)
			{
				$cek2 = clone $overtime;
				$cek2 = $cek2->where('history_approval_overtime.setting_approval_level_id', ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
				if($cek2)
				{
					return false;
				} 
			}
			return true;
		}elseif ($data->status_claim == 1) {
			# code...
			$cek1 = clone $overtime;
			$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
			if(!$cek1) return false;
			if($cek1->setting_approval_level_id >=2)
			{
				$cek2 = clone $overtime;
				$cek2 = $cek2->where('history_approval_overtime.setting_approval_level_id', ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved_claim')->first();
				if($cek2)
				{
					return false;
				} 
			}
			return true;
		}
	}
}

//cash advance

function cek_cash_advance_id_approval_or_no($id)
{
    return \App\Models\HistoryApprovalCashAdvance::join('cash_advance','cash_advance.id','=','history_approval_cash_advance.cash_advance_id')->orderBy('cash_advance_id', 'DESC')
	->where('cash_advance.id', $id)->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
}

function cek_cash_advance_id_approval($id)
{
    return cek_cash_advance_approval()->where('id', $id)->first();
}

function cek_cash_advance_approval()
{
	//jika statusnya 1
	//jika status claimnya 1
	$all = \App\Models\HistoryApprovalCashAdvance::join('cash_advance','cash_advance.id','=','history_approval_cash_advance.cash_advance_id')->orderBy('cash_advance_id', 'DESC')->get();
	
	$cash_advance = \App\Models\HistoryApprovalCashAdvance::join('cash_advance','cash_advance.id','=','history_approval_cash_advance.cash_advance_id')->orderBy('cash_advance_id', 'DESC');
	$cek_transfer_approve = \App\Models\TransferSetting::where('user_id', auth()->user()->id)->first();
	
	foreach ($all as $key => $data) {
		# code...
		if($data->status == 1)
		{
			$cek1 = clone $cash_advance;
			$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();	
			if(!$cek1) return [];
			if($cek1->setting_approval_level_id >=2)
			{
				$cek2 = clone $cash_advance;
				$cek2 = $cek2->where('history_approval_cash_advance.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
				if($cek2)
				{
					//return [];
				} 
			}
		}elseif($data->status_claim == 1)
		{
			$cek1 = clone $cash_advance;
			$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();	
			if(!$cek1) return [];
			if($cek1->setting_approval_level_id >=2)
			{
				$cek2 = clone $cash_advance;
				$cek2 = $cek2->where('history_approval_cash_advance.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved_claim')->first();
				if($cek2)
				{
					//return [];
				} 
			}
		}
	}

	return $cash_advance->select('history_approval_cash_advance.*', 'cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
	->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->groupBy('cash_advance.id')->get();
}

function count_cash_advance_approval()
{
	$data = cek_cash_advance_approval();
	$params['approved'] 	= 0;
	$params['waiting'] 		= 0;
	$params['reject'] 		= 0;
	$params['all']			= 0;

	if(!$data) return $params;
	foreach($data as $item)	
	{
		if($item->is_approved == NULL || $item->is_approved_claim == NULL)
		{
			if($item->status == 3 || $item->status_claim == 3) continue;
			if($item->cashAdvance != null ){
				if(cek_level_cash_advance_up($item->cashAdvance->id))
				{
					$params['data'][$params['waiting']] = $item;
					$params['waiting'] = $params['waiting'] + 1; 
				}
			}
        }
        if($item->is_approved == 0 || $item->is_approved_claim == 0) 
        {
			$params['reject'] = $params['reject'] + 1;
        }
        if($item->is_approved == 1 || $item->is_approved_claim == 1)
        {
			$params['approved'] = $params['approved'] + 1;
        }
	}

	if(cek_transfer_setting_user()){
		$params['waiting'] = $params['waiting'] + getCashAdvanceWaitingTransferCount();
	}

	$params['all'] = $params['approved'] + $params['waiting'] + $params['reject'];
	return $params;
}

function cek_level_cash_advance_up($id)
{
	$all = \App\Models\HistoryApprovalCashAdvance::join('cash_advance','cash_advance.id','=','history_approval_cash_advance.cash_advance_id')->where('cash_advance.id', $id)->get();
	
	$cash_advance = \App\Models\HistoryApprovalCashAdvance::join('cash_advance','cash_advance.id','=','history_approval_cash_advance.cash_advance_id')->where('cash_advance.id', $id);

	foreach ($all as $key => $data) {
		# code...
		if($data->status == 1)
		{
			$cek1 = clone $cash_advance;
			$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
			if(!$cek1) return false;
			if($cek1->setting_approval_level_id >=2)
			{
				$cek2 = clone $cash_advance;
				$cek2 = $cek2->where('history_approval_cash_advance.setting_approval_level_id', ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
				if($cek2)
				{
					return false;
				} 
			}
			return true;
		}elseif ($data->status_claim == 1) {
			# code...
			$cek1 = clone $cash_advance;
			$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
			if(!$cek1) return false;
			if($cek1->setting_approval_level_id >=2)
			{
				$cek2 = clone $cash_advance;
				$cek2 = $cek2->where('history_approval_cash_advance.setting_approval_level_id', ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved_claim')->first();
				if($cek2)
				{
					return false;
				} 
			}
			return true;
		}
	}
}

function cek_transfer_setting_user(){
	return \App\Models\TransferSetting::where('user_id', auth()->user()->id)->first();
}

function cek_training_id_approval_or_no($id)
{
    return  \App\Models\HistoryApprovalTraining::join('training','training.id','=','history_approval_training.training_id')
		->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->orderBy('training_id', 'DESC')->where('training.id', $id)->first();
}

function cek_training_id_approval($id)
{
    return cek_training_approval()->where('id', $id)->first();
}

function cek_training_approval()
{
	/*
	$training = \App\Models\HistoryApprovalTraining::join('training','training.id','=','history_approval_training.training_id')->orderBy('training_id', 'DESC');
	$cek1 = clone $training;
	$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
	
	if(!$cek1) return [];
	if($cek1->setting_approval_level_id >=2)
	{
		$cek2 = clone $training;
		$cek2 = $cek2->where('history_approval_training.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
		if($cek2)
		{
			//return [];
		} 
	}
	return $training->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->get();

	*/

	//jika statusnya 1
	//jika status claimnya 1
	$all = \App\Models\HistoryApprovalTraining::join('training','training.id','=','history_approval_training.training_id')->orderBy('training.id', 'DESC')->get();
	
	$training = \App\Models\HistoryApprovalTraining::join('training','training.id','=','history_approval_training.training_id')->orderBy('training.id', 'DESC');

	foreach ($all as $key => $data) {
		# code...
		if($data->status == 1)
		{
			$cek1 = clone $training;
			$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();	
			if(!$cek1) return [];
			if($cek1->setting_approval_level_id >=2)
			{
				$cek2 = clone $training;
				$cek2 = $cek2->where('history_approval_training.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
				if($cek2)
				{
					//return [];
				} 
			}
		}elseif($data->status_actual_bill == 1)
		{
			$cek1 = clone $training;
			$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();	
			if(!$cek1) return [];
			if($cek1->setting_approval_level_id >=2)
			{
				$cek2 = clone $training;
				$cek2 = $cek2->where('history_approval_training.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved_claim')->first();
				if($cek2)
				{
					//return [];
				} 
			}
		}
	}
	
	return $training->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->get();

}

function count_training_approval()
{
	$data = cek_training_approval();
	$params['approved'] 	= 0;
	$params['waiting'] 		= 0;
	$params['reject'] 		= 0;
	$params['all']			= 0;

	if(!$data) return $params;
	foreach($data as $item)	
	{
		if($item->is_approved == NULL || $item->is_approved_claim == NULL)
		{
			if($item->status == 3 || $item->status_actual_bill == 3) continue;
			
            if(cek_level_training_up($item->training->id))
            {
				$params['data'][$params['waiting']] = $item;
                $params['waiting'] = $params['waiting'] + 1; 
            }
        }
        if($item->is_approved == 0 || $item->is_approved_claim == 0)
        {
			$params['reject'] = $params['reject'] + 1;
        }
        if($item->is_approved == 1 || $item->is_approved_claim == 1)
        {
			$params['approved'] = $params['approved'] + 1;
        }
	}

	if(cek_transfer_setting_user()){
		$params['waiting'] = $params['waiting'] + getTrainingWaitingTransferCount();
	}

	$params['all'] = $params['approved'] + $params['waiting'] + $params['reject'];
	return $params;

}

function cek_level_training_up($id)
{
	$all = \App\Models\HistoryApprovalTraining::join('training','training.id','=','history_approval_training.training_id')->where('training.id', $id)->get();
	
	$training = \App\Models\HistoryApprovalTraining::join('training','training.id','=','history_approval_training.training_id')->where('training.id', $id);

	foreach ($all as $key => $data) {
		# code...
		if($data->status == 1)
		{
			$cek1 = clone $training;
			$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
			if(!$cek1) return false;
			if($cek1->setting_approval_level_id >=2)
			{
				$cek2 = clone $training;
				$cek2 = $cek2->where('history_approval_training.setting_approval_level_id', ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
				if($cek2)
				{
					return false;
				} 
			}
			return true;
		}elseif ($data->status_actual_bill == 1) {
			# code...
			$cek1 = clone $training;
			$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
			if(!$cek1) return false;
			if($cek1->setting_approval_level_id >=2)
			{
				$cek2 = clone $training;
				$cek2 = $cek2->where('history_approval_training.setting_approval_level_id', ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved_claim')->first();
				if($cek2)
				{
					return false;
				} 
			}
			return true;
		}
	}

}

//medical
function cek_medical_id_approval_or_no($id)
{
    return  \App\Models\HistoryApprovalMedical::join('medical_reimbursement','medical_reimbursement.id','=','history_approval_medical.medical_reimbursement_id')
		->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->orderBy('medical_reimbursement_id', 'DESC')->where('medical_reimbursement.id', $id)->first();
}

function cek_medical_id_approval($id)
{
    return cek_medical_approval()->where('id', $id)->first();
}

function cek_medical_approval()
{
	$medical = \App\Models\HistoryApprovalMedical::join('medical_reimbursement','medical_reimbursement.id','=','history_approval_medical.medical_reimbursement_id')->where('medical_reimbursement.status', '!=', 5)->orderBy('medical_reimbursement_id', 'DESC');

	$cek1 = clone $medical;
	$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
	
	if(!$cek1) return [];
	if($cek1->setting_approval_level_id >=2)
	{
		$cek2 = clone $medical;
		$cek2 = $cek2->where('history_approval_medical.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
		if($cek2)
		{
			//return [];
		} 
	}
	return $medical->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->get();
}

function count_medical_approval()
{
	$data = cek_medical_approval();
	$params['approved'] 	= 0;
	$params['waiting'] 		= 0;
	$params['reject'] 		= 0;
	$params['all']			= 0;
		
	if(!$data) return $params;
	foreach($data as $item)	
	{
		if($item->is_approved == NULL)
		{
			if($item->status == 3) continue;
			
            if(cek_level_medical_up($item->medicalReimbursement->id))
            {
				$params['data'][$params['waiting']] = $item;
                $params['waiting'] = $params['waiting'] + 1;  
            }
        }
        if($item->is_approved == 0)
        {
			$params['reject'] = $params['reject'] + 1;
        }
        if($item->is_approved == 1)
        {
			$params['approved'] = $params['approved'] + 1;
        }
	}

	if(cek_transfer_setting_user()){
		$params['waiting'] = $params['waiting'] + getMedicalCount();
	}

	$params['all'] = $params['approved'] + $params['waiting'] + $params['reject'];
	return $params;
}

function cek_level_medical_up($id)
{
	$medical = \App\Models\HistoryApprovalMedical::join('medical_reimbursement','medical_reimbursement.id','=','history_approval_medical.medical_reimbursement_id')->where('medical_reimbursement.id', $id);

	$cek1 = clone $medical;
	$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
	if(!$cek1) return false;
	if($cek1->setting_approval_level_id >=2)
	{
		$cek2 = clone $medical;
		$cek2 = $cek2->where('history_approval_medical.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
		if($cek2)
		{
			return false;
		} 
	}

	return true;
}

//loan
function cek_loan_id_approval($id)
{
    return cek_loan_approval()->where('id', $id)->first();
}

function cek_loan_approval()
{
	$loan = \App\Models\HistoryApprovalLoan::join('loan','loan.id','=','history_approval_loan.loan_id')->orderBy('loan_id', 'DESC');

	$cek1 = clone $loan;
	$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
	
	if(!$cek1) return [];
	if($cek1->setting_approval_level_id >=2)
	{
		$cek2 = clone $loan;
		$cek2 = $cek2->where('history_approval_loan.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
		if($cek2)
		{
			//return [];
		} 
	}
	return $loan->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->get();
}

function count_loan_approval()
{
	$data = cek_loan_approval();
	$params['approved'] 	= 0;
	$params['waiting'] 		= 0;
	$params['reject'] 		= 0;
	$params['all']			= 0;
		
	if(!$data) return $params;
	foreach($data as $item)	
	{
		if($item->is_approved == NULL)
		{
			if($item->status == 3) continue;
			
            if(cek_level_loan_up($item->loan->id))
            {
				$params['data'][$params['waiting']] = $item;
                $params['waiting'] = $params['waiting'] + 1; 
            }
        }
        if($item->is_approved == 0)
        {
			$params['reject'] = $params['reject'] + 1;
        }
        if($item->is_approved == 1)
        {
			$params['approved'] = $params['approved'] + 1;
        }
	}
	$params['all'] = $params['approved'] + $params['waiting'] + $params['reject'];
	return $params;
}

function cek_level_loan_up($id)
{
	$loan = \App\Models\HistoryApprovalLoan::join('loan','loan.id','=','history_approval_loan.loan_id')->where('loan.id', $id);

	$cek1 = clone $loan;
	$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
	if(!$cek1) return false;
	if($cek1->setting_approval_level_id >=2)
	{
		$cek2 = clone $loan;
		$cek2 = $cek2->where('history_approval_loan.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
		if($cek2)
		{
			return false;
		} 
	}

	return true;
}

//exit interview
function cek_exit_approval()
{
	$exit = \App\Models\HistoryApprovalExit::join('exit_interview','exit_interview.id','=','history_approval_exit.exit_interview_id')
        ->join('users as u','exit_interview.user_id','=','u.id')->orderBy('exit_interview_id', 'DESC')->select(['history_approval_exit.*']);

	$cek1 = clone $exit;
	$cek1 = $cek1->where('history_approval_exit.structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
	
	if(!$cek1) return [];
	if($cek1->setting_approval_level_id >=2)
	{
		$cek2 = clone $exit;
		$cek2 = $cek2->where('history_approval_exit.setting_approval_level_id',  ($cek1->setting_approval_level_id - 1))->whereNull('is_approved')->first();
		if($cek2)
		{
			//return [];
		} 
	}
	return $exit->where('history_approval_exit.structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->get();
}

function count_exit_approval()
{
	$data = cek_exit_approval();
	$params['approved'] 	= 0;
	$params['waiting'] 		= 0;
	$params['reject'] 		= 0;
	$params['all']			= 0;
		
	if(!$data) return $params;
	foreach($data as $item)	
	{
		if($item->is_approved == NULL)
		{
			if($item->exitInterview->status == 3) continue;
			
            if(cek_level_exit_up($item->exitInterview->id))
            {
				$params['data'][$params['waiting']] = $item;
                $params['waiting'] = $params['waiting'] + 1; 
            }
        }
        if($item->is_approved == 0)
        {
			$params['reject'] = $params['reject'] + 1;
        }
        if($item->is_approved == 1)
        {
			$params['approved'] = $params['approved'] + 1;
        }
	}
	$params['all'] = $params['approved'] + $params['waiting'] + $params['reject'];
	return $params;
}

function cek_level_exit_up($id)
{
	$exit = \App\Models\HistoryApprovalExit::join('exit_interview','exit_interview.id','=','history_approval_exit.exit_interview_id')->where('exit_interview.id', $id);

	$cek1 = clone $exit;
	$cek1 = $cek1->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();
	if(!$cek1) return false;
	if($cek1->setting_approval_level_id >=2)
	{
		$cek2 = clone $exit;
		$cek2 = $cek2->where('history_approval_exit.setting_approval_level_id',  ( $cek1->setting_approval_level_id - 1) )->whereNull('is_approved')->first();
		if($cek2)
		{
			return false;
		} 
	}

	return true;
}


//exit clearance
function count_clearance_approval()
{
	// cek jenis user
	$user = \Auth::user();

    $pics = \App\Models\SettingApprovalClearance::where('user_id',$user->id)->pluck('nama_approval')->toArray();
    
   	$params['approved'] 	= 0;
	$params['waiting'] 		= 0;
	$params['reject'] 		= 0;
	$params['all']			= 0;


    if(count($pics)>0){
        $params['approved'] 	= \App\Models\ExitInterview::whereRaw("(select count(*) from exit_interview_assets ea
                    join asset a on ea.asset_id = a.id
                    join asset_type as at on a.asset_type_id = at.id
                    where exit_interview.id = ea.exit_interview_id and ea.approval_check = 1
                           and at.pic_department in (SELECT sc.nama_approval from setting_approval_clearance sc where sc.user_id = ?)) > ?",[$user->id, 0])->count();
        $params['waiting'] 		= \App\Models\ExitInterview::whereRaw("(select count(*) from exit_interview_assets ea
                    join asset a on ea.asset_id = a.id
                    join asset_type as at on a.asset_type_id = at.id
                    where exit_interview.id = ea.exit_interview_id and ea.approval_check is null and exit_interview.status_clearance = 0
                           and at.pic_department in (SELECT sc.nama_approval from setting_approval_clearance sc where sc.user_id = ?)) > ?",[$user->id, 0])->count();
        $params['reject'] 		= \App\Models\ExitInterview::whereRaw("(select count(*) from exit_interview_assets ea
                    join asset a on ea.asset_id = a.id
                    join asset_type as at on a.asset_type_id = at.id
                    where exit_interview.id = ea.exit_interview_id and ea.approval_check != 1 and exit_interview.status_clearance = 2
                           and at.pic_department in (SELECT sc.nama_approval from setting_approval_clearance sc where sc.user_id = ?)) > ?",[$user->id, 0])->count();
        $params['all']			= $params['approved'] + $params['waiting'] + $params['reject'];
		$params['data'] 		= \App\Models\ExitInterview::whereRaw("(select count(*) from exit_interview_assets ea
					join asset a on ea.asset_id = a.id
					join asset_type as at on a.asset_type_id = at.id
					where exit_interview.id = ea.exit_interview_id and ea.approval_check is null and exit_interview.status_clearance = 0
						and at.pic_department in (SELECT sc.nama_approval from setting_approval_clearance sc where sc.user_id = ?)) > ?",[$user->id, 0])->get();
	}

    return $params;
}

function count_facilities_user(){
	$user = \Auth::user();

	return 	\App\Models\Asset::where('user_id',$user->id)->where('status', 0)->where('handover_date', NULL)->count();
}

function count_facilities_approval()
{
	// cek jenis user
	$user = \Auth::user();

    $pics = \App\Models\SettingApprovalClearance::where('user_id',$user->id)->pluck('nama_approval')->toArray();
    
   	$params['approved'] 	= 0;
	$params['waiting'] 		= 0;
	$params['reject'] 		= 0;
	$params['all']			= 0;
	if(count($pics)>0){
		$type = \App\Models\AssetType::whereIn('pic_department', $pics)->pluck('id')->toArray();

		$params['approved'] 	= \App\Models\AssetTracking::whereHas('asset', function($qry) use($type){
									$qry->whereIn('asset_type_id', $type);
								})->where('is_return', '1')->where('status_return', '1')->count();
		$params['waiting'] 	= \App\Models\AssetTracking::whereHas('asset', function($qry) use($type){
									$qry->whereIn('asset_type_id', $type);
								})->where('is_return', '1')->where('status_return', '0')->count();
		$params['reject'] 	= \App\Models\AssetTracking::whereHas('asset', function($qry) use($type){
									$qry->whereIn('asset_type_id', $type);
								})->where('is_return', '1')->where('status_return', '2')->count();
		$params['all']			= $params['approved'] + $params['waiting'] + $params['reject'];
		$params['data'] 	= \App\Models\AssetTracking::whereHas('asset', function($qry) use($type){
								$qry->whereIn('asset_type_id', $type);
							})->where('is_return', '1')->where('status_return', '0')->get();
	}

	return $params;
}



function replace_idr($nominal)
{
	if($nominal == null || $nominal == "" || $nominal == "0") return 0;
	
	$nominal = str_replace('Rp. ','', $nominal);
    $nominal = str_replace('.', '', $nominal);
    $nominal = str_replace(',', '', $nominal);

    return $nominal;
}

/**
 * Times Zone
 * @return array
 */
function generate_timezone_list()
{
    static $regions = array(
        DateTimeZone::AFRICA,
        DateTimeZone::AMERICA,
        DateTimeZone::ANTARCTICA,
        DateTimeZone::ASIA,
        DateTimeZone::ATLANTIC,
        DateTimeZone::AUSTRALIA,
        DateTimeZone::EUROPE,
        DateTimeZone::INDIAN,
        DateTimeZone::PACIFIC,
    );

    $timezones = array();
    foreach( $regions as $region )
    {
        $timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
    }

    $timezone_offsets = array();
    foreach( $timezones as $timezone )
    {
        $tz = new DateTimeZone($timezone);
        $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
    }

    // sort timezone by offset
    asort($timezone_offsets);

    $timezone_list = array();
    foreach( $timezone_offsets as $timezone => $offset )
    {
        $offset_prefix = $offset < 0 ? '-' : '+';
        $offset_formatted = gmdate( 'H:i', abs($offset) );

        $pretty_offset = "UTC${offset_prefix}${offset_formatted}";

        $timezone_list[$timezone] = "(${pretty_offset}) $timezone";
    }

    return $timezone_list;
}

/**
 * List Language
 * @return array
 */
function list_language()
{
	return ['id' => 'Indonesia', 'en' => 'English'];
}

/**
 * Update Setting
 */
function update_setting($key, $value)
{
	$auth = \Auth::user();
	if($auth) {
		if($auth->project_id != NULL) {
        	$setting = \App\Models\Setting::where('key', $key)->where('project_id', $auth->project_id)->first();
        } else {
        	$setting = \App\Models\Setting::where('key', $key)->first();
        }
	} else {
		$setting = \App\Models\Setting::where('key', $key)->first();
    }
    
	if(!$setting) {
        $setting = new \App\Models\Setting;
        $setting->key = $key;
        if($auth) {
            $setting->user_created = $auth->id;
            if($auth->project_id != NULL) {
                $setting->project_id = $auth->project_id;
            }
        }
    }
    $setting->value = $value;
    $setting->save();
}

/**
 * Get Setting
 * @param  $key
 * @return string
 */
function get_setting($key)
{
	// $setting = \App\Models\Setting::where('key', $key)->first();
	// if($key == 'layout_karyawan'){
	// 	if($setting)
	// 	{
	// 		return $setting->value;
	// 	}
	// }
	$auth = \Auth::user();
	if($auth) {
		if($auth->project_id != NULL) {
        	$setting = \App\Models\Setting::where('key', $key)->where('project_id', $auth->project_id)->first();
        } else {
        	$setting = \App\Models\Setting::where('key', $key)->first();
        }
	} else {
		$setting = \App\Models\Setting::where('key', $key)->first();
    }

	if($setting)
	{
		return $setting->value;
	}
	
	return '';
}

function get_schedule()
{
	return \App\Models\ScheduleBackup::all();
}


/**
 * [format_tanggal description]
 * @param  [type] $date [description]
 * @return [type]       [description]
 */
function format_tanggal($date, $format='tanggal')
{
	if($format=='tanggal')
	{
		return date('d F Y', strtotime($date));		
	}

	if($format=='tanggal_jam')
	{
		return date('d F Y H:i:s', strtotime($date));		
	}
	
}

/**
 * [jenis_claim_medical description]
 * @param  string $key [description]
 * @return [type]      [description]
 */
function jenis_claim_medical($key="")
{
	$arr = ['RJ' => 'Outpatient', 'RI' => 'Inpatient', 'MA' => 'Maternity','Frame' => 'Frame', 'Glasses' => 'Glasses'];
	if(!empty($key))
	{
		return @$arr[$key];
	}
	else
	{
		return @$arr;
	}
}

/**
 * [total_medical_nominal description]
 * @return [type] [description]
 */
function total_medical_nominal($id)
{
	$data = \App\Models\MedicalReimbursementForm::where('medical_reimbursement_id', $id)->get();
	$nominal = 0;

	foreach($data as $item)
	{
		$nominal  += $item->jumlah;
	}

	return $nominal;
}
function total_medical_nominal_approved($id)
{
    $data = \App\Models\MedicalReimbursementForm::where('medical_reimbursement_id', $id)->get();
    $nominal = 0;

    foreach($data as $item)
    {
        $nominal  += $item->nominal_approve;
    }

    return $nominal;
}

/**
 * [jenis_claim_strint description]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function medical_jenis_claim_string($id)
{
	$data = \App\Models\MedicalReimbursementForm::where('medical_reimbursement_id', $id)->get();
	$string = "";

	foreach($data as $item)
	{
		$string  .= jenis_claim_medical($item->jenis_klaim) .' ,';
	}

	return substr($string, 0, -1);
}

function medical_type_string($id)
{
	$data = \App\Models\MedicalReimbursementForm::where('medical_reimbursement_id', $id)->get();
	$string = "";

	foreach($data as $item)
	{
		$string  .= $item->medicalType->name.' ,';
	}

	return substr($string, 0, -1);
}

/**
 * @param  [type]
 * @return [type]
 */
function jabatan_level_user($id)
{
	$user = \App\User::where('id', $id)->first();

	if($user)
	{
		if(!empty($user->empore_organisasi_staff_id)):
            return 'Staff';
        endif;

        if(empty($user->empore_organisasi_staff_id) and !empty($user->empore_organisasi_manager_id)):
            return 'Manager';
        endif;

        if(empty($user->empore_organisasi_staff_id) and empty($user->empore_organisasi_manager_id) and !empty($user->empore_organisasi_direktur)):
            return 'Direktur';
        endif;
	}

	return;
}

/**
 * @return [type]
 */
function get_level_organisasi()
{
	$organisasi = ['Staff', 'Manager', 'Direktur'];
	
	return $organisasi;
}

/**
 * [pay_slip_tahun description]
 * @return [type] [description]
 */
function pay_slip_tahun($id)
{
	$data = \App\Models\Payroll::select(DB::raw('year(created_at) as tahun'))->where('user_id', $id)->get();

	return $data;
}

/**
 * [pay_slip_tahun_history description]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function pay_slip_tahun_history($id)
{
	$data = \App\Models\PayrollHistory::select(DB::raw('year(created_at) as tahun'))->where('user_id', $id)->groupBy('tahun')->get();

	return $data;
}

function pay_slip_tahun_historyNet($id)
{
	$data = \App\Models\PayrollNetHistory::select(DB::raw('year(created_at) as tahun'))->where('user_id', $id)->groupBy('tahun')->get();

	return $data;
}

function pay_slip_tahun_historyGross($id)
{
	$data = \App\Models\PayrollGrossHistory::select(DB::raw('year(created_at) as tahun'))->where('user_id', $id)->groupBy('tahun')->get();

	return $data;
}

/**
 * [asset_type description]
 * @return [type] [description]
 */
function asset_type($id=null)
{
	if($id != null)
		return \App\Models\AssetType::where('id', $id)->get();
	else
		if(\Auth::user()->project_id != Null){
			return \App\Models\AssetType::where('project_id', \Auth::user()->project_id)->get();
		}else{
			return \App\Models\AssetType::all();
		}
}

function total_payment_request_nominal($id)
{
    $data = \App\Models\PaymentRequestForm::where('payment_request_id', $id)->get();
    $nominal = 0;

    foreach($data as $item)
    {
        $nominal  += $item->amount;
    }

    return $nominal;
}

function total_payment_request_nominal_approved($id)
{
    $data = \App\Models\PaymentRequestForm::where('payment_request_id', $id)->get();
    $nominal = 0;

    foreach($data as $item)
    {
        $nominal  += $item->nominal_approved;
    }

    return $nominal;
}

function total_cash_advance_nominal($id)
{
    $data = \App\Models\CashAdvanceForm::where('cash_advance_id', $id)->get();
    $nominal = 0;

    foreach($data as $item)
    {
        $nominal  += $item->amount;
    }

    return $nominal;
}

function total_cash_advance_nominal_approved($id)
{
    $data = \App\Models\CashAdvanceForm::where('cash_advance_id', $id)->get();
    $nominal = 0;

    foreach($data as $item)
    {
        $nominal  += $item->nominal_approved;
    }

    return $nominal;
}

function total_cash_advance_actual_amount($id)
{
    $data = \App\Models\CashAdvanceForm::where('cash_advance_id', $id)->get();
    $nominal = 0;

    foreach($data as $item)
    {
        $nominal  += $item->actual_amount;
    }

    return $nominal;
}

function total_cash_advance_nominal_claimed($id)
{
    $data = \App\Models\CashAdvanceForm::where('cash_advance_id', $id)->get();
    $nominal = 0;

    foreach($data as $item)
    {
        $nominal  += $item->nominal_claimed;
    }

    return $nominal;
}

/**
 * @param  [type]
 * @param  [type]
 * @param  [type]
 * @return [type]
 */

function get_cuti_user($cuti_id, $user_id, $field)
{
	$cuti = \App\Models\UserCuti::where('user_id', $user_id)->where('cuti_id', $cuti_id)->first();

	$jenis_cuti = \App\Models\Cuti::where('id', $cuti_id)->first();
	if($cuti){
		if(isset($cuti->$field))
		{
			return $cuti->$field;
		}
	}
	else
		return $jenis_cuti->kuota;

}

/**
 * @return [type]
 */
function cek_cuti_direktur($status='approved')
{
	if($status=='approved')
	{
		$cuti = \App\Models\CutiKaryawan::where('approve_direktur_id', \Auth::user()->id)->where('approve_direktur', 1)->count();		
	}
	elseif($status == 'null')
	{
		//$cuti = \App\Models\CutiKaryawan::where('approve_direktur_id', \Auth::user()->id)->whereNull('approve_direktur')->count();	
		$cuti = \App\Models\CutiKaryawan::where('approve_direktur_id', \Auth::user()->id)->where('status' ,'<' ,3)->where('is_approved_atasan',1)->whereNull('approve_direktur')->count();	
	}

	return $cuti;
}


/**
 * @param  string
 * @param  integer 
 * @return [type]
 */
function cek_cuti_atasan($status='approved')
{
	if($status =='null')
	{
		//return \App\Models\CutiKaryawan::where('approved_atasan_id', \Auth::user()->id)->whereNull('is_approved_atasan')->count();
		return \App\Models\CutiKaryawan::where('approved_atasan_id', \Auth::user()->id)->where('status' ,'<' ,3)->whereNull('is_approved_atasan')->count();
	}
	elseif($status =='approved')
	{
		return \App\Models\CutiKaryawan::where('approved_atasan_id', \Auth::user()->id)->where('is_approved_atasan',1)->count();
	}
	elseif($status=='reject')
	{
		return \App\Models\CutiKaryawan::where('approved_atasan_id', \Auth::user()->id)->where('is_approved_atasan',0)->count();
	}
	elseif($status=='all')
	{
		return \App\Models\CutiKaryawan::where('approved_atasan_id', \Auth::user()->id)->count();
	}
}

function getTypeProvinsi($id_prov){
	$user = \Auth::user();

	$check = \App\Models\ProvinsiDetailAllowance::where('id_prov', $id_prov)
												->where('project_id', $user->project_id)
												->count();
	if($check < 1){
		$type = "";
	}else{

		$gettype =  \App\Models\ProvinsiDetailAllowance::where('id_prov', $id_prov)
												->where('project_id', $user->project_id)
												->first();
		$type = $gettype->type;
	}
	
	return $type;
}

function getNamaHari($date){
//	$arrayhari = array("Minggu"=>"Sun", "Senin"=>"Mon", "Selasa"=>"Tue", "Rabu"=>"Wed", "Kamis"=>"Thu", "Jumat"=>"Fri", "Sabtu"=>"Sat");
	$arrayhari = array("Sunday"=>"Sun", "Monday"=>"Mon", "Tuesday"=>"Tue", "Wednesday"=>"Wed", "Thursday"=>"Thu", "Friday"=>"Fri", "Saturday"=>"Sat");
	$day = array_search(date_format(date_create($date), "D"), $arrayhari);
	
	return $day;
}

function getEmailConfig(){
    $params['mail_driver'] = get_setting('mail_driver');
    $params['mail_host'] = get_setting('mail_host');
    $params['mail_port'] = get_setting('mail_port');
    $params['mail_from'] = ['address' => get_setting('mail_username'), 'name' => get_setting('mail_name')];
    $params['mail_username'] = get_setting('mail_username');
    $params['mail_password'] = get_setting('mail_password');
    $params['mail_encryption'] = get_setting('mail_encryption');
    $params['mail_name'] = get_setting('mail_name');
    $params['mail_signature'] = get_setting('mail_signature');
    $params['title'] = get_setting('title');
    $params['logo'] = get_setting('logo');
    return $params;
}

function getCompany($company_code)
{
    if(!$company_code)
        return null;
    $config = App\Models\ConfigDB::where('company_code',strtolower($company_code))->where('due_date', '>=', \Carbon\Carbon::now()->startOfDay())->first();
    return $config;
}

function get_all_user(){
    $user = \Auth::user();
    if($user->project_id != Null){
        return \App\User::whereIn('access_id', ['1', '2'])
            ->where('project_id', \Auth::user()->project_id)
            ->where(function($query) {
            $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
        })->where(function($query) {
            $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
        })->get();
    }else{

        return \App\User::whereIn('access_id', ['1', '2'])
            ->where(function($query) {
            $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
        })->where(function($query) {
            $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
        })->get();
    }
}

function isJsonValid($string) {
    return (json_decode($string) == null) ? false : true;
}

function getTimesheetActivity($id) {
    return \App\Models\TimesheetActivity::where('timesheet_category_id', $id)->withTrashed()->get();
}

function getTimesheetCategory() {
    return \App\Models\TimesheetCategory::withTrashed()->get();
}

function getAvailableTimesheetActivity($id) {
    return \App\Models\TimesheetActivity::where('timesheet_category_id', $id)->get();
}

function getAvailableTimesheetCategory() {
    return \App\Models\TimesheetCategory::all();
}

function IsAccess($data) {
    return $data && \Illuminate\Support\Facades\Gate::allows('user-access', $data);
}

function getLoanWaitingHRCount(){
    $user = \Auth::user();
    return \App\Models\Loan::where('status', 1)->whereDoesntHave('historyApproval', function($query){
        $query->where('is_approved', 0)->orWhereNull('is_approved');
    })->count();
}

function getCashAdvanceWaitingTransferCount(){
	$user = \Auth::user();
	$i = 0;
	$cash_advance = \App\Models\HistoryApprovalCashAdvance::select('history_approval_cash_advance.*', 'cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
				->join('cash_advance','cash_advance.id','=','history_approval_cash_advance.cash_advance_id')->orderBy('cash_advance_id', 'DESC')
				->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id')->get();
	foreach($cash_advance as $no => $item){
		if($item->status==2 && $item->is_transfer==0 && $item->payment_method == 'Bank Transfer'){
			$i = $i +1;
		}
		if($item->status_claim==2 && $item->is_transfer_claim==0 && $item->payment_method == 'Bank Transfer' && $item->total_amount_claimed != $item->total_amount_approved){
			$i = $i +1;
		}
	}
	return $i;
}

function getTrainingWaitingTransferCount(){
	$user = \Auth::user();
	$i = 0;
	$training = \App\Models\HistoryApprovalTraining::join('training','training.id','=','history_approval_training.training_id')->orderBy('training.id', 'DESC')->groupBy('training.id')->get();
	foreach($training as $no => $item){
		if($item->status==2 && $item->is_transfer==0){
			$i = $i +1;
		}
		if($item->status_actual_bill==2 && $item->is_transfer_claim==0 && ($item->sub_total_1_disetujui + $item->sub_total_2_disetujui + $item->sub_total_3_disetujui + $item->sub_total_4_disetujui - $item->pengambilan_uang_muka != 0)){
			$i = $i +1;
		}
	}
	return $i;
}

function getPaymentRequestCount(){
	$user = \Auth::user();
	return \App\Models\PaymentRequest::where('status', 2)->where('payment_method', 'Bank Transfer')->where('is_transfer', 0)->count();
}

function getMedicalCount(){
	$user = \Auth::user();
	return \App\Models\MedicalReimbursement::where('status', 2)->where('is_transfer', 0)->count();
}

function getTrainingWaitingTransferUserCount(){
	$user = \Auth::user();
	$i = 0;
	$training = \App\Models\HistoryApprovalTraining::join('training','training.id','=','history_approval_training.training_id')->where('training.user_id', auth()->user()->id)->orderBy('training.id', 'DESC')->groupBy('training.id')->get();
	foreach($training as $no => $item){
		if($item->status_actual_bill==2 && $item->is_transfer_claim==0 && ($item->sub_total_1_disetujui + $item->sub_total_2_disetujui + $item->sub_total_3_disetujui + $item->sub_total_4_disetujui - $item->pengambilan_uang_muka < 0)){
			$i = $i +1;
		}
	}
	return $i;
}

function getCashAdvanceWaitingTransferUserCount(){
	$user = \Auth::user();
	$i = 0;
	$cash_advance = \App\Models\CashAdvance::select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
				->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id')
				->where('user_id', \Auth::user()->id)->orderBy('cash_advance.id', 'DESC')->get();
	foreach($cash_advance as $no => $item){
		if($item->status_claim==2 && $item->is_transfer_claim==0 && $item->payment_method == 'Bank Transfer' && $item->total_amount_claimed < $item->total_amount_approved){
			$i = $i +1;
		}
	}
	return $i;
}

function getLoanPaymentWaitingHRCount(){
    return \App\Models\LoanPayment::where('status', 1)->count();
}

function notif(){
	$data['cash_advance'] = count_cash_advance_approval();
	$data['overtime'] = count_overtime_approval();
	$data['timesheet'] = count_timesheet_approval();
	$data['leave'] = count_leave_approval();
	$data['payment'] = count_payment_request_approval();
	$data['recruitment'] = count_recruitment_approval();
	$data['training'] = count_training_approval();
	$data['medical'] = count_medical_approval();
	$data['exit'] = count_exit_approval();
	$data['facilities'] = count_facilities_approval();
	$data['clearance'] = count_clearance_approval();
	$data['loan'] = count_loan_approval();
	return($data);
}

function cek_user_like($id){
	return \App\Models\BirthdayLike::where('user_id', $id)->where('date', date('Y-m-d'))->where('like_by', auth()->user()->id)->count();
}

function cek_user_comment($id){
	return \App\Models\BirthdayComment::where('user_id', $id)->where('parent_id', NULL)->where('date', date('Y-m-d'))->where('comment_by', auth()->user()->id)->count() < 2 ? true : false;
}

function cek_user_comment_like($id){
	return \App\Models\BirthdayCommentLike::where('comment_id', $id)->where('date', date('Y-m-d'))->where('like_by', auth()->user()->id)->count();
}

function cek_user_comment_reply($id, $parent_id){
	return \App\Models\BirthdayComment::where('user_id', $id)->where('parent_id', $parent_id)->where('date', date('Y-m-d'))->where('comment_by', auth()->user()->id)->count() < 2 ? true : false;
}

function count_birthday_like($id){
	return \App\Models\BirthdayLike::where('user_id', $id)->where('date', date('Y-m-d'))->count();
}

function get_available_plafond($type_name){
		$period_ca_pr = \App\Models\Setting::where('key', 'period_ca_pr')->first();
        $type = \App\Models\PaymentRequestType::where('type', $type_name)->first();
        if(isset($period_ca_pr)){
            if($period_ca_pr->value=='yes'){
                $data = \App\Models\PaymentRequestForm::whereHas('paymentRequest', function($qry){
                    $qry->where('user_id', auth()->user()->id)->where('status', '!=', 3);
                })->where('type_form', $type_name)->where('created_at', '>=', $period_ca_pr->updated_at)->orderBy('id', 'DESC');
                
                if($type->period=='Daily'){
                    $data = $data->whereDate('created_at',  \Carbon\Carbon::today());
                }
                elseif($type->period=='Weekly'){
                    $data = $data->whereBetween('created_at', [ \Carbon\Carbon::now()->startOfWeek(),  \Carbon\Carbon::now()->endOfWeek()]);
                }elseif($type->period=='Monthly'){
                    $data = $data->whereYear('created_at', '=',  \Carbon\Carbon::now()->year)
                                ->whereMonth('created_at', '=',  \Carbon\Carbon::now()->month);
                }elseif($type->period=='Yearly'){
                    $data = $data->whereYear('created_at', '=',  \Carbon\Carbon::now()->year);
                }
                if($data->first()){
					$data = $data->first();
                    return $data->sisa_plafond;
                }
                else{
                    return  $type->plafond;
                }
            }
            else{
                return $type->plafond;
            }
        }
        else{
            return $type->plafond;
        }
}

function get_available_plafond_ca($type_name){
	$period_ca_pr = \App\Models\Setting::where('key', 'period_ca_pr')->first();
	$type = \App\Models\PaymentRequestType::where('type', $type_name)->first();
	if(isset($period_ca_pr)){
		if($period_ca_pr->value=='yes'){
			$data = \App\Models\CashAdvanceForm::whereHas('cashAdvance', function($qry){
				$qry->where('user_id', auth()->user()->id)->where('status', '!=', 3)->orWhere('status_claim', '!=', 3);
			})->where('type_form', $type_name)->where('created_at', '>=', $period_ca_pr->updated_at)->orderBy('id', 'DESC');
			
			if($type->period=='Daily'){
				$data = $data->whereDate('created_at',  \Carbon\Carbon::today());
			}
			elseif($type->period=='Weekly'){
				$data = $data->whereBetween('created_at', [ \Carbon\Carbon::now()->startOfWeek(),  \Carbon\Carbon::now()->endOfWeek()]);
			}elseif($type->period=='Monthly'){
				$data = $data->whereYear('created_at', '=',  \Carbon\Carbon::now()->year)
							->whereMonth('created_at', '=',  \Carbon\Carbon::now()->month);
			}elseif($type->period=='Yearly'){
				$data = $data->whereYear('created_at', '=',  \Carbon\Carbon::now()->year);
			}
			if($data->first()){
				$data = $data->first();
				return $data->sisa_plafond;
			}
			else{
				return  $type->plafond;
			}
		}
		else{
			return $type->plafond;
		}
	}
	else{
		return $type->plafond;
	}
}