<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    protected $table = 'loan_payment';
    protected $guarded = [];

    public function loan()
    {
        return $this->belongsTo('App\Models\Loan', 'loan_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo('\App\User', 'approval_user_id', 'id');
    }

    public function payrollHistory()
    {
        return $this->belongsTo('App\Models\PayrollHistory', 'payroll_history_id', 'id');
    }
}
