<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jobseeker extends Model
{
    //
    protected $table = 'jobseekers';

    public function educations(){
        return $this->hasMany('App\Models\JobseekerEducation','jobseeker_id','id');
    }

    public function applications(){
        return $this->hasMany('App\Models\ExternalApplication','jobseeker_id','id');
    }

    public function updatedBy(){
        return $this->belongsTo('App\User','updated_by','id');
    }

    public function createdBy(){
        return $this->belongsTo('App\User','created_by','id');
    }

    public function options(){
        return $this->hasMany('App\Models\JobseekerOption','jobseekers_id','id');
    }

    public function tags(){
        return $this->hasMany('App\Models\JobseekerTag','jobseekers_id','id');
    }
}
