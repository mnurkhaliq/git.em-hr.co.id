<?php

/**
 * Get List Backup
 * @return [type] [description]
 */
function get_kpi_modules()
{
//	$auth = \Auth::user();
//	if($auth)
//	{
//		if($auth->project_id != NULL)
//        {
//        	return \App\Models\KpiModule::orderBy('id', 'ASC')->where('project_id',$auth->project_id)->get();
//        } else{
        	return \App\Models\KpiModule::orderBy('id', 'ASC')->get();
//        }
//	}



}
function get_kpi_periods()
{
	$auth = \Auth::user();
	if($auth)
	{
		if($auth->project_id != NULL)
        {
        	return \App\Models\KpiPeriod::orderBy('id', 'DESC')->where('project_id',$auth->project_id)->get();
        } else{
            return \App\Models\KpiPeriod::orderBy('id', 'DESC')->get();
        }
	}
}
function checkManager(){
    $user = \Auth::user();
    if($user)
    {
        $checkStructureExists = \App\Models\StructureOrganizationCustom::find($user->structure_organization_custom_id);
        if($checkStructureExists){
            $childStructures = \App\Models\StructureOrganizationCustom::where('parent_id',$user->structure_organization_custom_id)->count();
            if($childStructures>0){
                return true;
            }
        }
    }
    return false;
}
function getAllPositions()
{
    $user = \Auth::user();
    $positions = \App\Models\StructureOrganizationCustom::leftJoin('organisasi_position as op','structure_organization_custom.organisasi_position_id','=','op.id')
        ->leftJoin('organisasi_division as od','structure_organization_custom.organisasi_division_id','=','od.id')
        ->leftJoin('organisasi_title as ot','structure_organization_custom.organisasi_title_id','=','ot.id')
        ->select(['structure_organization_custom.id',\DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), '')) as position"),'structure_organization_custom.*']);
    if($user->project_id != NULL)
    {
        $positions = $positions->where('structure_organization_custom.project_id',$user->project_id);
    }
    return $positions->get();
}
function getJuniorPositions()
{
    $user = \Auth::user();
    if($user->structure_organization_custom_id == null){
        return json_encode(null);
    }
    $positions = \App\Models\StructureOrganizationCustom::where('parent_id',$user->structure_organization_custom_id)
        ->leftJoin('organisasi_position as op','structure_organization_custom.organisasi_position_id','=','op.id')
        ->leftJoin('organisasi_division as od','structure_organization_custom.organisasi_division_id','=','od.id')
        ->leftJoin('organisasi_title as ot','structure_organization_custom.organisasi_title_id','=','ot.id')
        ->select(['structure_organization_custom.id',\DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), '')) as position")]);
    return $positions->get();
}
function getSettingModuleByID($settings,$id)
{
    foreach ($settings as $setting){
        if($setting->id == $id){
            return $setting->module;
        }
    }

    return null;
}

//function getKpiList($user_id){
//    return \DB::select(\DB::raw("select ke.*, u.name as supervisor, CONCAT(DATE_FORMAT(kp.start_date, '%d %M %Y'), ' - ',DATE_FORMAT(kp.end_date, '%d %M %Y')) AS period
//                                        ,CONCAT(COALESCE(op.name,''),' - ',COALESCE(od.name,'')) as position, concat(kp.min_rate, ' - ',kp.max_rate) as rate from kpi_employee ke
//                                        join kpi_periods kp on ke.kpi_period_id = kp.id
//                                        join structure_organization_custom so on ke.structure_organization_custom_id = so.id
//                                        left join organisasi_position op on so.organisasi_position_id = op.id
//                                        left join organisasi_division od on so.organisasi_division_id = od.id
//                                        left join users u on ke.supervisor_id = u.id
//                                        where ke.user_id = $user_id and
//                                        (select count(st.status) from kpi_setting_status st join kpi_setting_scoring ss on st.kpi_setting_scoring_id = ss.id
//                                        where ss.kpi_period_id=kp.id and st.status=1
//                                        and (st.structure_organization_custom_id is null or st.structure_organization_custom_id=ke.structure_organization_custom_id)) =
//                                        (select count(*) from kpi_setting_scoring ss2 where ss2.kpi_period_id = kp.id)"));
//}

function getKpiList($user_id){
    return \DB::select(\DB::raw("select ke.*, u.name as supervisor, CONCAT(DATE_FORMAT(kp.start_date, '%d %M %Y'), ' - ',DATE_FORMAT(kp.end_date, '%d %M %Y')) AS period
                                        ,CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), '')) as position, concat(kp.min_rate, ' - ',kp.max_rate) as rate from kpi_employee ke 
                                        join kpi_periods kp on ke.kpi_period_id = kp.id
                                        join structure_organization_custom so on ke.structure_organization_custom_id = so.id
                                        left join organisasi_position op on so.organisasi_position_id = op.id
                                        left join organisasi_division od on so.organisasi_division_id = od.id
                                        left join organisasi_title ot on so.organisasi_title_id = ot.id
                                        left join users u on ke.supervisor_id = u.id
                                        where ke.user_id = $user_id and kp.is_lock = 1"));
}

