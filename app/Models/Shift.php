<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Shift extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'shift';

    public function details(){
        return $this->hasMany('App\Models\ShiftDetail', 'shift_id', 'id');
    }

    public function users(){
        return $this->hasMany('App\User', 'shift_id', 'id');
    }

    public function userTemps(){
        return $this->hasMany('App\Models\UserTemp', 'shift_id', 'id');
    }

    public function shiftScheduleChanges(){
        return $this->hasMany('App\Models\ShiftScheduleChange', 'shift_id', 'id');
    }

    public function shiftScheduleChangeTemps(){
        return $this->hasMany('App\Models\ShiftScheduleChangeTemp', 'shift_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($query) {
            // $query->users()->each(function($item) {
            //     $item->shift()->dissociate();
            //     $item->save();
            // });

            $query->users()->update(['shift_id' => null]);

            $query->userTemps()->update(['shift_id' => null]);

            $query->shiftScheduleChanges()->where('change_date', '>', Carbon::now())->delete();

            $query->shiftScheduleChangeTemps()->delete();
        });
    }
}
