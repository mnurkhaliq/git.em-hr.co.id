<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiEmployeeScoring extends Model
{
    //
    protected $table = 'kpi_employee_scoring';
    protected $fillable = ['kpi_employee_id','kpi_item_id','self_score','supervisor_score','justification','comment'];

    public function employee(){
        return $this->belongsTo('App\Models\KpiEmployee','kpi_employee_id');
    }
    public function kpi_item(){
        return $this->belongsTo('App\Models\KpiItem','kpi_item_id');
    }
}
