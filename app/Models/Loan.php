<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table = 'loan';

    public function user()
    {
    	return $this->belongsTo('\App\User', 'user_id', 'id');
    }

    public function receiptApprover()
    {
    	return $this->belongsTo('\App\User', 'approval_collateral_receipt_user_id', 'id');
    }

    public function physicalApprover()
    {
    	return $this->belongsTo('\App\User', 'approval_collateral_physical_user_id', 'id');
    }

    public function loanApprover()
    {
    	return $this->belongsTo('\App\User', 'approval_loan_user_id', 'id');
    }

    public function asset()
    {
        return $this->hasMany('\App\Models\LoanAsset', 'loan_id', 'id');
    }

    public function payment()
    {
        return $this->hasMany('\App\Models\LoanPayment', 'loan_id', 'id');
    }

    public function historyApproval()
    {
        return $this->hasMany('\App\Models\HistoryApprovalLoan', 'loan_id', 'id');
    }
}
