<?php
/**
 * get Employee Rate
 */

function employee_rate($month)
{
	$period = 0;
	if($month == 1)
	{
		#$period  = \App\User::whereYear('date', date('Y'))->whereMonth('non_active_date', $month)->where('access_id', 2)->count();
	}
	else
	{

	}

	return $period;
}

/**
 * get Employee Resigness
 */
function employee_get_actives($StartDate, $StopDate, $position, $division, $branch, $currentMonth)
{
	$year = substr($currentMonth, 0, 4);
	$month = substr($currentMonth, 5, 7);
    $user  = \App\User::leftJoin('structure_organization_custom', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
    ->where(function($query) use ($year, $month, $StopDate) {
        $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d'))->orWhere('non_active_date', '>', $StopDate);
    })->where(function($query) use ($year, $month, $StopDate) {
        $query->whereNull('join_date')->orWhere(function($query) use ($year, $month, $StopDate) {
            $query->where('join_date', '<=', \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d'))->where('join_date', '<=', $StopDate);
        });
    })->where('users.project_id', \Auth::user()->project_id)->whereIn('access_id', ['1', '2']);
    
    if (!empty($position)) {
        $user = $user->where('structure_organization_custom.organisasi_position_id', $position);
    }
    if (!empty($division)) {
        $user = $user->where('structure_organization_custom.organisasi_division_id', $division);
    }
    if (!empty($branch)) {
        $user = $user->where('cabang_id', $branch);
    }
    
    $user = $user->count();
	
	return $user;
}

/**
 * get Employee Resigness
 */
function employee_get_resigness($StartDate, $StopDate, $position, $division, $branch, $currentMonth)
{
	$year = substr($currentMonth, 0, 4);
	$month = substr($currentMonth, 5, 7);
	$user  = \App\User::leftJoin('structure_organization_custom', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
                        ->whereBetween('resign_date', array($StartDate, $StopDate))
						->whereMonth('resign_date', $month)
						->whereYear('resign_date', $year)
						->whereIn('access_id', [1,2])
						->where('users.project_id', \Auth::user()->project_id);
    
    if (!empty($position)) {
        $user = $user->where('structure_organization_custom.organisasi_position_id', $position);
    }
    if (!empty($division)) {
        $user = $user->where('structure_organization_custom.organisasi_division_id', $division);
    }
    if (!empty($branch)) {
        $user = $user->where('cabang_id', $branch);
    }
    
    $user = $user->count();

	return $user;
}

/**
 * get Employee Resigness
 */
function employee_get_joinees($StartDate, $StopDate, $position, $division, $branch, $currentMonth)
{
	$year = substr($currentMonth, 0, 4);
	$month = substr($currentMonth, 5, 7);

	if(\Auth::user()->project_id != Null){
		$user  = \App\User::leftJoin('structure_organization_custom', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
                        ->whereBetween('join_date', array($StartDate, $StopDate))
						->whereMonth('join_date', $month)
						->whereYear('join_date', $year)
						->whereIn('access_id', ['1', '2'])

						->where('users.project_id', \Auth::user()->project_id);
	}else{
		$user  = \App\User::leftJoin('structure_organization_custom', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
                        ->whereBetween('join_date', array($StartDate, $StopDate))
						->whereMonth('join_date', $month)
						->whereYear('join_date', $year)
						->whereIn('access_id', ['1', '2']);
	}

    if (!empty($position)) {
        $user = $user->where('structure_organization_custom.organisasi_position_id', $position);
    }
    if (!empty($division)) {
        $user = $user->where('structure_organization_custom.organisasi_division_id', $division);
    }
    if (!empty($branch)) {
        $user = $user->where('cabang_id', $branch);
    }
    
    $user = $user->count();
	
	return $user;
}

function employee_get_end_contracts($StartDate, $StopDate, $position, $division, $branch, $currentMonth)
{
	$year = substr($currentMonth, 0, 4);
	$month = substr($currentMonth, 5, 7);

	if(\Auth::user()->project_id != Null){
		$user  = \App\User::leftJoin('structure_organization_custom', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
                        ->whereBetween('end_date_contract', array($StartDate, $StopDate))
						->whereMonth('end_date_contract', $month)
						->whereYear('end_date_contract', $year)
						->whereIn('access_id', ['1', '2'])

						->where('users.project_id', \Auth::user()->project_id);
	}else{
		$user  = \App\User::leftJoin('structure_organization_custom', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
                        ->whereBetween('end_date_contract', array($StartDate, $StopDate))
						->whereMonth('end_date_contract', $month)
						->whereYear('end_date_contract', $year)
						->whereIn('access_id', ['1', '2']);
	}

    if (!empty($position)) {
        $user = $user->where('structure_organization_custom.organisasi_position_id', $position);
    }
    if (!empty($division)) {
        $user = $user->where('structure_organization_custom.organisasi_division_id', $division);
    }
    if (!empty($branch)) {
        $user = $user->where('cabang_id', $branch);
    }
    
    $user = $user->count();
	
	return $user;
}

/**
 * Exist this month
 * @return number
 */
function employee_exit_this_month()
{
	if(\Auth::user()->project_id != Null){
		$user  = \App\User::whereYear('non_active_date', date('Y'))
						->whereMonth('non_active_date', date('m'))
						->whereIn('access_id', ['1', '2'])
						->where('project_id', \Auth::user()->project_id)
						->count();
	}else{
		$user  = \App\User::whereYear('non_active_date', date('Y'))
						->whereMonth('non_active_date', date('m'))
						->whereIn('access_id', ['1', '2'])
						->count();
	}
	
	
	return $user;
}

/**
 * Status Employee
 * @return integer
 */
function employee($status='all')
{
	$today = date('Y-m-d');

	$user = \Auth::user(); 
    if($user->project_id != NULL)
    {
        $employee = \App\User::whereIn('access_id', [1,2])->where('users.project_id', $user->project_id);
    }else{
        $employee = \App\User::whereIn('access_id', [1,2]);
    }

	if($status== 'all')
	{
		$employee = $employee->count();
	}

	if($status== 'active')
	{
	//	$employee = \App\Models\AbsensiItem::whereDate('date','=', $today)->count();
		if($user->project_id != NULL)
		{
			$employee = DB::table('absensi_item')
						->join('users', 'absensi_item.user_id','=', 'users.id')
						->where('users.project_id', \Auth::user()->project_id)
						->whereDate('absensi_item.date','=', $today)
                        ->groupBy('absensi_item.user_id')
                        ->select('absensi_item.user_id')
                        ->get()
						->count();
		}else{
			$employee = DB::table('absensi_item')
						->whereDate('date','=', $today)
                        ->groupBy('user_id')
                        ->select('user_id')
                        ->get()
						->count();
		}
		

	}

	if($status== 'on-leave')
	{
		if($user->project_id != NULL)
	    {
			$employee = \App\Models\CutiKaryawan::whereIn('cuti_karyawan.status', [2, 6, 8])
												->whereDate('cuti_karyawan.tanggal_cuti_start','<=', $today)
												->whereDate('cuti_karyawan.tanggal_cuti_end','>=', $today)
												->join('users','users.id','=','cuti_karyawan.user_id')
												->where('users.project_id', $user->project_id)
												->select('cuti_karyawan.*')->count();
	    }else{
			$employee = \App\Models\CutiKaryawan::whereIn('status', [2, 6, 8])
												->whereDate('tanggal_cuti_start','<=', $today)
												->whereDate('tanggal_cuti_end','>=', $today)->count();
	    }
	}

	if($status== 'on-tour')
	{
	/*	$employee = \App\Models\Training::where('status', 2)
										->whereDate('tanggal_kegiatan_start','<=', $today)
										->whereDate('tanggal_kegiatan_end','>=', $today)->count();	*/

		$employee = DB::table('training')
						->join('users', 'users.id', '=', 'training.user_id')
						->where('users.project_id', \Auth::user()->project_id)
						->where('training.status', 2)
						->whereDate('training.tanggal_kegiatan_start','<=', $today)
						->whereDate('training.tanggal_kegiatan_end','>=', $today)
						->count();
	}

	if($status == 'permanent')
	{
		$employee = $employee->where('organisasi_status', 'Permanent')->count();
	}

	if($status == 'contract')
	{
		$employee = $employee->where('organisasi_status', 'Contract')->count();
	}

    if($status == 'internship')
	{
		$employee = $employee->where('organisasi_status', 'Internship')->count();
    }
    
    if($status == 'outsource')
	{
		$employee = $employee->where('organisasi_status', 'Outsource')->count();
    }
    
    if($status == 'freelance')
	{
		$employee = $employee->where('organisasi_status', 'Freelance')->count();
	}

	if($status == 'late-comers')
	{
	//	$employee = \App\Models\AbsensiItem::whereNotNull('late')->whereDate('date','=', $today)->count();
		$employee = DB::table('absensi_item')
						->join('users', 'absensi_item.user_id','=', 'users.id')
						->where('users.project_id', \Auth::user()->project_id)
						->whereNotNull('absensi_item.late')
						->whereDate('absensi_item.date','=', $today)
						->count();
	}

	return $employee;
}


function employee_attrition($StartDate, $StopDate, $position, $division, $branch, $currentMonth, $nextmonth){
	$year = substr($currentMonth, 0, 4);
	$month = substr($currentMonth, 5, 7);

	$next_month = substr($nextmonth, 5, 7);
	$next_month_year = substr($nextmonth, 0, 4);
	$jumlah_karyawan_resign_perbulan = \App\User::leftJoin('structure_organization_custom', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
                                                    ->whereBetween('non_active_date', array($StartDate, $StopDate))
													->whereMonth('non_active_date', $month)
													->whereYear('non_active_date', $year)
													->whereIn('access_id', [1,2])
													->where('users.project_id', \Auth::user()->project_id);

    if (!empty($position)) {
        $jumlah_karyawan_resign_perbulan = $jumlah_karyawan_resign_perbulan->where('structure_organization_custom.organisasi_position_id', $position);
    }
    if (!empty($division)) {
        $jumlah_karyawan_resign_perbulan = $jumlah_karyawan_resign_perbulan->where('structure_organization_custom.organisasi_division_id', $division);
    }
    if (!empty($branch)) {
        $jumlah_karyawan_resign_perbulan = $jumlah_karyawan_resign_perbulan->where('cabang_id', $branch);
    }
    
    $jumlah_karyawan_resign_perbulan = $jumlah_karyawan_resign_perbulan->count();

	$jumlah_karyawan_sebelum_resign_perbulan = \App\User::leftJoin('structure_organization_custom', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
                                                            ->whereIn('access_id', [1,2])
														//	->whereBetween('join_date', array($StartDate, $StopDate))
															->whereMonth('join_date', '<',$next_month)
															->whereYear('join_date', $next_month_year)
															->where('users.project_id', \Auth::user()->project_id);

    if (!empty($position)) {
        $jumlah_karyawan_sebelum_resign_perbulan = $jumlah_karyawan_sebelum_resign_perbulan->where('structure_organization_custom.organisasi_position_id', $position);
    }
    if (!empty($division)) {
        $jumlah_karyawan_sebelum_resign_perbulan = $jumlah_karyawan_sebelum_resign_perbulan->where('structure_organization_custom.organisasi_division_id', $division);
    }
    if (!empty($branch)) {
        $jumlah_karyawan_sebelum_resign_perbulan = $jumlah_karyawan_sebelum_resign_perbulan->where('cabang_id', $branch);
    }
    
    $jumlah_karyawan_sebelum_resign_perbulan = $jumlah_karyawan_sebelum_resign_perbulan->count();

	if($jumlah_karyawan_resign_perbulan == 0 || $jumlah_karyawan_sebelum_resign_perbulan == 0){
		$attrition = 0;
	}else{
		$attrition = round(($jumlah_karyawan_resign_perbulan / $jumlah_karyawan_sebelum_resign_perbulan) * 100);
	}

	return $attrition;
}
