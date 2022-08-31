<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentInterviewer extends Model
{
    //
    protected $table = 'recruitment_interviewers';

    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
    public function recruitment(){
        return $this->belongsTo('App\Models\RecruitmentRequest','recruitment_request_id','id');
    }
}
