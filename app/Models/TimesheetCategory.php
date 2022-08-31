<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimesheetCategory extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public function timesheetActivity()
    {
        return $this->hasMany('\App\Models\TimesheetActivity');
    }

    public function timesheetActivityTrashed()
    {
        return $this->hasMany('\App\Models\TimesheetActivity')->withTrashed();
    }

    public function settingApproval()
    {
        return $this->hasMany('\App\Models\SettingApprovalTimesheetTransactionItem');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($query) {
            $query->timesheetActivity()->delete();
        });
    }
}
