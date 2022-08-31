<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingDaily extends Model
{
    //
    protected $table = 'training_daily';

    public function report()
    {
        return $this->hasMany('App\Models\TrainingDailyReport', 'training_daily_id', 'id');
    }
}
