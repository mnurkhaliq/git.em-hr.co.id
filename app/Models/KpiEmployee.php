<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiEmployee extends Model
{
    //
    protected $table = 'kpi_employee';
    protected $fillable = ['user_id','structure_organization_custom_id','kpi_period_id','supervisor_id'
        ,'employee_input_date','supervisor_input_date','employee_feedback','final_score','status'];

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
    public function supervisor(){
        return $this->belongsTo('App\User','supervisor_id');
    }
    public function period(){
        return $this->belongsTo('App\Models\KpiPeriod','kpi_period_id');
    }
    public function structure(){
        return $this->belongsTo('App\Models\StructureOrganizationCustom', 'structure_organization_custom_id');
    }
    public function scorings(){
        return $this->hasMany('App\Models\KpiEmployeeScoring','kpi_employee_id');
    }
}
