<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollEarnings extends Model
{
    protected $table = 'payroll_earnings';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_created', 'user_id');
    }
}
