<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiItem extends Model
{
    //
    protected $table = 'kpi_items';
    protected $fillable = ['kpi_setting_scoring_id','structure_organization_custom_id','name','weightage'];

    public function setting()
    {
        return $this->belongsTo('App\Models\KpiSettingScoring', 'kpi_setting_scoring_id');
    }
    public function structure()
    {
        return $this->belongsTo('App\Models\StructureOrganizationCustom', 'structure_organization_custom_id');
    }
    public function scoring(){
        return $this->hasMany('App\Models\KpiEmployeeScoring', 'kpi_item_id');
    }

}
