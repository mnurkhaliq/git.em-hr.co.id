<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentRequestDetail extends Model
{
    //
    protected $table = 'recruitment_request_detail';

    public function recruitment(){
        return $this->belongsTo('App\Models\RecruitmentRequest','recruitment_request_id');
    }
    public function type(){
        return $this->belongsTo('App\Models\RecruitmentType','recruitment_type_id');
    }
}
