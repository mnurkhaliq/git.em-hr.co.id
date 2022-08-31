<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'grade';

    public function sub_grade(){
        return $this->hasMany('App\Models\SubGrade', 'grade_id', 'id');
    }
}
