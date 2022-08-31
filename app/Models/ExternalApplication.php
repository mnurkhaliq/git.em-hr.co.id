<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalApplication extends Model
{
    //
    protected $table = 'external_applications';
    public function applicant(){
        return $this->belongsTo('App\Models\Jobseeker','jobseeker_id','id');
    }
    public function application(){
        return $this->belongsTo('App\Models\RecruitmentApplication','recruitment_application_id','id');
    }
    public function facilites(){
        return $this->hasMany('App\Models\EmployeeFacility','external_application_id','id');
    }

}
