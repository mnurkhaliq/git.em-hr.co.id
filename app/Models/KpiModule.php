<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiModule extends Model
{
    //
    protected $table = 'kpi_modules';

    public function settings(){
        return $this->hasMany('App\Models\KpiSettingScoring', 'kpi_module_id');
    }
    public function employees(){
        return $this->hasMany('App\Models\KpiEmployee', 'kpi_module_id');
    }
}
