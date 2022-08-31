<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentApplication extends Model
{
    //
    protected $table = 'recruitment_applications';

    public function recruitmentRequest(){
        return $this->hasOne('App\Models\RecruitmentRequest','id','recruitment_request_id');
    }
    public function status(){
        return $this->hasOne('App\Models\RecruitmentApplicationStatus','id','application_status');
    }
    public function currentPhase(){
        return $this->hasOne('App\Models\RecruitmentPhase','id','current_phase_id');
    }
    public function internal(){
        return $this->hasOne('App\Models\InternalApplication','recruitment_application_id','id');
    }
    public function external(){
        return $this->hasOne('App\Models\ExternalApplication','recruitment_application_id','id');
    }
    public function histories(){
        return $this->hasMany('App\Models\RecruitmentApplicationHistory','recruitment_application_id','id');
    }
    public function interviewers(){
        return $this->hasMany('App\Models\ApplicantInterviewer','recruitment_application_id','id');
    }
}
