<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentApplicationHistory extends Model
{
    //
    protected $table = 'recruitment_application_history';

    public function status(){
        return $this->hasOne('App\Models\RecruitmentApplicationStatus','id','application_status');
    }
    public function application(){
        return $this->belongsTo('App\Models\RecruitmentApplication','recruitment_application_id','id');
    }
    public function phase(){
        return $this->hasOne('App\Models\RecruitmentPhase','id','recruitment_phase_id');
    }
}
