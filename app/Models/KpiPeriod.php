<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiPeriod extends Model
{
    //
    protected $table = 'kpi_periods';
    protected $fillable = ['start_date','end_date','min_rate','max_rate','project_id','status'];

    public function settings(){
        return $this->hasMany('App\Models\KpiSettingScoring', 'kpi_period_id');
    }
    public function employee(){
        return $this->hasMany('App\Models\KpiEmployee', 'kpi_period_id');
    }
    public function items()
    {
        return $this->hasManyThrough(
            'App\Models\KpiItem',
            'App\Models\KpiSettingScoring',
            'kpi_period_id',
            'kpi_setting_scoring_id',
            'id',
            'id'
        );
    }
}
