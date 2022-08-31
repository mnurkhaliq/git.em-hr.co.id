<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollHistory extends Model
{
    protected $table = 'payroll_history';
    const CREATED_AT = 'post_date';
    
    /**
     * [user description]
     * @return [type] [description]
     */
    public function payroll()
    {
        return $this->hasOne('\App\Models\Payroll', 'id', 'payroll_id');
    }

    /**
     * [user description]
     * @return [type] [description]
     */
    public function user()
    {
        return $this->hasOne('\App\User', 'id', 'user_id');
    }
    
     /**
     * Earnings
     * @return object
     */
    public function payrollEarningsEmployee()
    {
    	return $this->hasMany('App\Models\PayrollEarningsEmployeeHistory', 'payroll_id', 'id');
    }

    /**
     * Deductions
     * @return object
     */
    public function payrollDeductionsEmployee()
    {
    	return $this->hasMany('App\Models\PayrollDeductionsEmployeeHistory', 'payroll_id', 'id');
    }

    public function loanPayments()
    {
    	return $this->hasMany('App\Models\LoanPayment', 'payroll_history_id', 'id');
    }

    public function businessTrips()
    {
    	return $this->hasMany('App\Models\Training', 'payroll_history_id', 'id');
    }

    public function cashAdvances()
    {
    	return $this->hasMany('App\Models\CashAdvance', 'payroll_history_id', 'id');
    }
}
