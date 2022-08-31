<?php


/**
 * @param  string
 * @return [type] 
 */

function user_approval_custom($id)
{
	return App\User::where('structure_organization_custom_id',$id)->where(function($query) {
        $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
    })->where(function($query) {
        $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
    })->get();
}

function user_approval_firebase_tokens($id){
    return $tokens = App\User::where('structure_organization_custom_id',$id)->whereNotNull('firebase_token')->pluck('firebase_token')->toArray();
}

function user_approval_id($id){
    return $id = App\User::where('structure_organization_custom_id',$id)->whereNotNull('id')->pluck('id')->toArray();
}

function cek_training_direktur($status='approved')
{
	if($status=='approved')
	{
		$data = \App\Models\Training::where('approve_direktur_id', \Auth::user()->id)->where('approve_direktur', 1)->count();		
	}
	elseif($status == 'null')
	{
		$data = \App\Models\Training::where('approve_direktur_id', \Auth::user()->id)->whereNull('approve_direktur')->count();		
	}
	elseif($status == 'all')
	{
		$data = \App\Models\Training::where('approve_direktur_id', \Auth::user()->id)->count();		
	}

	if($status=='approved')
	{
		$actual_bill = \App\Models\Training::where('approve_direktur_id', \Auth::user()->id)->where('status', 2)->where('approve_direktur_actual_bill', 1)->count();		
	}
	elseif($status == 'null')
	{
		$actual_bill = \App\Models\Training::where('approve_direktur_id', \Auth::user()->id)->where('status', 2)->where('status_actual_bill', 2)->whereNull('approve_direktur_actual_bill')->count();		
	}

	return $data + $actual_bill;
}

/**
 * @param  string
 * @return [type]
 */
function cek_training_atasan($status ='approved')
{
	// cek approval training
	if($status=='approved')
	{
		$data = \App\Models\Training::where('approved_atasan_id', \Auth::user()->id)->where('is_approved_atasan', 1)->count();		
	}
	elseif($status == 'null')
	{
		$data = \App\Models\Training::where('approved_atasan_id', \Auth::user()->id)->whereNull('is_approved_atasan')->count();		
	}
	elseif($data = 'all')
	{
		$data = \App\Models\Training::where('approved_atasan_id', \Auth::user()->id)->count();
	}

	// cek approval actual bill training
	if($status=='all')
	{
		$actual_bill = \App\Models\Training::where('approved_atasan_id', \Auth::user()->id)->where('status', 2)->count();		
	}
	elseif($status=='approved')
	{
		$actual_bill = \App\Models\Training::where('approved_atasan_id', \Auth::user()->id)->where('status', 2)->where('is_approve_atasan_actual_bill', 1)->count();		
	}
	elseif($status == 'null')
	{
		$actual_bill = \App\Models\Training::where('approved_atasan_id', \Auth::user()->id)->where('status_actual_bill', 2)->where('status', 2)->whereNull('is_approve_atasan_actual_bill')->count();		
	}

	return $data + $actual_bill;
}

/**
 * [approval_count_exit description]
 * @param  string $status  [description]
 * @param  string $jabatan [description]
 * @return [type]          [description]
 */
function approval_count_exit($status='all', $jabatan='direktur')
{
	if($jabatan == 'direktur')
	{
		if($status =='null')
		{
			return \App\Models\ExitInterview::where('approve_direktur_id', \Auth::user()->id)->whereNull('approve_direktur')->count();
		}
		elseif($status =='approved')
		{
			return \App\Models\ExitInterview::where('approve_direktur_id', \Auth::user()->id)->where('approve_direktur',1)->count();
		}
		elseif($status=='reject')
		{
			return \App\Models\ExitInterview::where('approve_direktur_id', \Auth::user()->id)->where('approve_direktur',0)->count();
		}
		elseif($status=='all')
		{
			return \App\Models\ExitInterview::where('approve_direktur_id', \Auth::user()->id)->count();
		}
	}
	elseif($jabatan == 'atasan')
	{
		if($status =='null')
		{
			return \App\Models\ExitInterview::where('approved_atasan_id', \Auth::user()->id)->whereNull('is_approved_atasan')->count();
		}
		elseif($status =='approved')
		{
			return \App\Models\ExitInterview::where('approved_atasan_id', \Auth::user()->id)->where('is_approved_atasan',1)->count();
		}
		elseif($status=='reject')
		{
			return \App\Models\ExitInterview::where('approved_atasan_id', \Auth::user()->id)->where('is_approved_atasan',0)->count();
		}
		elseif($status=='all')
		{
			return \App\Models\ExitInterview::where('approved_atasan_id', \Auth::user()->id)->count();
		}
	}

	return $data;
}

/**
 * @param  string
 * @param  string
 * @return [type]
 */
