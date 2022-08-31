<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterCategoryVisit extends Model
{
    protected $table = 'master_category_visit';

    protected $guarded = [];

    public function settingVisitActivities()
    {
        return $this->hasMany('\App\Models\SettingActivityVisit', 'master_category_visit_id')->where('setting_visit_activity.isactive', 1);
    }
    
    public function usercategorivisit()
    {
    	return $this->belongsTo('\App\User', 'master_category_visit_id', 'id');
    }
}
