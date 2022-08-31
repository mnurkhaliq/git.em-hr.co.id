<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimesheetPeriod extends Model
{
    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function timesheetTransaction()
    {
        return $this->hasMany('\App\Models\TimesheetTransaction')->orderBy('date', 'ASC')->orderBy('start_time', 'ASC');
    }

    public function timesheetPeriodTransaction()
    {
        return $this->hasMany('\App\Models\TimesheetPeriodTransaction')->orderBy('date', 'ASC')->orderBy('start_time', 'ASC');
    }

    public function historyApproval()
    {
        return $this->hasMany('\App\Models\HistoryApprovalTimesheet', 'timesheet_period_id', 'id')->orderBy('setting_approval_level_id', 'ASC');
    }
}
