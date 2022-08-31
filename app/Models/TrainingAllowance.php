<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingAllowance extends Model
{
    //
    protected $table = 'training_allowance';

    public function report()
    {
        return $this->hasMany('App\Models\TrainingAllowanceReport', 'training_allowance_id', 'id');
    }
}
