<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingAllowanceReport extends Model
{
    use HasFactory;
    protected $table = 'training_allowance_report';

    public function userApproved()
    {
        return $this->hasOne('\App\User', 'id', 'approval_id');
    }
}
