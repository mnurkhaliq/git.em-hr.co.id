<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimePayroll extends Model
{
    protected $guarded = [];

    public function overtimePayrollType()
    {
        return $this->belongsTo('App\Models\OvertimePayrollType');
    }

    public function overtimePayrollEarning()
    {
        return $this->hasMany('App\Models\OvertimePayrollEarning');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::deleted(function ($query) {
    //         $query->overtimePayrollEarning()->delete();

    //         $query->users()->dissociate();
    //         // $query->save();
    //     });
    // }
}
