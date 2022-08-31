<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingTransportation extends Model
{
    //
    protected $table = 'training_transportation';
    
    public function transportation_type()
    {
        return $this->hasOne('\App\Models\TrainingTransportationType', 'id', 'training_transportation_type_id');
    }

    public function report()
    {
        return $this->hasMany('App\Models\TrainingTransportationReport', 'training_transportation_id', 'id');
    }
}
