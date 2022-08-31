<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeFacility extends Model
{
    //
    protected $table = 'employee_facility_recruitment';

    public function application(){
        return $this->belongsTo('App\Models\ExternalApplication','external_application_id','id');
    }
    public function asset(){
        return $this->belongsTo('App\Models\AssetType','asset_type_id','id');
    }
}
