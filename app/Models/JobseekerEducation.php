<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobseekerEducation extends Model
{
    //
    protected $table = 'jobseeker_educations';

    public function jobseeker(){
        return $this->belongsTo('App\Models\Jobseeker','jobseeker_id','id');
    }
    public function education(){
        return $this->belongsTo('App\Models\Education','education_id','id');
    }
}
