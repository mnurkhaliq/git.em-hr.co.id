<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimesheetPeriodTransaction extends Model
{
    public function timesheetPeriod()
    {
        return $this->belongsTo('\App\timesheetPeriod');
    }

    public function timesheetActivity()
    {
        return $this->belongsTo('\App\Models\TimesheetActivity')->withTrashed();
    }

    public function timesheetCategory()
    {
        return $this->belongsTo('\App\Models\TimesheetCategory')->withTrashed();
    }

    public function userApproved()
    {
        return $this->hasOne('\App\User', 'id', 'approval_id');
    }
}
