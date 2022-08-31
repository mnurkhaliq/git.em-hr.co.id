<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryApprovalTimesheet extends Model
{
    protected $table = 'history_approval_timesheet';

    public function timesheetPeriod()
    {
        return $this->belongsTo('App\Models\TimesheetPeriod');
    }

    public function level()
    {
        return $this->hasOne('App\Models\SettingApprovalLevel', 'id', 'setting_approval_level_id');
    }

    public function structure()
    {
        return $this->hasOne('\App\Models\StructureOrganizationCustom', 'id', 'structure_organization_custom_id');
    }

    public function userApproved()
    {
        return $this->hasOne('\App\User', 'id', 'approval_id');
    }

    public function historyApprovalTimesheetNote()
    {
        return $this->hasMany('\App\Models\HistoryApprovalTimesheetNote')->join('timesheet_transactions', 'timesheet_transactions.id', '=', 'history_approval_timesheet_note.timesheet_transaction_id')->orderBy('timesheet_transactions.date', 'ASC')->orderBy('timesheet_transactions.start_time', 'ASC');
    }
}
