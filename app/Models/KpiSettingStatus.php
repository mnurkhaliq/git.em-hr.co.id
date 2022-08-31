<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiSettingStatus extends Model
{
    //
    protected $table = 'kpi_setting_status';
    protected $fillable = ['kpi_setting_scoring_id','structure_organization_custom_id','status'];

    public function setting()
    {
        return $this->belongsTo('App\Models\KpiSettingScoring', 'kpi_setting_scoring_id');
    }
}
