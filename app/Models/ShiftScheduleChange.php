<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftScheduleChange extends Model
{
    protected $guarded = [];

    public function shift()
    {
        return $this->belongsTo('App\Models\Shift')->withTrashed();
    }

    public function shiftScheduleChangeEmployees()
    {
        return $this->hasMany('App\Models\ShiftScheduleChangeEmployee');
    }
}
