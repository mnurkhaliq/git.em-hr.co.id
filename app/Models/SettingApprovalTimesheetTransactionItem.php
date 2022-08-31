<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingApprovalTimesheetTransactionItem extends Model
{
    protected $table = 'setting_approval_timesheet_transaction_item';

    public function timesheetCategory()
    {
    	return $this->belongsTo('\App\Models\TimesheetCategory');
    }

    public function user()
    {
    	return $this->belongsTo('\App\User');
    }
}
