<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftScheduleChangeEmployee extends Model
{
    protected $guarded = [];

    public function shiftScheduleChange()
    {
        return $this->belongsTo('App\Models\ShiftScheduleChange');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
