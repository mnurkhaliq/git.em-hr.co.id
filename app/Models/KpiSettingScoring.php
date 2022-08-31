<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiSettingScoring extends Model
{
    //
    protected $table = 'kpi_setting_scoring';
    protected $fillable = ['kpi_period_id','kpi_module_id','weightage'];

    public function period()
    {
        return $this->belongsTo('App\Models\KpiPeriod','kpi_period_id');
    }
    public function module()
    {
        return $this->belongsTo('App\Models\KpiModule','kpi_module_id');
    }
    public function items()
    {
        return $this->hasMany('App\Models\KpiItem', 'kpi_setting_scoring_id');
    }
    public function status()
    {
        return $this->hasMany('App\Models\KpiSettingStatus', 'kpi_setting_scoring_id');
    }
}
