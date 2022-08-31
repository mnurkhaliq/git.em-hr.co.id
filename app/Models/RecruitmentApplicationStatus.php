<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentApplicationStatus extends Model
{
    //
    protected $table = 'recruitment_application_status';

    public function history(){
        return $this->hasMany('App\Models\RecruitmentApplicationHistory','application_status','id');
    }
    public function applications(){
        return $this->hasMany('App\Models\RecruitmentApplication','application_status','id');
    }
}
