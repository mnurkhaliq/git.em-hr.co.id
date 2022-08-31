<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingOther extends Model
{
    //
    protected $table = 'training_other';

    public function report()
    {
        return $this->hasMany('App\Models\TrainingOtherReport', 'training_other_id', 'id');
    }
}
