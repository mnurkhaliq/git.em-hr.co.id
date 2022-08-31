<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimesheetTransaction extends Model
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

    public function historyApprovalTimesheetNote()
    {
        return $this->hasMany('\App\Models\HistoryApprovalTimesheetNote')->orderBy('history_approval_timesheet_id');
    }
}
