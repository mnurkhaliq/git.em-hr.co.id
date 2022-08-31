<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantInterviewer extends Model
{
    //
    protected $table = 'applicant_interviewers';
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
    public function application(){
        return $this->belongsTo('App\Models\RecruitmentApplication','recruitment_application_id','id');
    }
}
