<?php 

/**
 * Cek Payroll User ID array
 */
function cek_payroll_user_id_array($month, $year)
{
	// Payroll History
	$result = \App\Models\PayrollHistory::whereHas('user')->whereMonth('created_at', $month)->whereYear('created_at', $year);

	return $result;
}


/**
 * Cek Payroll User ID
 */
function get_payroll_history($user_id, $month, $year)
{
	// Payroll History
	$row = \App\Models\PayrollHistory::where('user_id', $user_id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->orderBy('id', 'DESC')->first();

	return $row;
}
function get_burden_allowance()
{
    $biaya_jabatan = \App\Models\PayrollOthers::where('id', 1)->first()->value;
    return $biaya_jabatan;
}

/**
 * Cek Payroll User ID
 */
function cek_payroll_user_id($user_id, $month, $year)
{
    // Payroll History
    $count = \App\Models\PayrollHistory::where('user_id', $user_id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->count();

    if($count) return true;

    $count = \App\Models\Payroll::where('user_id', $user_id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->count();

    if($count) return true;

    return false;
}

/**
 * Round Down
 */
function roundDown($decimal, $precision)
{
    $sign = $decimal > 0 ? 1 : -1;
    $base = pow(10, $precision);
    return floor(abs($decimal) * $base) / $base * $sign;
}


function get_ptkp($user_id, $tahun = false)
{
    $user = \App\User::where('id', $user_id)->first();

    $ptkp = \App\Models\PayrollPtkp::where('id', 1)->first();

    if ($tahun && $tahun > \Carbon\Carbon::now()->format('Y')) {
        $payroll_jenis_kelamin = $user->jenis_kelamin;
        $payroll_marital_status = $user->marital_status;
    } else {
        $payroll_jenis_kelamin = $user->payroll_jenis_kelamin;
        $payroll_marital_status = $user->payroll_marital_status;
    }

    if ($payroll_jenis_kelamin == 'Female' || $payroll_jenis_kelamin == ""){
        $data = $ptkp->bujangan_wanita;
    }elseif ($payroll_jenis_kelamin == 'Male') {
        if($payroll_marital_status == 'Bujangan/Wanita' || $payroll_marital_status == "")
        {
            $data = $ptkp->bujangan_wanita;
        }
        if($payroll_marital_status == 'Menikah')
        {
            $data = $ptkp->menikah;
        }
        if($payroll_marital_status == 'Menikah Anak 1')
        {
            $data = $ptkp->menikah_anak_1;
        }
        if($payroll_marital_status == 'Menikah Anak 2')
        {
            $data = $ptkp->menikah_anak_2;
        }
        if($payroll_marital_status == 'Menikah Anak 3')
        {
            $data = $ptkp->menikah_anak_3;
        }
    }
    return $data;
}

function get_status_ptkp($user_id, $tahun = false)
{
    $user = \App\User::where('id', $user_id)->first();

    if ($tahun && $tahun > \Carbon\Carbon::now()->format('Y')) {
        $payroll_jenis_kelamin = $user->jenis_kelamin;
        $payroll_marital_status = $user->marital_status;
    } else {
        $payroll_jenis_kelamin = $user->payroll_jenis_kelamin;
        $payroll_marital_status = $user->payroll_marital_status;
    }

    if ($payroll_jenis_kelamin == 'Female' || $payroll_jenis_kelamin == ""){
        return 'TK-0';
    }elseif ($payroll_jenis_kelamin == 'Male') {
        if($payroll_marital_status == 'Bujangan/Wanita' || $payroll_marital_status == "")
        {
            return 'TK-0';
        }
        if($payroll_marital_status == 'Menikah')
        {
            return 'K-0';
        }
        if($payroll_marital_status == 'Menikah Anak 1')
        {
            return 'K-1';
        }
        if($payroll_marital_status == 'Menikah Anak 2')
        {
            return 'K-2';
        }
        if($payroll_marital_status == 'Menikah Anak 3')
        {
            return 'K-3';
        }
    }
}

function getpphYear($nominal)
{
    $yearly_income_tax = 0;
    foreach (\App\Models\PayrollPPH::all() as $key => $value) {
        if (($nominal <= $value->batas_atas && $nominal >= $value->batas_bawah && $value->batas_atas != null) || ($nominal >= $value->batas_bawah && $value->batas_atas == null)) {
            $yearly_income_tax += ($value->tarif / 100) * ($nominal - $value->batas_bawah);
        } else if ($nominal >= $value->batas_atas && $value->batas_atas != null) {
            $yearly_income_tax += ($value->tarif / 100) * ($value->batas_atas - $value->batas_bawah);
        }
    }

    return $yearly_income_tax;
}

/**
 * Get History Earning
 */
function get_payroll_earning_history_param($payroll_id, $year, $month, $id)
{
    if(!isset($payroll_id)) return 0;

//    $data = \App\Models\PayrollEarningsEmployeeHistory::where('payroll_earning_id', $id)->where('payroll_id', $payroll_id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->orderBy('created_at', 'DESC')->first();
    $data = \App\Models\PayrollEarningsEmployeeHistory::where('payroll_earning_id', $id)->where('payroll_id', $payroll_id)->orderBy('created_at', 'DESC')->first();
    if($data)
    {
        return $data->nominal;
    }
    else
    {
        return 0;
    }
}

function get_payroll_deduction_history_param($payroll_id, $year, $month, $id)
{
    if(!isset($payroll_id)) return 0;
    $data = \App\Models\PayrollDeductionsEmployeeHistory::where('payroll_deduction_id', $id)->where('payroll_id', $payroll_id)->orderBy('created_at', 'DESC')->first();
    if($data)
    {
        return $data->nominal;
    }
    else
    {
        return 0;
    }
}


/**
 * Get Payroll pph21
 */
function get_payroll_history_param($user_id, $year, $month, $field="")
{
	$data = \App\Models\PayrollHistory::where('user_id', $user_id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->orderBy('created_at', 'DESC')->first();

	if(empty($field)) return $data;

	if($data)
	{
		return $data->$field;
	}
	else
	{
		return 0;
	}
}

/**
 * Bukti Potong
 * Integer / String
 */
function bukti_potong($id, $type)
{
    $data_arr = \App\Models\PayrollHistory::select('*',DB::raw('MONTH(created_at) month'))->where('payroll_id', $id)->groupBy('month')->orderBy('id', 'DESC')->get();

    $data = [];
    foreach($data_arr as $item)
    {
        $row = \App\Models\PayrollHistory::select('*',DB::raw('MONTH(created_at) month'))->whereMonth("created_at", $item->month)->where('payroll_id', $id)->orderBy('id', 'DESC')->first();
        $data[] = $row;
    }

    $nominal = 0;

    if($type == 'gaji' || $type== 'bruto')
    {
        foreach($data as $k => $item)
        {
            $nominal 	+= $item->salary;
        }
    }

    if($type == 'pph21' || $type== 'bruto')
    {
        foreach($data as $k => $item)
        {
            $nominal 	+= $item->pph21;
        }
    }

    if($type == 'tunjangan' || $type== 'bruto')
    {
        foreach($data as $k => $item)
        {
            $earning = \App\Models\PayrollEarningsEmployeeHistory::where('payroll_id', $item->payroll_id)->groupBy('payroll_earning_id')->orderBy('created_at', 'DESC')->get();

            if($earning)
            {
                foreach($earning as $i)
                {
                    $nominal 	+= $i->nominal;
                }
            }
        }
    }

    if($type == 'premi' || $type== 'bruto')
    {
        foreach($data as $k => $item)
        {
            $nominal 	+= $item->bpjs_ketenagakerjaan_employee + $item->bpjs_kesehatan_employee + $item->bpjs_pensiun_employee;
        }
    }

    if($type == 'bonus' || $type== 'bruto')
    {
        foreach($data as $k => $item)
        {
            $nominal 	+= $item->bonus;
        }
    }

    return $nominal;
}

function send_bukti_potong($id, $tahun, $type)
{
	$data = \App\Models\PayrollHistory::select('*', DB::raw('MONTH(created_at) month'))->where('is_lock', 1)->where('user_id', $id)->whereYear('created_at', $tahun)->groupBy('month')->orderBy('month', 'ASC')->get();
	
	$data_start = $data[0];
	
	$data_end = $data[count($data) - 1];

 	$nominal = 0;

    if($type == 'count')
    {
        return count($data);
    }

 	if($type == 'start')
 	{
 		$nominal = sprintf('%02d', $data_start->month);
 	}
 	if($type =='end')
 	{
 		$nominal = sprintf('%02d', $data_end->month);
    }
    if($type =='serial_bukti_potong')
 	{
 		$nominal = str_pad($data_start->serial_bukti_potong, 7, '0', STR_PAD_LEFT);
    }

	if($type == 'gaji' || $type== 'bruto')
	{
		foreach($data as $k => $item)
		{
			$nominal 	+= $item->salary;
		}	
	}

	if($type == 'pph21')
	{
		foreach($data as $k => $item)
		{
			$nominal 	+= $item->pph21;
		}	
	}

	if($type == 'tunjangan' || $type== 'bruto')
	{
		foreach($data as $k => $item)
		{
            $nominal 	+= $item->overtime;

			$earning = \App\Models\PayrollEarningsEmployeeHistory::where('payroll_id', $item->id)->groupBy('payroll_earning_id')->orderBy('created_at', 'DESC')->get();
	
			if($earning)
			{
				foreach($earning as $i)
				{
                    if($i->payrollEarnings->taxable == 1)
					    $nominal 	+= $i->nominal;					
				}
			}
		}	
	}

	if($type == 'premi' || $type== 'bruto')
	{	
		foreach($data as $k => $item)
		{
			$nominal 	+= $item->bpjs_jkk_company + $item->bpjs_jkm_company + $item->bpjs_kesehatan_company;
		}	
	}

	if($type == 'bonus' || $type== 'bruto')
	{
		foreach($data as $k => $item)
		{
			$nominal 	+= ($item->bonus+$item->thr);
		}	
	}
	if($type == 'burden' || $type=='pengurang')
	{
		foreach($data as $k => $item)
		{
			$nominal 	+= $item->burden_allow;
		}	
	}
	if($type == 'jht' || $type=='pengurang')
	{
		foreach($data as $k => $item)
		{
			$nominal 	+= $item->bpjs_pensiun_employee + $item->bpjs_ketenagakerjaan_employee;

            $deduction = \App\Models\PayrollDeductionsEmployeeHistory::where('payroll_id', $item->id)->groupBy('payroll_deduction_id')->orderBy('created_at', 'DESC')->get();
	
			if($deduction)
			{
				foreach($deduction as $i)
				{
                    if($i->payrollDeductions->taxable == 1)
					    $nominal 	+= $i->nominal;					
				}
			}
		}	
	}

	return $nominal;		
}

/**
 * Get All Year Payroll
 * @return array
 */
function get_year_payroll()
{	
	$data = \App\Models\PayrollHistory::select(DB::raw('YEAR(created_at) year'))->groupBy('year')->get();
	$year = [];

	foreach($data as $item)
	{
		$year[] = $item->year;
	}
	
	return $year;
}

/**
 * Get Deduction Employee
 * @param  $id
 * @return object
 */
function getDeductionEmployee($id, $payroll_id, $type = 'current')
{
	if($type == 'history')
	{
		$item = \App\Models\PayrollDeductionsEmployeeHistory::where('payroll_deduction_id', $id)->where('payroll_id', $payroll_id)->first();
	}
	else $item = \App\Models\PayrollDeductionsEmployee::where('payroll_deduction_id', $id)->where('payroll_id', $payroll_id)->first();

	return $item;
}

/**
 * Get Earning Employee
 * @param  $id
 * @return object
 */
function getEarningEmployee($id, $payroll_id, $type='current')
{
	if($type == 'history')
	{
		$item = \App\Models\PayrollEarningsEmployeeHistory::where('payroll_earning_id', $id)->where('payroll_id', $payroll_id)->first();
	}
	else $item = \App\Models\PayrollEarningsEmployee::where('payroll_earning_id', $id)->where('payroll_id', $payroll_id)->first();

	return $item;
}
function getDeductionEmployeeDataHistory($id, $payroll_id)
{
	$item = \App\Models\PayrollDeductionsEmployeeHistory::where('payroll_deduction_id', $id)->where('payroll_id', $payroll_id)->first();
	return $item;
}

function getEarningEmployeeDataHistory($id, $payroll_id)
{
	$item = \App\Models\PayrollEarningsEmployeeHistory::where('payroll_earning_id', $id)->where('payroll_id', $payroll_id)->first();
	return $item;
}

/**
 * Deduction Employee History
 */
function payrollDeductionsEmployeeHistory($id)
{
	return App\Models\PayrollDeductionsEmployeeHistory::where('payroll_id', $id)->get();
}


/**
 * Earning Employee History
 */
function payrollEarningsEmployeeHistory($id)
{
	return App\Models\PayrollEarningsEmployeeHistory::where('payroll_id', $id)->get();
}


function payrollEarningsEmployeeData($id,$bulan,$tahun)
{
	$bulanArray = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
	
	$intBulan = array_search($bulan, $bulanArray);

	if($intBulan == (Int)date('m') and $tahun == date('Y'))
    {
    	return App\Models\PayrollEarningsEmployee::where('payroll_id', $id)->get();
    }else{
    	return \App\Models\PayrollEarningsEmployeeHistory::where('payroll_id', $id)->get();
    }
}

function payrollDeductionsEmployeeData($id,$bulan,$tahun)
{
	$bulanArray = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
	$intBulan = array_search($bulan, $bulanArray);

	if($intBulan == (Int)date('m') and $tahun == date('Y'))
    {
    	return App\Models\PayrollDeductionsEmployee::where('payroll_id', $id)->get();
    }else{
    	return \App\Models\PayrollDeductionsEmployeeHistory::where('payroll_id', $id)->get();
    }
}


/**
 * Earning
 * @return objects
 */
function get_earnings()
{
	$user = \Auth::user();
    if($user->project_id != NULL)
    {
    	return \App\Models\PayrollEarnings::where('project_id', $user->project_id)->get();
    }else{
    	return \App\Models\PayrollEarnings::all();
    }
}

/**
 * Deductions
 * @return objects
 */
function get_deductions()
{
	$user = \Auth::user();
    if($user->project_id != NULL)
    {
    	return \App\Models\PayrollDeductions::where('project_id', $user->project_id)->get();
    }else{
    	return \App\Models\PayrollDeductions::all();
    }
}

function get_setting_payroll($id){
	if(\Auth::user()->project_id != NULL){
		$value = \App\Models\PayrollNpwp::where('id_payroll_npwp',$id)->where('project_id', \Auth::user()->project_id)->get();
		if(count($value) < 1){
			return "";
		}else{
			return \App\Models\PayrollNpwp::where('id_payroll_npwp',$id)->where('project_id', \Auth::user()->project_id)->first()->value;
		}
	}else{
		$value = \App\Models\PayrollNpwp::where('id_payroll_npwp',$id)->whereNull('project_id')->get();
		if(count($value) < 1){
			return "";
		}else{
			return \App\Models\PayrollNpwp::where('id_payroll_npwp',$id)->whereNull('project_id')->first()->value;
		}
	}
}

function getProrate($user_id) {
    $user = \App\User::find($user_id);

    if ($user->payroll_cycle_id) {
        $cycle = \App\Models\PayrollCycle::find($user->payroll_cycle_id);
    } else {
        if ($user->project_id != null) {
            $cycle = \App\Models\PayrollCycle::where('project_id', $user->project_id)->where('key_name', 'payroll')->first();
        } else {
            $cycle = \App\Models\PayrollCycle::whereNull('project_id')->where('key_name', 'payroll')->first();
        }
    }
    
    if (!$cycle) {
        return 1;
    } else {
        $end_date = fix_date($cycle->end_date, \Session::get('m-month'), \Session::get('m-year'));

        // Start bulan yang sama
        if ($cycle->start_date < $cycle->end_date) {
            $start_date = fix_date($cycle->start_date, \Session::get('m-month'), \Session::get('m-year'));
        }
        // Start bulan sebelumnya
        else {
            $prev = get_previous_month(\Session::get('m-month'), \Session::get('m-year'));
            $start_date = fix_date($cycle->start_date, $prev['month'], $prev['year']);
        }
    }

    $prorate = get_setting('prorate');

    $start = \Carbon\Carbon::parse(date_format($start_date, "Y-m-d"))->startOfDay();
    $end = \Carbon\Carbon::parse(date_format($end_date, "Y-m-d"))->endOfDay();

    $join = \Carbon\Carbon::parse($user->join_date ?: $start)->startOfDay();
    $resign = \Carbon\Carbon::parse($user->non_active_date ?: $end)->endOfDay();

    if (!$prorate || ($join->lte($start) && $resign->gte($end))) {
        return 1;
    }

    if ($prorate == 2) {
        $publicHoliday = hari_libur($start, $end);
    
        $ShiftScheduleChange = \App\Models\ShiftScheduleChange::where('change_date', '<=', $end)->where('change_date', '>=', $start)->whereHas('shiftScheduleChangeEmployees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('change_date', 'DESC')->with('shift.details')->get();
        $currentShift = \App\Models\ShiftScheduleChange::where('change_date', '<', $start)->whereHas('shiftScheduleChangeEmployees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('change_date', 'DESC')->with('shift.details')->first();
        $currentShift = $currentShift ? $currentShift->shift : \App\Models\Shift::where('id', $user->shift_id)->with('details')->first();
    }

    $pembilang = 0;
    $penyebut = 0;
    $start->subDay();
    while ($start->diff($end)->days) {
        $loopDate = $start->addDay();
        
        if ($prorate == 2) {
            $loopDateName = $loopDate->format('l');
            $loopDateYMD = $loopDate->format('Y-m-d');

            $loopPublicHoliday = $publicHoliday->filter(function ($value) use ($loopDateYMD) {
                return $value->tanggal == $loopDateYMD;
            })->first();

            $loopShiftScheduleChange = $ShiftScheduleChange->filter(function ($value) use ($loopDateYMD) {
                return $value->change_date <= $loopDateYMD;
            })->first();
            $loopShiftScheduleChange = ($loopShiftScheduleChange ? $loopShiftScheduleChange->shift : $currentShift);
            $loopShiftScheduleChangeDay = $loopShiftScheduleChange ? $loopShiftScheduleChange->details->filter(function ($value) use ($loopDateName) {
                return $value->day == $loopDateName;
            })->first() : $loopShiftScheduleChange;

            if ((!$loopShiftScheduleChange && !$loopPublicHoliday) || ($loopShiftScheduleChange && $loopShiftScheduleChangeDay && (!$loopPublicHoliday || ($loopPublicHoliday && $loopShiftScheduleChange->is_holiday)))) {
                $penyebut++;
                if ($loopDate->lte($resign) && $loopDate->gte($join)) {
                    $pembilang++;
                }
            }
        } else {
            $penyebut++;
            if ($loopDate->lte($resign) && $loopDate->gte($join)) {
                $pembilang++;
            }
        }
    }

    return round($pembilang/$penyebut, 3);
}

function getLoanPayroll($user_id) {
    $user = \App\User::find($user_id);

    if ($user->payroll_cycle_id) {
        $cycle = \App\Models\PayrollCycle::find($user->payroll_cycle_id);
    } else {
        if ($user->project_id != null) {
            $cycle = \App\Models\PayrollCycle::where('project_id', $user->project_id)->where('key_name', 'payroll')->first();
        } else {
            $cycle = \App\Models\PayrollCycle::whereNull('project_id')->where('key_name', 'payroll')->first();
        }
    }
    
    if (!$cycle) {
        $start = \Carbon\Carbon::parse(\Session::get('m-year').'-'.\Session::get('m-month').'-1')->startOfMonth()->format('Y-m-d');
        $end = \Carbon\Carbon::parse(\Session::get('m-year').'-'.\Session::get('m-month').'-1')->endOfMonth()->format('Y-m-d');
    } else {
        $end_date = fix_date($cycle->end_date, \Session::get('m-month'), \Session::get('m-year'));

        // Start bulan yang sama
        if ($cycle->start_date < $cycle->end_date) {
            $start_date = fix_date($cycle->start_date, \Session::get('m-month'), \Session::get('m-year'));
        }
        // Start bulan sebelumnya
        else {
            $prev = get_previous_month(\Session::get('m-month'), \Session::get('m-year'));
            $start_date = fix_date($cycle->start_date, $prev['month'], $prev['year']);
        }

        $start = \Carbon\Carbon::parse(date_format($start_date, "Y-m-d"))->startOfDay()->format('Y-m-d');
        $end = \Carbon\Carbon::parse(date_format($end_date, "Y-m-d"))->endOfDay()->format('Y-m-d');
    }

    return \App\Models\LoanPayment::whereNull('status')->whereBetween('due_date', [$start, $end])->whereHas('loan', function ($query) use ($user) {
        $query->where('user_id', $user->id)->where('payment_type', 1);
    })->with('loan')->get();
}

function getBusinessTripPayment($user_id) {
    $user = \App\User::find($user_id);

    if ($user->payroll_cycle_id) {
        $cycle = \App\Models\PayrollCycle::find($user->payroll_cycle_id);
    } else {
        if ($user->project_id != null) {
            $cycle = \App\Models\PayrollCycle::where('project_id', $user->project_id)->where('key_name', 'payroll')->first();
        } else {
            $cycle = \App\Models\PayrollCycle::whereNull('project_id')->where('key_name', 'payroll')->first();
        }
    }
    
    if (!$cycle) {
        $start = \Carbon\Carbon::parse(\Session::get('m-year').'-'.\Session::get('m-month').'-1')->startOfMonth()->format('Y-m-d');
        $end = \Carbon\Carbon::parse(\Session::get('m-year').'-'.\Session::get('m-month').'-1')->endOfMonth()->format('Y-m-d');
    } else {
        $end_date = fix_date($cycle->end_date, \Session::get('m-month'), \Session::get('m-year'));

        // Start bulan yang sama
        if ($cycle->start_date < $cycle->end_date) {
            $start_date = fix_date($cycle->start_date, \Session::get('m-month'), \Session::get('m-year'));
        }
        // Start bulan sebelumnya
        else {
            $prev = get_previous_month(\Session::get('m-month'), \Session::get('m-year'));
            $start_date = fix_date($cycle->start_date, $prev['month'], $prev['year']);
        }

        $start = \Carbon\Carbon::parse(date_format($start_date, "Y-m-d"))->startOfDay()->format('Y-m-d');
        $end = \Carbon\Carbon::parse(date_format($end_date, "Y-m-d"))->endOfDay()->format('Y-m-d');
    }

    return \App\Models\Training::where('user_id', $user_id)->where('disbursement_claim', 'Next Payroll')->whereBetween('updated_at', [$start, $end])->get();
}

function getCashAdvancePayment($user_id) {
    $user = \App\User::find($user_id);

    if ($user->payroll_cycle_id) {
        $cycle = \App\Models\PayrollCycle::find($user->payroll_cycle_id);
    } else {
        if ($user->project_id != null) {
            $cycle = \App\Models\PayrollCycle::where('project_id', $user->project_id)->where('key_name', 'payroll')->first();
        } else {
            $cycle = \App\Models\PayrollCycle::whereNull('project_id')->where('key_name', 'payroll')->first();
        }
    }
    
    if (!$cycle) {
        $start = \Carbon\Carbon::parse(\Session::get('m-year').'-'.\Session::get('m-month').'-1')->startOfMonth()->format('Y-m-d');
        $end = \Carbon\Carbon::parse(\Session::get('m-year').'-'.\Session::get('m-month').'-1')->endOfMonth()->format('Y-m-d');
    } else {
        $end_date = fix_date($cycle->end_date, \Session::get('m-month'), \Session::get('m-year'));

        // Start bulan yang sama
        if ($cycle->start_date < $cycle->end_date) {
            $start_date = fix_date($cycle->start_date, \Session::get('m-month'), \Session::get('m-year'));
        }
        // Start bulan sebelumnya
        else {
            $prev = get_previous_month(\Session::get('m-month'), \Session::get('m-year'));
            $start_date = fix_date($cycle->start_date, $prev['month'], $prev['year']);
        }

        $start = \Carbon\Carbon::parse(date_format($start_date, "Y-m-d"))->startOfDay()->format('Y-m-d');
        $end = \Carbon\Carbon::parse(date_format($end_date, "Y-m-d"))->endOfDay()->format('Y-m-d');
    }
    $cash_advance = \App\Models\CashAdvance::select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id')
                ->where('user_id', $user_id)->where('disbursement_claim', 'Next Payroll')->whereBetween('cash_advance.updated_at', [$start, $end])->get();

    return $cash_advance;
}

function getCashAdvancePaymentDetail($id){
    $cash_advance = \App\Models\CashAdvance::select('cash_advance.*', \DB::raw('SUM(cash_advance_form.nominal_approved) as total_amount_approved'), \DB::raw('SUM(cash_advance_form.nominal_claimed) as total_amount_claimed'))
                ->join('cash_advance_form','cash_advance_form.cash_advance_id', '=', 'cash_advance.id')->groupBy('cash_advance.id')
                ->where('cash_advance.id', $id)->first();
    return $cash_advance->total_amount_approved - $cash_advance->total_amount_claimed;
}

function get_payroll_cycle($key_name = 'attendance'){
    $user = \Auth::user();
    if ($key_name == 'attendance_custom' || $key_name == 'payroll_custom') {
        $value = \App\Models\PayrollCycle::where('key_name', $key_name)->get();
    } else {
        if($user->project_id != NULL){
            $value = \App\Models\PayrollCycle::where('project_id',$user->project_id)->where('key_name', $key_name)->first();
        }else{
            $value = \App\Models\PayrollCycle::whereNull('project_id')->where('key_name', $key_name)->first();
        }
    }
    return $value;
}

function get_user_payroll_cycle($key_name, $user_id){
    $user = \App\User::find($user_id);
    
    if ($user->project_id != NULL) {
        $value = \App\Models\PayrollCycle::where('project_id', $user->project_id)->where('key_name', $key_name)->first();
    } else {
        $value = \App\Models\PayrollCycle::whereNull('project_id')->where('key_name', $key_name)->first();
    }

    if ($key_name == 'attendance' && $user->attendance_cycle_id) {
        $value = \App\Models\PayrollCycle::where('id', $user->attendance_cycle_id)->first();
    } else if ($key_name == 'payroll' && $user->payroll_cycle_id) {
        $value = \App\Models\PayrollCycle::where('id', $user->payroll_cycle_id)->first();
    }
    
    return $value;
}

function get_cycle($start, $end, $month, $year){
    $end_date = fix_date($end,$month,$year);
    
    // Start bulan yang sama
    if($start<$end){
        $start_date = fix_date($start,$month,$year);
    }

    // Start bulan sebelumnya
    else{
        $prev = get_previous_month($month,$year);
        $start_date = fix_date($start,$prev['month'],$prev['year']);
    }

    return $start_date->format('d/m/Y') . ' - ' . $end_date->format('d/m/Y');
}

function get_cycle_array($start, $end, $month, $year){
    $end_date = fix_date($end,$month,$year);
    
    // Start bulan yang sama
    if($start<$end){
        $start_date = fix_date($start,$month,$year);
    }

    // Start bulan sebelumnya
    else{
        $prev = get_previous_month($month,$year);
        $start_date = fix_date($start,$prev['month'],$prev['year']);
    }

    return [$start_date, $end_date];
}

function get_payroll_attendance($month,$year,$user_id,$id = null){
    if ($id && ($payroll_history = \App\Models\PayrollHistory::find($id)) && $payroll_history->payroll_attendance_label) {
        $end_date = fix_date($payroll_history->payroll_attendance_end,$month,$year);
    
        // Start bulan yang sama
        if($payroll_history->payroll_attendance_start<$payroll_history->payroll_attendance_end){
            $start_date = fix_date($payroll_history->payroll_attendance_start,$month,$year);
        }
        // Start bulan sebelumnya
        else{
            $prev = get_previous_month($month,$year);
            $start_date = fix_date($payroll_history->payroll_attendance_start,$prev['month'],$prev['year']);
        }
        $data = [
            'label' => $payroll_history->payroll_attendance_label,
            'start_date' => date_format($start_date,"d/m/Y"),
            'end_date' => date_format($end_date,"d/m/Y"),
            'attendance' => \App\Models\AbsensiItem::whereBetween('date',[$start_date,$end_date])->where('user_id',$user_id)->orderBy('date','desc')->get()
        ];
        return $data;
    } else {
        $user = \App\User::find($user_id);
        if ($user->attendance_cycle_id) {
            $cycle = \App\Models\PayrollCycle::find($user->attendance_cycle_id);
        } else {
            if($user->project_id != NULL){
                $cycle = \App\Models\PayrollCycle::where('project_id',$user->project_id)->where('key_name', 'attendance')->first();
            }else{
                $cycle = \App\Models\PayrollCycle::whereNull('project_id')->where('key_name', 'attendance')->first();
            }
        }
        if(!$cycle) {
            return null;
        }else{
            if ($month && $year) {
                $end_date = fix_date($cycle->end_date,$month,$year);
        
                // Start bulan yang sama
                if($cycle->start_date<$cycle->end_date){
                    $start_date = fix_date($cycle->start_date,$month,$year);
                }
                // Start bulan sebelumnya
                else{
                    $prev = get_previous_month($month,$year);
                    $start_date = fix_date($cycle->start_date,$prev['month'],$prev['year']);
                }
                $data = [
                    'label' => $cycle->label,
                    'start_date' => date_format($start_date,"d/m/Y"),
                    'end_date' => date_format($end_date,"d/m/Y"),
                    'attendance' => \App\Models\AbsensiItem::whereBetween('date',[$start_date,$end_date])->where('user_id',$user_id)->orderBy('date','desc')->get()
                ];
                return $data;
            } else {
                return $cycle;
            }
        }
    }
}
function get_payroll_overtime($month,$year,$user_id,$id = null){
    if ($id && ($payroll_history = \App\Models\PayrollHistory::find($id)) && $payroll_history->payroll_attendance_label) {
        $end_date = fix_date($payroll_history->payroll_attendance_end,$month,$year);
    
        // Start bulan yang sama
        if($payroll_history->payroll_attendance_start<$payroll_history->payroll_attendance_end){
            $start_date = fix_date($payroll_history->payroll_attendance_start,$month,$year);
        }
        // Start bulan sebelumnya
        else{
            $prev = get_previous_month($month,$year);
            $start_date = fix_date($payroll_history->payroll_attendance_start,$prev['month'],$prev['year']);
        }
        $data = [
            'label' => $payroll_history->payroll_attendance_label,
            'start_date' => date_format($start_date,"d/m/Y"),
            'end_date' => date_format($end_date,"d/m/Y"),
            'overtime' => \App\Models\OvertimeSheetForm::whereBetween('claim_approval',[$start_date,$end_date])->whereHas('overtimeSheet', function($query) use ($user_id) {
                $query->where('user_id',$user_id)->where('status_claim',2);
            })->whereNotNull('payroll_calculate')->orderBy('tanggal','desc')->get()
        ];
        return $data;
    } else {
        $user = \App\User::find($user_id);
        if ($user->attendance_cycle_id) {
            $cycle = \App\Models\PayrollCycle::find($user->attendance_cycle_id);
        } else {
            if($user->project_id != NULL){
                $cycle = \App\Models\PayrollCycle::where('project_id',$user->project_id)->where('key_name', 'attendance')->first();
            }else{
                $cycle = \App\Models\PayrollCycle::whereNull('project_id')->where('key_name', 'attendance')->first();
            }
        }
        if(!$cycle) {
            return null;
        }else{
            if ($month && $year) {
                $end_date = fix_date($cycle->end_date,$month,$year,true);
                
                // Start bulan yang sama
                if($cycle->start_date<$cycle->end_date){
                    $start_date = fix_date($cycle->start_date,$month,$year);
                }
                // Start bulan sebelumnya
                else{
                    $prev = get_previous_month($month,$year);
                    $start_date = fix_date($cycle->start_date,$prev['month'],$prev['year']);
                }
                $data = [
                    'label' => $cycle->label,
                    'start_date' => date_format($start_date,"d/m/Y"),
                    'end_date' => date_format($end_date,"d/m/Y"),
                    'overtime' => \App\Models\OvertimeSheetForm::whereBetween('claim_approval',[$start_date,$end_date])->whereHas('overtimeSheet', function($query) use ($user_id) {
                        $query->where('user_id',$user_id)->where('status_claim',2);
                    })->whereNotNull('payroll_calculate')->orderBy('tanggal','desc')->get()
                ];
                return $data;
            } else {
                return $cycle;
            }
        }
    }
}
function get_previous_month($month,$year){
    if($month == 1)
        return ['year'=>($year-1), 'month'=> 12];
    else
        return ['year'=>$year, 'month'=> ($month-1)];
}
function fix_date($date,$month,$year,$end=false){
    $lastDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    if($date>$lastDate)
        $date = $lastDate;
    $date = $end ? date_create("$year-$month-$date 23:59:59") : date_create("$year-$month-$date");
    return $date;
}

function getWaitingPayslipCount(){
    $user = \Auth::user();
    return \App\Models\RequestPaySlip::join('users','request_pay_slip.user_id','=','users.id')->where(['project_id'=>$user->project_id,'request_pay_slip.status'=>'1'])->count();
}