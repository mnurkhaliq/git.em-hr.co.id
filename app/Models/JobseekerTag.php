<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobseekerTag extends Model
{
    protected $table = 'jobseekers_tags';
    protected $guarded = [];

    public function jobseekers()
    {
        return $this->belongsTo('App\Models\Jobseekers', 'jobseekers_id', 'id');
    }
}