//function getKpiDetail($user,$manager, $id){
//
//    $employee = \App\Models\KpiEmployee::with('user')->find($id);
//    if($employee){
//        $structure = \App\Models\StructureOrganizationCustom::where('id', $employee->structure_organization_custom_id)->first();
//
//        if(($user && $employee->user->id == $user->id) || ($manager && $structure->parent_id == $manager->structure_organization_custom_id)) {
//            $period = \App\Models\KpiPeriod::with('settings.module')->where(['id' => $employee->kpi_period_id, 'status' => 1])->first();
//            $approval = \DB::select(\DB::raw("select count(st.status) as total from kpi_setting_status st join kpi_setting_scoring ss on st.kpi_setting_scoring_id = ss.id
//                                                where ss.kpi_period_id=$period->id and st.status=1 and (st.structure_organization_custom_id is null
//                                                or st.structure_organization_custom_id=$employee->structure_organization_custom_id)"));
//
//            // Apakah jumlah yg sudah diapprove = yg seharusnya diapprove
//            if ($approval[0]->total == count($period->settings)) {
//                $items = \App\Models\KpiItem::with(['scoring' => function ($query) use ($employee) {
//                    $query->where('kpi_employee_id', $employee->id);
//                }])->join('kpi_setting_scoring as ss', 'kpi_items.kpi_setting_scoring_id', '=', 'ss.id')
//                    ->join('kpi_periods as kp', 'ss.kpi_period_id', '=', 'kp.id')
//                    ->where('kp.id', $period->id)
//                    ->whereRaw(\DB::raw("(kpi_items.structure_organization_custom_id is null or kpi_items.structure_organization_custom_id = $employee->structure_organization_custom_id)"))
//                    ->select('kpi_items.*')
//                    ->get();
//                $structure = \DB::table('structure_organization_custom as so')
//                    ->leftJoin('organisasi_position as op', 'so.organisasi_position_id', '=', 'op.id')
//                    ->leftJoin('organisasi_division as od', 'so.organisasi_division_id', '=', 'od.id')
//                    ->select([\DB::raw("CONCAT(COALESCE(op.name,''),' - ',COALESCE(od.name,'')) as position")])
//                    ->where('so.id', $employee->structure_organization_custom_id)
//                    ->first();
//                $param = ['employee' => $employee, 'period' => $period, 'items' => $items, 'position' => $structure];
//                return $param;
//            }
//            else{
//                info("gak!");
//            }
//        }
//
//    }
//    return null;
//}

function getKpiDetail($user,$manager, $id){

    $employee = \App\Models\KpiEmployee::with('user')->find($id);
    if($employee){
        $structure = \App\Models\StructureOrganizationCustom::where('id', $employee->structure_organization_custom_id)->first();
        if(($user && $employee->user->id == $user->id) || ($manager && $structure->parent_id == $manager->structure_organization_custom_id)) {
            $period = \App\Models\KpiPeriod::with('settings.module')->where(['id' => $employee->kpi_period_id, 'status' => 1,'is_lock'=>1])->first();
            if ($period) {
                $items = \App\Models\KpiItem::with(['scoring' => function ($query) use ($employee) {
                    $query->where('kpi_employee_id', $employee->id);
                }])->join('kpi_setting_scoring as ss', 'kpi_items.kpi_setting_scoring_id', '=', 'ss.id')
                    ->join('kpi_periods as kp', 'ss.kpi_period_id', '=', 'kp.id')
                    ->where('kp.id', $period->id)
                    ->whereRaw(\DB::raw("(kpi_items.structure_organization_custom_id is null or kpi_items.structure_organization_custom_id = $employee->structure_organization_custom_id)"))
                    ->select('kpi_items.*')
                    ->get();
                $structure = \DB::table('structure_organization_custom as so')
                    ->leftJoin('organisasi_position as op', 'so.organisasi_position_id', '=', 'op.id')
                    ->leftJoin('organisasi_division as od', 'so.organisasi_division_id', '=', 'od.id')
                    ->leftJoin('organisasi_title as ot', 'so.organisasi_title_id', '=', 'ot.id')
                    ->select([\DB::raw("CONCAT(COALESCE(op.name,''),IF(od.name IS NOT NULL, CONCAT(' - ',COALESCE(od.name,'')), ''),IF(ot.name IS NOT NULL, CONCAT(' - ',COALESCE(ot.name,'')), '')) as position")])
                    ->where('so.id', $employee->structure_organization_custom_id)
                    ->first();
                $param = ['employee' => $employee, 'period' => $period, 'items' => $items, 'position' => $structure];
                return $param;
            }
            else{
                info("gak!");
            }
        }

    }
    return null;
}

function getKpiScoringStatus($kpi_period_id){
    // Jumlah setting admin yg sudah di published
    $param['count_admin_published'] = \App\Models\KpiSettingStatus::where(['status'=>1])->whereNull('structure_organization_custom_id')->whereHas('setting', function ($q) use ($kpi_period_id){
        $q->where(['kpi_period_id'=>$kpi_period_id,'kpi_module_id'=>1]);
    })->count();
    // Jumlah setting position yg sudah di approve
    $param['count_manager_published'] = \App\Models\KpiSettingStatus::where(['status'=>1])->whereNotNull('structure_organization_custom_id')->whereHas('setting',function ($q) use ($kpi_period_id){
        $q->where(['kpi_period_id'=>$kpi_period_id,'kpi_module_id'=>2]);
    })->count();

    $param['manager_published'] = \App\Models\KpiSettingStatus::where(['status'=>1])->whereNotNull('structure_organization_custom_id')->whereHas('setting',function ($q) use ($kpi_period_id){
        $q->where(['kpi_period_id'=>$kpi_period_id,'kpi_module_id'=>2]);
    })->pluck('structure_organization_custom_id');
    // Jumlah position
    $param['count_position'] = \App\Models\StructureOrganizationCustom::whereRaw('parent_id in (SELECT id from structure_organization_custom)')->count();
    return $param;
}

function getKpiPositions(){
    return \App\Models\StructureOrganizationCustom::whereRaw('parent_id in (SELECT id from structure_organization_custom)')->get();
}