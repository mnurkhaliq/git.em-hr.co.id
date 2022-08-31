<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftDetail extends Model
{
    protected $table = 'shift_detail';

    public function shift()
    {
        return $this->belongsTo('App\Models\Shift')->withTrashed();
    }
}
