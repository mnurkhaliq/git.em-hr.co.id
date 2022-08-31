<?php

use App\User;

function checkModule($module)
{
	$user = \Auth::user();
	if($user)
	{
		if($user->project_id != NULL)
		{
			$module = \App\Models\CrmModule::where('project_id', $user->project_id)->where('crm_product_id', $module)->count();
			if($module>0)
			{
				return true;
			} 
		}else{
			return true;
		}
	}
	return false;
}

function checkModuleAdmin($module){
	$user = \Auth::user();
	if($user)
	{
		if($user->project_id != NULL)
		{
			$admin = \App\Models\CrmModuleAdmin::where('user_id',$user->id)->where('product_id',$module)->count();
			if($admin>0)
			{
				return true;
			}
		}else{
			return true;
		}
	}
	return false;
}

function getAdminByModule($module){
    $user = \Auth::user();
    if($user) {
        return User::where('project_id',$user->project_id)->whereHas('modules', function ($q) use ($module) {
            $q->where('product_id', '=', $module);
        })->get();
    }
}

function checkUserLimit()
{
	$user = \Auth::user();
	if($user)
	{
		if($user->project_id != NULL)
		{
			$module = \App\Models\CrmModule::where('project_id', $user->project_id)->where('crm_product_id', 3)->first();
//			info("MODUL : ".$module);
			$User = total_karyawan();
			if($module && ($module->limit_user != NULL || $module->limit_user > 0)){
				if($User >= $module->limit_user)
				{
					return false;
				}else{
					return true;
				}
			}else
			{
				return true;
			}
		}else{
			return true;
		}
	}
	return false;
}


?>