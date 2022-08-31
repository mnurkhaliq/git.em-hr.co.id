<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalApplication extends Model
{
    //
    protected $table = 'internal_applications';

    public function applicant(){
        return $this->belongsTo('App\User','user_id','id');
    }
    public function application(){
        return $this->belongsTo('App\Models\RecruitmentApplication','recruitment_application_id','id');
    }
}
