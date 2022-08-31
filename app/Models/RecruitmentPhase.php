<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentPhase extends Model
{
    //
    protected $table = 'recruitment_phases';

    public function type(){
        $this->belongsTo('App\Models\RecruitmentType','recruitment_type_id','id');
    }
    public function applications(){
        $this->hasMany('App\Models\RecruitmentApplication','current_phase_id','id');
    }
}
