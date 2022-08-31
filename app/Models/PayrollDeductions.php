<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollDeductions extends Model
{
    protected $table = 'payroll_deductions';
    public function user()
    {
        return $this->belongsTo('App\User', 'user_created', 'user_id');
    }
}
