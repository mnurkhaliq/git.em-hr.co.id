<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimesheetActivity extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public function timesheetCategory()
    {
        return $this->belongsTo('\App\Models\TimesheetCategory')->withTrashed();
    }

    public function timesheetTransaction()
    {
        return $this->hasMany('\App\Models\TimesheetTransaction');
    }
}
