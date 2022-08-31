<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingTransportationReport extends Model
{
    use HasFactory;
    protected $table = 'training_transportation_report';

    public function userApproved()
    {
        return $this->hasOne('\App\User', 'id', 'approval_id');
    }
}