function approval_count_overtime($status='all', $jabatan='direktur')
{
	if($jabatan == 'direktur')
	{
		if($status =='null')
		{
			return \App\Models\OvertimeSheet::where('approve_direktur_id', \Auth::user()->id)->whereNull('approve_direktur')->count();
		}
		elseif($status =='approved')
		{
			return \App\Models\OvertimeSheet::where('approve_direktur_id', \Auth::user()->id)->where('approve_direktur',1)->count();
		}
		elseif($status=='reject')
		{
			return \App\Models\OvertimeSheet::where('approve_direktur_id', \Auth::user()->id)->where('approve_direktur',0)->count();
		}
		elseif($status=='all')
		{
			return \App\Models\OvertimeSheet::where('approve_direktur_id', \Auth::user()->id)->count();
		}
	}
	elseif($jabatan == 'atasan')
	{
		if($status =='null')
		{
			return \App\Models\OvertimeSheet::where('approved_atasan_id', \Auth::user()->id)->whereNull('is_approved_atasan')->count();
		}
		elseif($status =='approved')
		{
			return \App\Models\OvertimeSheet::where('approved_atasan_id', \Auth::user()->id)->where('is_approved_atasan',1)->count();
		}
		elseif($status=='reject')
		{
			return \App\Models\OvertimeSheet::where('approved_atasan_id', \Auth::user()->id)->where('is_approved_atasan',0)->count();
		}
		elseif($status=='all')
		{
			return \App\Models\OvertimeSheet::where('approved_atasan_id', \Auth::user()->id)->count();
		}
	}

	return $data;
}

/**
 * @param  string
 * @param  string
 * @return [type]
 */
function approval_count_medical($status='all', $jabatan='direktur')
{

	if($jabatan == 'direktur')
	{
		if($status =='null')
		{
			return \App\Models\MedicalReimbursement::where('approve_direktur_id', \Auth::user()->id)->whereNull('approve_direktur')->where('status' ,'<' ,3)->count();
		}
		elseif($status =='approved')
		{
			return \App\Models\MedicalReimbursement::where('approve_direktur_id', \Auth::user()->id)->where('approve_direktur',1)->count();
		}
		elseif($status=='reject')
		{
			return \App\Models\MedicalReimbursement::where('approve_direktur_id', \Auth::user()->id)->where('approve_direktur',0)->count();
		}
		elseif($status=='all')
		{
			return \App\Models\MedicalReimbursement::where('approve_direktur_id', \Auth::user()->id)->count();
		}
	}
	elseif($jabatan == 'atasan')
	{
		if($status =='null')
		{
			return \App\Models\MedicalReimbursement::where('approved_atasan_id', \Auth::user()->id)->whereNull('is_approved_atasan')->where('status' ,'<' ,3)->count();
		}
		elseif($status =='approved')
		{
			return \App\Models\MedicalReimbursement::where('approved_atasan_id', \Auth::user()->id)->where('is_approved_atasan',1)->count();
		}
		elseif($status=='reject')
		{
			return \App\Models\MedicalReimbursement::where('approved_atasan_id', \Auth::user()->id)->where('is_approved_atasan',0)->count();
		}
		elseif($status=='all')
		{
			return \App\Models\MedicalReimbursement::where('approved_atasan_id', \Auth::user()->id)->count();
		}
	}

	return $data;
}

/**
 * @param  string
 * @return [type]
 */
function approval_count_payment_request($status='all', $jabatan='direktur')
{

	if($jabatan == 'direktur')
	{
		if($status =='null')
		{
			return \App\Models\PaymentRequest::where('approve_direktur_id', \Auth::user()->id)->whereNull('approve_direktur')->count();
		}
		elseif($status =='approved')
		{
			return \App\Models\PaymentRequest::where('approve_direktur_id', \Auth::user()->id)->where('approve_direktur',1)->count();
		}
		elseif($status=='reject')
		{
			return \App\Models\PaymentRequest::where('approve_direktur_id', \Auth::user()->id)->where('approve_direktur',0)->count();
		}
		elseif($status=='all')
		{
			return \App\Models\PaymentRequest::where('approve_direktur_id', \Auth::user()->id)->count();
		}
	}
	elseif($jabatan == 'atasan')
	{
		if($status =='null')
		{
			return \App\Models\PaymentRequest::where('approved_atasan_id', \Auth::user()->id)->whereNull('is_approved_atasan')->count();
		}
		elseif($status =='approved')
		{
			return \App\Models\PaymentRequest::where('approved_atasan_id', \Auth::user()->id)->where('is_approved_atasan',1)->count();
		}
		elseif($status=='reject')
		{
			return \App\Models\PaymentRequest::where('approved_atasan_id', \Auth::user()->id)->where('is_approved_atasan',0)->count();
		}
		elseif($status=='all')
		{
			return \App\Models\PaymentRequest::where('approved_atasan_id', \Auth::user()->id)->count();
		}
	}

	return $data;
} 