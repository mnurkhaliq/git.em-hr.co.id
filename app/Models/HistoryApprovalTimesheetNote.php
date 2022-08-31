<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryApprovalTimesheetNote extends Model
{
    protected $table = 'history_approval_timesheet_note';

    protected $guarded = [];

    public function historyApprovalTimesheet()
    {
        return $this->belongsTo('App\Models\HistoryApprovalTimesheet');
    }

    public function timesheetTransaction()
    {
        return $this->belongsTo('App\Models\TimesheetTransaction');
    }
}
