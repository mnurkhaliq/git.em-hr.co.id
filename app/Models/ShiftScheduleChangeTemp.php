<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftScheduleChangeTemp extends Model
{
    protected $table = 'shift_schedule_change_temp';

    protected $guarded = [];

    public function shift()
    {
        return $this->belongsTo('App\Models\Shift');
    }
}
