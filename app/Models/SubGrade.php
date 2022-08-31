<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubGrade extends Model
{
    protected $table = 'sub_grade';

    public function grade(){
        return $this->belongsTo('App\Models\Grade', 'grade_id', 'id');
    }
}
